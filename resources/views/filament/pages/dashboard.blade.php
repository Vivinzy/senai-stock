@php
$cards = $cards ?? [];
$rowHeading = $rowHeading ?? null;
$rows = $rows ?? [];
@endphp

<div class="space-y-6">
    <div class="space-y-2">
        <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">{{ $heading ?? $this->getHeading() }}</h1>
        @if($subheading)
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $subheading }}</p>
        @endif
    </div>

    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        @foreach($cards as $card)
            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm transition-colors duration-150 hover:border-blue-300 dark:border-gray-700 dark:bg-gray-900">
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $card['label'] }}</div>
                <div class="mt-4 text-3xl font-semibold text-gray-900 dark:text-white">{{ $card['value'] }}</div>
                @if(!empty($card['description']))
                    <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ $card['description'] }}</p>
                @endif
            </div>
        @endforeach
    </div>

    @if($rowHeading && count($rows))
        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $rowHeading }}</h2>
            <div class="mt-4 space-y-3">
                @foreach($rows as $row)
                    <div class="flex items-center justify-between rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        <span>{{ $row['label'] }}</span>
                        <span class="font-semibold">{{ $row['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
