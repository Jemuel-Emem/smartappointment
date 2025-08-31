<div class="bg-white p-4 rounded shadow">
    <h2 class="text-lg font-semibold mb-3">⭐ Ratings & Feedback Summary</h2>

    <!-- Summary -->
    <div class="mb-4">
        <p><strong>Average Rating:</strong> {{ number_format($summary->avg_rating, 1) ?? 'N/A' }} / 5</p>
        <p><strong>Total Feedbacks:</strong> {{ $summary->total_feedbacks }}</p>
    </div>

    <!-- Comments -->
    <h3 class="font-semibold mb-2">💬 Latest Comments</h3>
    <ul class="space-y-3">
        @forelse($comments as $comment)
            <li class="border p-2 rounded">
                <p class="text-sm text-gray-600">
                    <strong>{{ $comment->user->name ?? 'Anonymous' }}</strong>
                    on <em>{{ $comment->staff->name ?? 'Unknown Staff' }}</em>
                </p>
                <p class="text-yellow-500">Rating: ⭐ {{ $comment->rating }}</p>
                <p class="text-gray-800">{{ $comment->comment }}</p>
            </li>
        @empty
            <p class="text-gray-500">No feedback yet.</p>
        @endforelse
    </ul>
</div>
