<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="border-0 card-header">
                <h4 class="mb-0 card-title">Event Schedule</h4>
            </div><!-- end cardheader -->
            <div class="p-0 pb-2 card-body">
                <div>

                    <div id="widget-event-schedule"></div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
    <div class="col-xl-4">
        <div class="card card-height-100">
            <div class="card-header align-items-center d-flex">
                <h4 class="mb-0 card-title flex-grow-1">Event Booking</h4>
            </div>
            <div class="card-body">
                @foreach ($data['event_booking'] as $event)
                    <div class="mt-3 mini-stats-wid d-flex align-items-center">
                        <div class="flex-shrink-0 avatar-sm">
                            <span class="mini-stat-icon avatar-title rounded-circle text-primary bg-soft-primary fs-4">
                                {{ date('d', strtotime($event['tanggal_rencana_checkin'])) }}
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $event->event['nama_kelas'] }}</h6>
                            <p class="mb-0 text-muted">{{ $event->destinationBranch['name'] }}
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <p class="mb-0 text-muted">
                                {{ date('M Y', strtotime($event['tanggal_rencana_checkin'])) }}
                            </p>
                        </div>
                    </div>
                @endforeach

                <div class="mt-3 text-center">
                    <a href="javascript:void(0);" class="text-muted text-decoration-underline">View
                        all Events</a>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div> <!-- end col-->
</div>

@push('body-script')
    <script>
        fetch("/api/occupancy/event-schedule")
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

                let chart = new ApexCharts(document.querySelector("#widget-event-schedule"), options);
                chart.render();
            })
            .catch(error => console.error('Error:', error));
    </script>
@endpush
