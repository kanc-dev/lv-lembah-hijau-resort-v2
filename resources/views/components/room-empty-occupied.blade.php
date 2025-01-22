<div id="room-occupancy-chart"></div>

<script>
    fetch("/api/occupancy/room-empty-occupied?branch_id={{ $branchId ? $branchId : '' }}")
        .then(response => response.json())
        .then(data => {
            console.log('API Response:', data);

            if (!data || !data.data) {
                throw new Error('Invalid API response format.');
            }

            const occupancyData = data.data;

            const chartOptions = {
                chart: {
                    type: 'pie',
                    height: 350
                },
                labels: ['Kamar Terisi', 'Kamar Kosong'],
                series: [occupancyData.occupied, occupancyData.empty],
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val.toFixed(2) + '%';
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    floating: false,
                    fontSize: '14px',
                    itemMargin: {
                        horizontal: 5,
                        vertical: 10
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            let chart = new ApexCharts(document.querySelector("#room-occupancy-chart"), chartOptions);
            chart.render();
        })
        .catch(error => console.error('Error fetching data:', error));
</script>
