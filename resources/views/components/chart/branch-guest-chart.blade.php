<div id="branch-guest-chart"></div>


<script>
    // Ambil data dari API
    fetch('/branch-guests?filter=daily')
        .then(response => response.json())
        .then(data => {
            let options = {
                series: data.series,
                chart: {
                    type: 'bar',
                    height: 350
                },
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
                        text: 'Jumlah Tamu'
                    },
                    min: 0,
                    max: Math.max(...data.series.flatMap(s => s.data)) +
                        2,
                    tickAmount: 6,
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " tamu";
                        }
                    }
                }
            };

            let chart = new ApexCharts(document.querySelector("#branch-guest-chart"), options);
            chart.render();
        })
        .catch(error => console.error('Error:', error));
</script>
