<div class="row">
    <div class="col-12">
        <!-- card body -->
        <div class="mb-2 d-flex align-items-lg-center flex-lg-row flex-column">
            <div class="flex-grow-1">
                <h4 class="mb-1 fs-16">Data Okupansi Harian</h4>
            </div>
            <div class="mt-3 mt-lg-0">
                <form action="javascript:void(0);">
                    <div class="mb-0 row g-3 align-items-center">
                        <div class="gap-2 col-sm-auto d-flex align-items-center">
                            <select name="filter_type" class="border form-select" id="filter-type-chart">
                                <option value="daily" selected>Daily</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                            <div class="input-group" style="min-width: 200px">
                                <input type="date" class="border form-control" id="date-picker-chart">
                                <input type="month" class="border form-control" id="month-picker-chart"
                                    style="display: none;">
                                <input type="number" class="border form-control" id="year-picker-chart"
                                    style="display: none;" min="2000" max="2099" step="1" value="2023">
                                <div class="text-white input-group-text bg-primary border-primary">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                            </div>
                            <button id="filter-btn-occupancy-chart" class="btn btn-primary ms-3"
                                type="button">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @foreach ($data['branch_list'] as $branch)
                <div class="col-md-4 col-lg">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <div class="flex-grow-1">
                                <h4 class="mb-0 card-title flex-grow-1">{{ $branch['name'] }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="room-daily-occupancy-{{ $branch['id'] }}"></div>

                            <div class="mt-3 table-responsive table-card">
                                <table
                                    class="table mb-1 align-middle table-borderless table-sm table-centered table-nowrap">
                                    <thead
                                        class="border border-dashed text-muted border-start-0 border-end-0 bg-soft-light">
                                        <tr>
                                            <th></th>
                                            <th style="width: 30%;">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">
                                        <tr>
                                            <th>Kapasitas</th>
                                            <td id="total-{{ $branch['id'] }}"></td>
                                        </tr>
                                        <tr>
                                            <th>Terisi</th>
                                            <td id="occupied-{{ $branch['id'] }}"></td>
                                        </tr>
                                        <tr>
                                            <th>Tersedia</th>
                                            <td id="empty-{{ $branch['id'] }}"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('body-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterType = document.getElementById('filter-type-chart');
            const datePicker = document.getElementById('date-picker-chart');
            const monthPicker = document.getElementById('month-picker-chart');
            const yearPicker = document.getElementById('year-picker-chart');
            const filterBtn = document.getElementById('filter-btn-occupancy-chart');

            let today = new Date();
            let currentDate = today.toISOString().split('T')[0];
            let currentMonth = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0');
            let currentYear = today.getFullYear();

            // Set default values
            monthPicker.value = currentMonth;
            datePicker.value = currentDate;
            yearPicker.value = currentYear;

            // Show month picker by default
            datePicker.style.display = 'block';

            filterType.addEventListener('change', function() {
                const selectedFilter = this.value;

                datePicker.style.display = 'none';
                monthPicker.style.display = 'none';
                yearPicker.style.display = 'none';

                if (selectedFilter === 'daily') {
                    datePicker.style.display = 'block';
                } else if (selectedFilter === 'monthly') {
                    monthPicker.style.display = 'block';
                } else if (selectedFilter === 'yearly') {
                    yearPicker.style.display = 'block';
                }
            });

            filterBtn.addEventListener('click', function() {
                const selectedFilter = filterType.value;
                let selectedValue;

                if (selectedFilter === 'daily') {
                    selectedValue = datePicker.value;
                } else if (selectedFilter === 'monthly') {
                    selectedValue = monthPicker.value;
                } else if (selectedFilter === 'yearly') {
                    selectedValue = yearPicker.value;
                }

                if (!selectedValue) {
                    alert('Please select a date, month, or year.');
                    return;
                }

                fetchData(selectedFilter, selectedValue);
            });

            function fetchData(filterType, selectedValue) {
                let url = '/monitoring/occupancy-';

                if (filterType === 'daily') {
                    url += `daily?date=${selectedValue}`;
                } else if (filterType === 'monthly') {
                    url += `monthly?month=${selectedValue}`;
                } else if (filterType === 'yearly') {
                    url += `yearly?year=${selectedValue}`;
                }

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Data:', data);
                        updateDashboard(data);
                    })
                    .catch(error => console.error('Error:', error));
            }

            function updateDashboard(data) {
                data.occupancy_of_branch.forEach(branch => {
                    let branchId = branch.id;

                    document.getElementById(`total-${branchId}`).textContent = branch.occupancy.total;
                    document.getElementById(`occupied-${branchId}`).textContent = branch.occupancy.occupied;
                    document.getElementById(`empty-${branchId}`).textContent = branch.occupancy.empty;

                    renderChart(branchId, branch.occupancy);
                });
            }

            function renderChart(branchId, occupancyData) {
                let chartElement = document.querySelector(`#room-daily-occupancy-${branchId}`);

                if (!chartElement) {
                    console.error('Chart element not found:', branchId);
                    return;
                }

                let chartOptions = {
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

                // Hapus chart lama jika sudah ada
                if (chartElement.chartInstance) {
                    chartElement.chartInstance.destroy();
                }

                // Render chart baru
                let chart = new ApexCharts(chartElement, chartOptions);
                chart.render();

                // Simpan instance chart agar bisa dihapus nanti
                chartElement.chartInstance = chart;
            }
        });
    </script>
@endpush
