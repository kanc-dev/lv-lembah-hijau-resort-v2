<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartContainer = document.getElementById('roomOccupancyChart-{{ $branchId }}');

        if (chartContainer) {
            fetch("/branch-occupancy-chart")
                .then(response => response.json())
                .then(data => {
                    if (data.status !== 'success' || !data.data) {
                        console.error('Invalid API response');
                        return;
                    }

                    const chartData = data.data;

                    const options = {
                        series: [chartData.occupied, chartData.empty],
                        chart: {
                            type: 'pie',
                            height: 250
                        },
                        labels: ['Occupied', 'Empty'],
                        colors: ['#FF4560', '#00E396'],
                        legend: {
                            position: 'bottom'
                        },
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 200
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }]
                    };

                    const chart = new ApexCharts(chartContainer, options);
                    chart.render();
                })
                .catch(error => console.error('Error fetching chart data:', error));
        }
    });
</script>
