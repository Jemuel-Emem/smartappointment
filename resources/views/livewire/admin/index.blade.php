<div class="bg-white p-4 rounded shadow space-y-6">

    <!-- Today's appointments -->
    <div class="p-3 bg-blue-100 rounded">
        <p class="text-lg font-bold">📅 Today's Appointments</p>
        <p class="text-2xl">{{ $todayAppointments }}</p>
    </div>

 <div class="p-3 rounded">
    <p class="text-lg font-bold">⚡ Live Service Statistics</p>
    <canvas id="serviceChart" height="150"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('livewire:navigated', function () {
    const ctx = document.getElementById('serviceChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json(array_keys($serviceStats->toArray())),
            datasets: [{
                label: 'Appointments',
                data: @json(array_values($serviceStats->toArray())),
              backgroundColor: [

    '#6EE7B7',
    '#FCD34D',
    '#FCA5A5',
    '#C4B5FD'
]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
});
</script>


</div>

