<div id="event-timeline-chart"></div>

<script>
    fetch('/event-timeline')
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

            let chart = new ApexCharts(document.querySelector("#event-timeline-chart"), options);
            chart.render();
        })
        .catch(error => console.error('Error:', error));
</script>


<script>
    {
        "series": [{
                "name": "Bandung",
                "data": [{
                        "x": "Bandung",
                        "y": [
                            1737331200000,
                            1737763200000
                        ],
                        "goals": [{
                            "name": "Break",
                            "value": 1737763200000,
                            "strokeColor": "#CD2F2A"
                        }]
                    },
                    {
                        "x": "Makasar",
                        "y": [
                            1738195200000,
                            1738281600000
                        ],
                        "goals": [{
                            "name": "Break",
                            "value": 1738281600000,
                            "strokeColor": "#CD2F2A"
                        }]
                    },
                ]
            },
            {
                "name": "Yogyakarta",
                "data": [{
                    "x": "Makasar",
                    "y": [
                        1737331200000,
                        1737676800000
                    ],
                    "goals": [{
                        "name": "Break",
                        "value": 1737676800000,
                        "strokeColor": "#CD2F2A"
                    }]
                }]
            },
            {
                "name": "Surabaya",
                "data": []
            },
            {
                "name": "Padang",
                "data": []
            },
            {
                "name": "Makassar",
                "data": []
            }
        ]
    }
</script>
