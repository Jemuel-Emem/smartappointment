<div class=" mx-auto bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-blue-600 mb-4">Department Announcements</h2>

    @if($announcements->isEmpty())
        <p class="text-gray-500">No announcements available for your department.</p>
    @else
        <div class="space-y-4">
            @foreach($announcements as $a)
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <h3 class="font-semibold text-lg text-blue-700">{{ $a->title }}</h3>
                    <p class="text-gray-700 mt-1">{{ $a->message }}</p>

                    <p class="text-xs text-gray-500 mt-2">
                        For:
                        <span class="font-medium">
                            @if($a->audience === 'departments') Departments Only
                            @elseif($a->audience === 'users') Users Only
                            @elseif($a->audience === 'both') Both
                            @elseif($a->audience === 'specific_department')
                                {{ $a->department->department_name ?? 'N/A' }}
                            @endif
                        </span>
                        • {{ $a->created_at->diffForHumans() }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif
</div>
