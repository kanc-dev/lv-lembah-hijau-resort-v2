<div class="row">
    <div class="col-12">
        <div class="card card-height-100">
            <div class="card-header align-items-center d-flex">
                <div class="flex-grow-1">
                    <h4 class="mb-1 fs-16">Grafik Okupansi Bulanan</h4>
                </div>
                <div class="mt-3 mt-lg-0">
                    <form action="javascript:void(0);">
                        <div class="mb-0 row g-3 align-items-center">
                            <div class="gap-2 col-sm-auto d-flex align-items-center">
                                <select name="branch_id" class="border form-select" id="branch_id">
                                    <option value="">All</option>
                                    @foreach ($data['branch_list'] as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group">
                                    <input type="month" class="border form-control" id="month-picker" value="">
                                    <div class="text-white input-group-text bg-primary border-primary">
                                        <i class="ri-calendar-2-line"></i>
                                    </div>
                                </div>
                                <button id="filter-btn" class="btn btn-primary ms-3" type="button">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <!-- Loading Indicator -->
                {{-- <div id="loading-indicator"
                    class="top-0 bg-white d-none position-absolute start-0 w-100 h-100 d-flex justify-content-center align-items-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div> --}}
                <!-- Grafik -->
                <div id="grafik-branch-occupancy"></div>
            </div>
        </div>
    </div>
</div>


@push('body-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const branchIdInput = document.getElementById('branch_id');
            const monthInput = document.getElementById('month-picker');
            const filterBtn = document.getElementById('filter-btn');
            const grafikElement = document.getElementById('grafik-branch-occupancy');

            function fetchDataGrafik(branchId = '', month = '') {
                // Show loading indicator
                grafikElement.innerHTML = ''; // Clear the previous chart
                // grafikElement.style.display = 'none';

                fetch(`/monitoring/graph-occupancy?branch_id=${branchId}&month=${month}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Data grafik:', data);
                        renderChartOccupancy(data);
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    })
                    .finally(() => {
                        grafikElement.style.display = 'block'; // Show the chart after loading
                    });
            }

            function renderChartOccupancy(data) {
                const options = {
                    series: data.series,
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            borderRadius: 5
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        categories: data.categories
                    },
                    yaxis: {
                        title: {
                            text: 'Jumlah Kamar Terisi'
                        },
                        min: 0,
                        max: Math.max(...data.series.flatMap(s => s.data)) + 2,
                        tickAmount: 6,
                        labels: {
                            formatter: function(value) {
                                return value.toFixed(0);
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " kamar";
                            }
                        }
                    }
                };

                const chart = new ApexCharts(document.querySelector("#grafik-branch-occupancy"), options);
                chart.render();
            }

            // Initial data fetch (if needed, can be triggered by default)
            fetchDataGrafik();

            // Event listener for the filter button
            filterBtn.addEventListener('click', function() {
                const currentBranchId = branchIdInput.value;
                const currentMonth = monthInput.value;
                fetchDataGrafik(currentBranchId,
                    currentMonth); // Fetch new data based on the selected filters
            });
        });
    </script>
@endpush
