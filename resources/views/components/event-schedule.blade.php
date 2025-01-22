<div id="event-schedule"></div>

<script>
    fetch("/api/occupancy/event-schedule?branch_id={{ $branchId ?? '' }}")
        .then(response => response.json())
        .then(data => {
            let options = {
                series: data.series,
                chart: {
                    height: 450,
                    type: 'rangeBar'
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        barHeight: '80%'
                    }
                },

                xaxis: {
                    type: 'datetime'
                },
                stroke: {
                    width: 1
                },
                fill: {
                    type: 'solid',
                    opacity: 0.6
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left'
                },
                tooltip: {
                    custom: function({
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        // Menampilkan nama event di tooltip
                        let eventName = w.config.series[seriesIndex].data[dataPointIndex].event_name;
                        let start = new Date(w.config.series[seriesIndex].data[dataPointIndex].y[0]);
                        let end = new Date(w.config.series[seriesIndex].data[dataPointIndex].y[1]);

                        return `
                        <div>
                            <strong>Event:</strong> ${eventName}<br>
                            <strong>Start:</strong> ${start.toLocaleString()}<br>
                            <strong>End:</strong> ${end.toLocaleString()}
                        </div>
                    `;
                    }
                }
            };

            let chart = new ApexCharts(document.querySelector("#event-schedule"), options);
            chart.render();
        })
        .catch(error => console.error('Error:', error));
</script>
