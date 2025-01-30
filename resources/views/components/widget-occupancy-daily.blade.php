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
                        <div class="col-sm-auto">
                            <div class="input-group">
                                <input type="date" class="border form-control" id="filterDate">
                                <div class="text-white input-group-text bg-primary border-primary">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                            </div>
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
            let dateInput = document.getElementById('filterDate');

            // Get yesterday's date
            let yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1); // Subtract one day
            let yesterdayFormatted = yesterday.toISOString().split('T')[0]; // Format as YYYY-MM-DD

            // Set the date input value to yesterday's date
            dateInput.value = yesterdayFormatted;

            // Fetch data for yesterday
            fetchDataDaily(yesterdayFormatted);

            // Event listener for date change
            dateInput.addEventListener('change', function() {
                fetchDataDaily(this.value);
            });
        });

        function fetchDataDaily(selectedDate) {
            console.log('Fetching data for date:', selectedDate);

            fetch('/monitoring/occupancy-daily?date=' + selectedDate)
                .then(response => response.json())
                .then(data => {
                    console.log('Data:', data);
                    updateDashboardDaily(data);
                })
                .catch(error => console.error('Error:', error));
        }

        function updateDashboardDaily(data) {
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
    </script>
@endpush
