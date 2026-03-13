@props([
    'currentStatus',
    'itemId',
    'disabled' => false,
    'statuses' => ['pending', 'investigating', 'completed', 'closed'],
    'updateRoute' => null
])

<div class="clean-select status-{{ $currentStatus }}"
     data-item-id="{{ $itemId }}"
     data-current-status="{{ $currentStatus }}"
     @if($disabled) data-disabled="true" @endif>

    <div class="clean-select-button">
        <span class="status-text">{{ ucfirst($currentStatus) }}</span>
        @if(!$disabled)
            <svg class="clean-select-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        @endif
    </div>

    @if(!$disabled)
        <div class="clean-select-dropdown">
            @foreach($statuses as $status)
                <div class="clean-select-option" data-value="{{ $status }}">
                    {{ ucfirst($status) }}
                </div>
            @endforeach
        </div>
    @endif
</div>

@if(!$disabled && $updateRoute)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize status select for this component
                const select = document.querySelector('[data-item-id="{{ $itemId }}"]');
                if (select && !select.dataset.initialized) {
                    initializeStatusSelect(select, '{{ $updateRoute }}');
                    select.dataset.initialized = 'true';
                }
            });
        </script>
    @endpush
@endif
