@props([
'current' => 0,
'total' => 0,
'itemName' => 'items',
'additionalInfo' => null
])

<div class="mb-4">
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Showing {{ $current }} of {{ $total }} {{ $itemName }}
        @if($additionalInfo)
        {{ $additionalInfo }}
        @endif
    </p>
</div>
