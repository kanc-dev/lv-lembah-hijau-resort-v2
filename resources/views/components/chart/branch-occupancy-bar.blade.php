<div id="branch-occupancy-bar"></div>

<script>
    // Ambil data dari API untuk occupancy
    fetch('/branch-occupancy-accumulated') // Gantilah URL API sesuai kebutuhan
        .then(response => response.json())
        .then(data => {
            const options = {
                series: data.series,
                chart: {
                    type: 'bar',
                    height: 350
                },
                // colors: ['#00c47f'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 5,
                        borderRadiusApplication: 'end'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: data.categories,
                },
                yaxis: {
                    title: {
                        text: 'Number of Occupied Rooms'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val
                        }
                    }
                }
            };

            // Membuat dan menampilkan chart
            let chart = new ApexCharts(document.querySelector("#branch-occupancy-bar"), options);
            chart.render();
        })
        .catch(error => console.error('Error:', error)); // Menangani error jika terjadi
</script>
