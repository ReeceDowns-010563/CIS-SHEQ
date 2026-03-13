<div class="flex items-center space-x-2">
    @if(!$incident->archived)
        <a href="{{ route('incidents.edit', $incident) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200">
            Edit
        </a>
        <button onclick="archiveIncident({{ $incident->id }})"
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200">
            Archive
        </button>
    @else
        <button onclick="unarchiveIncident({{ $incident->id }})"
                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition-colors duration-200">
            Restore
        </button>
    @endif
</div>
