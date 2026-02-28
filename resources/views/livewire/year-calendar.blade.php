<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    {{-- Header --}}
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Austrian Holidays {{ $year }}
            </h1>
            @if (!empty($nextHoliday))
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
                    Next Holiday:
                    <span class="font-semibold text-red-700 dark:text-red-400">{{ $nextHoliday['name'] }}</span>
                    &mdash; {{ $nextHoliday['date'] }}
                    @if ($nextHoliday['daysRemaining'] === 0)
                        <span class="ml-1 inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">Today!</span>
                    @else
                        <span class="text-gray-500 dark:text-gray-400">(in {{ $nextHoliday['daysRemaining'] }} {{ $nextHoliday['daysRemaining'] === 1 ? 'day' : 'days' }})</span>
                    @endif
                </p>
            @endif
        </div>
    </header>

    {{-- Calendar Grid --}}
    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($months as $month)
                <div class="rounded-lg bg-white p-4 shadow-sm dark:bg-gray-800">
                    <h2 class="mb-3 text-center text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $month['name'] }}
                    </h2>

                    {{-- Weekday Headers --}}
                    <div class="mb-1 grid grid-cols-7 text-center text-xs font-medium text-gray-500 dark:text-gray-400">
                        <div>Mo</div>
                        <div>Tu</div>
                        <div>We</div>
                        <div>Th</div>
                        <div>Fr</div>
                        <div>Sa</div>
                        <div>Su</div>
                    </div>

                    {{-- Weeks --}}
                    @foreach ($month['weeks'] as $week)
                        <div class="grid grid-cols-7 text-center">
                            @foreach ($week as $cell)
                                @if ($cell['day'] === null)
                                    <div class="p-1"></div>
                                @else
                                    <div
                                        class="relative p-1"
                                        @if ($cell['holiday'])
                                            x-data="{ showTooltip: false }"
                                            x-on:mouseenter="showTooltip = true"
                                            x-on:mouseleave="showTooltip = false"
                                        @endif
                                    >
                                        <span @class([
                                            'inline-flex h-7 w-7 items-center justify-center rounded-full text-xs cursor-default',
                                            'bg-red-100 font-semibold text-red-800 dark:bg-red-900/50 dark:text-red-300' => $cell['holiday'] && !$cell['isToday'],
                                            'bg-blue-600 font-bold text-white' => $cell['isToday'],
                                            'text-gray-700 dark:text-gray-300' => !$cell['holiday'] && !$cell['isToday'],
                                        ])>
                                            {{ $cell['day'] }}
                                        </span>
                                        @if ($cell['holiday'])
                                            <div class="mt-0.5 truncate text-[9px] leading-tight text-red-700 dark:text-red-400">
                                                {{ $cell['holiday'] }}
                                            </div>
                                            <div
                                                x-show="showTooltip"
                                                x-transition:enter="transition ease-out duration-150"
                                                x-transition:enter-start="opacity-0 translate-y-1"
                                                x-transition:enter-end="opacity-100 translate-y-0"
                                                x-transition:leave="transition ease-in duration-100"
                                                x-transition:leave-start="opacity-100 translate-y-0"
                                                x-transition:leave-end="opacity-0 translate-y-1"
                                                x-cloak
                                                class="absolute z-50 w-52 rounded-lg bg-gray-900 px-3 py-2 text-left shadow-lg dark:bg-gray-700"
                                                style="bottom: 100%; left: 50%; transform: translateX(-50%); margin-bottom: 0.5rem;"
                                            >
                                                <div class="text-xs font-semibold text-white">{{ $cell['holiday'] }}</div>
                                                @if ($cell['holidayDescription'])
                                                    <div class="mt-1 text-[11px] leading-snug text-gray-300">{{ $cell['holidayDescription'] }}</div>
                                                @endif
                                                <div class="absolute left-1/2 top-full -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </main>
</div>
