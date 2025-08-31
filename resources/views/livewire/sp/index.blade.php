<div>
    <div class="p-6 space-y-6">


    <!-- Top Rated Staff -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="font-semibold mb-2">🏅 Top Rated Staff</h2>
        <ul>
            @foreach($topStaff as $staff)
                <li>{{ $staff->name }} - ⭐ {{ number_format($staff->avg_rating, 1) }}</li>
            @endforeach
        </ul>
    </div>

    <!-- High Demand Services -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="font-semibold mb-2">🔥 High Demand Services</h2>
        <ul>
            @foreach($highDemandServices as $service)
                <li>{{ $service->service_type }} - {{ $service->total }} appointments</li>
            @endforeach
        </ul>
    </div>

    <!-- Appointments Over Time (Line Chart) -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="font-semibold mb-2">📅 Appointments Over Time</h2>
        <canvas id="appointmentsChart"></canvas>
    </div>

    <!-- Department Satisfaction Leaderboard -->
    {{-- <div class="bg-white p-4 rounded shadow">
        <h2 class="font-semibold mb-2">🏆 Department Satisfaction</h2>
        <ul>
            @foreach($departmentSatisfaction as $dept)
                <li>{{ $dept->name }} - ⭐ {{ number_format($dept->avg_rating, 1) }}</li>
            @endforeach
        </ul>
    </div> --}}
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('appointmentsChart').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'line', // Change to 'bar' if you want a bar chart
            data: {
                labels: @json($appointmentStats->pluck('date')),
                datasets: [{
                    label: 'Appointments',
                    data: @json($appointmentStats->pluck('total')),
                    fill: true,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    tension: 0.3, // smooth curves
                    pointBackgroundColor: '#1d4ed8',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    x: {
                        title: { display: true, text: 'Date' }
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Total Appointments' }
                    }
                }
            }
        });
    });
</script>

</div>
