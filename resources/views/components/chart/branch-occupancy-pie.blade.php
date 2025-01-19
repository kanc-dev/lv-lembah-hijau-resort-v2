<div id="branch-occupancy-pie"></div>

<script>
    fetch('/branch-occupancy-pie')
        .then(response => response.json())
        .then(data => {
            const series = data.map(item => item.y);
            const labels = data.map(item => item.name);
            let options = {
                series: series,
                chart: {
                    type: 'donut',
                    height: 350
                },
                labels: labels,
                // title: {
                //     text: 'Persentase Occupancy',
                //     align: 'left'
                // },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + "%";
                        }
                    }
                },
                legend: {
                    position: 'bottom', // Memindahkan legend ke bawah chart
                    horizontalAlign: 'center', // Menempatkan legend di tengah
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

            let chart = new ApexCharts(document.querySelector("#branch-occupancy-pie"), options);
            chart.render();
        })
        .catch(error => console.error('Error:', error));
</script>
