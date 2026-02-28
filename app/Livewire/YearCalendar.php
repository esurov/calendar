<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Spatie\Holidays\Holidays;

class YearCalendar extends Component
{
    public int $year;

    /** @var array<int, array{name: string, weeks: array<int, array<int, array{day: int|null, holiday: string|null, isToday: bool}>>}> */
    public array $months = [];

    /** @var array{name: string, date: string, daysRemaining: int} */
    public array $nextHoliday = [];

    /** @var array<string, array{name: string, description: string}> */
    private array $holidayMap = [];

    /** @var array<string, string> */
    private const HOLIDAY_DESCRIPTIONS = [
        'Neujahr' => 'New Year\'s Day — marks the beginning of the new calendar year.',
        'Heilige Drei Könige' => 'Epiphany — celebrates the visit of the Three Wise Men to the infant Jesus.',
        'Ostermontag' => 'Easter Monday — the day after Easter Sunday, celebrating the resurrection of Christ.',
        'Staatsfeiertag' => 'Labour Day — honours the workers\' movement and social achievements.',
        'Christi Himmelfahrt' => 'Ascension Day — commemorates the ascension of Jesus into heaven, 40 days after Easter.',
        'Pfingstmontag' => 'Whit Monday — celebrates the descent of the Holy Spirit upon the Apostles.',
        'Fronleichnam' => 'Corpus Christi — a Catholic feast honouring the presence of Christ in the Eucharist.',
        'Mariä Himmelfahrt' => 'Assumption of Mary — celebrates the bodily assumption of the Virgin Mary into heaven.',
        'Nationalfeiertag' => 'Austrian National Day — commemorates the declaration of permanent neutrality in 1955.',
        'Allerheiligen' => 'All Saints\' Day — a day to honour all saints, known and unknown.',
        'Mariä Empfängnis' => 'Immaculate Conception — celebrates the conception of the Virgin Mary free from original sin.',
        'Christtag' => 'Christmas Day — celebrates the birth of Jesus Christ.',
        'Stefanitag' => 'St. Stephen\'s Day — honours the first Christian martyr, St. Stephen.',
    ];

    public function mount(): void
    {
        $this->year = Carbon::today()->year;
        $this->loadHolidays();
        $this->calculateNextHoliday();
        $this->buildCalendar();
    }

    private function loadHolidays(): void
    {
        $holidays = Holidays::for(country: 'at', year: $this->year);

        foreach ($holidays->get() as $holiday) {
            $date = Carbon::parse($holiday['date'])->format('Y-m-d');
            $this->holidayMap[$date] = [
                'name' => $holiday['name'],
                'description' => self::HOLIDAY_DESCRIPTIONS[$holiday['name']] ?? '',
            ];
        }
    }

    private function calculateNextHoliday(): void
    {
        $today = Carbon::today();

        $upcomingDate = collect($this->holidayMap)
            ->filter(fn (array $holiday, string $date) => Carbon::parse($date)->gte($today))
            ->sortKeys()
            ->keys()
            ->first();

        if ($upcomingDate) {
            $carbonDate = Carbon::parse($upcomingDate);

            $this->nextHoliday = [
                'name' => $this->holidayMap[$upcomingDate]['name'],
                'date' => $carbonDate->format('j M Y'),
                'daysRemaining' => (int) $today->diffInDays($carbonDate),
            ];

            return;
        }

        $nextYearHolidays = Holidays::for(country: 'at', year: $this->year + 1)->get();

        if (count($nextYearHolidays) > 0) {
            $first = $nextYearHolidays[0];
            $carbonDate = Carbon::parse($first['date']);

            $this->nextHoliday = [
                'name' => $first['name'],
                'date' => $carbonDate->format('j M Y'),
                'daysRemaining' => (int) $today->diffInDays($carbonDate),
            ];
        }
    }

    private function buildCalendar(): void
    {
        $today = Carbon::today();

        for ($month = 1; $month <= 12; $month++) {
            $firstDay = Carbon::create($this->year, $month, 1);
            $daysInMonth = $firstDay->daysInMonth;

            // Monday = 1, Sunday = 7 (ISO)
            $startDayOfWeek = $firstDay->dayOfWeekIso;

            $weeks = [];
            $emptyCell = ['day' => null, 'holiday' => null, 'holidayDescription' => null, 'isToday' => false];
            $currentWeek = array_fill(0, 7, $emptyCell);

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($this->year, $month, $day);
                $dateKey = $date->format('Y-m-d');
                $dayIndex = $date->dayOfWeekIso - 1; // 0 = Monday, 6 = Sunday
                $holidayData = $this->holidayMap[$dateKey] ?? null;

                $currentWeek[$dayIndex] = [
                    'day' => $day,
                    'holiday' => $holidayData['name'] ?? null,
                    'holidayDescription' => $holidayData['description'] ?? null,
                    'isToday' => $date->isSameDay($today),
                ];

                if ($dayIndex === 6 || $day === $daysInMonth) {
                    $weeks[] = $currentWeek;
                    $currentWeek = array_fill(0, 7, $emptyCell);
                }
            }

            $this->months[] = [
                'name' => $firstDay->translatedFormat('F'),
                'weeks' => $weeks,
            ];
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.year-calendar');
    }
}
