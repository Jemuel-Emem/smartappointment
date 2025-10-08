<div class=" mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4 text-blue-600">Create Announcement</h2>

    @if (session('success'))
        <div class="p-3 mb-4 text-green-700 bg-green-100 border border-green-200 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="createAnnouncement" class="space-y-4">
        <div>
            <label class="block text-gray-700 font-medium">Title</label>
            <input type="text" wire:model="title" class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring focus:border-blue-400">
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Message</label>
            <textarea wire:model="message" rows="4" class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring focus:border-blue-400"></textarea>
            @error('message') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Audience</label>
            <select wire:model="audience" class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring focus:border-blue-400">
                <option value="departments">Departments Only</option>
                <option value="users">Users Only</option>
                <option value="both">Both</option>
                <option value="specific_department">Specific Department</option>
            </select>
            @error('audience') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        @if($audience === 'specific_department')
            <div>
                <label class="block text-gray-700 font-medium">Select Department</label>
                <select wire:model="department_id" class="w-full border rounded-lg px-4 py-2 mt-1 focus:ring focus:border-blue-400">
                    <option value="">-- Choose Department --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                    @endforeach
                </select>
                @error('department_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
        @endif

        <div class="pt-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg">
                Post Announcement
            </button>
        </div>
    </form>

    <hr class="my-6">

    <h3 class="text-xl font-semibold mb-3 text-gray-700">Recent Announcements</h3>
    <div class="space-y-3">
        @foreach($announcements as $a)
            <div class="border rounded-lg p-3 bg-gray-50">
                <h4 class="font-semibold text-blue-700">{{ $a->title }}</h4>
                <p class="text-gray-700 text-sm mt-1">{{ $a->message }}</p>
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
</div>
