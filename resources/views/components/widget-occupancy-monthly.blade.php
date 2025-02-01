<div class=" row">
    <div class="col-12">
        <div class="mb-2 d-flex align-items-lg-center flex-lg-row flex-column">
            <div class="flex-grow-1">
                <h4 class="mb-1 fs-16">Data Okupansi Bulanan</h4>
            </div>
            <div class="mt-3 mt-lg-0">
                <form action="javascript:void(0);">
                    <div class="mb-0 row g-3 align-items-center">
                        <div class="gap-2 col-sm-auto d-flex align-items-center">
                            <select name="filter_type" class="border form-select" id="filter-type">
                                <option value="daily">Daily</option>
                                <option value="monthly" selected>Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                            <div class="input-group" style="min-width: 200px">
                                <input type="date" class="border form-control" id="date-picker"
                                    style="display: none;">
                                <input type="month" class="border form-control" id="month-picker">
                                <input type="number" class="border form-control" id="year-picker"
                                    style="display: none;" min="2000" max="2099" step="1" value="2023">
                                <div class="text-white input-group-text bg-primary border-primary">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                            </div>
                            <button id="filter-btn-occupancy" class="btn btn-primary ms-3"
                                type="button">Filter</button>
                        </div>
                    </div>
                </form>
                {{-- <form action="javascript:void(0);">
                    <div class="mb-0 row g-3 align-items-center">
                        <div class="col-sm-auto">
                            <div class="input-group">
                                <input type="month" class="border form-control" id="filterMonth">
                                <div class="text-white input-group-text bg-primary border-primary">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </form> --}}
            </div>
        </div>
        <div class="row">
            @foreach ($data['branch_list'] as $branch)
                <div class="col">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="overflow-hidden flex-grow-1">
                                    <p class="mb-0 text-uppercase fw-medium text-muted text-truncate">
                                        {{ $branch['name'] }}</p>
                                </div>
                            </div>
                            <div class="mt-4 d-flex align-items-end justify-content-between">
                                <div>
                                    <h4 class="mb-4 fs-22 fw-semibold ff-secondary"><span class="counter-value"
                                            data-branch-id="{{ $branch['id'] }}" data-target="0">0</span>%
                                    </h4>
                                </div>
                                <div class="flex-shrink-0 avatar-sm">
                                    <span class="rounded avatar-title bg-soft-primary fs-3">
                                        <i class='bx bx-doughnut-chart text-primary'></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            @endforeach
        </div>
    </div>
</div>

@push('body-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterType = document.getElementById('filter-type');
            const datePicker = document.getElementById('date-picker');
            const monthPicker = document.getElementById('month-picker');
            const yearPicker = document.getElementById('year-picker');
            const filterBtn = document.getElementById('filter-btn-occupancy');

            let today = new Date();
            let currentDate = today.toISOString().split('T')[0];
            let currentMonth = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0');
            let currentYear = today.getFullYear();

            // Set default values
            monthPicker.value = currentMonth;
            datePicker.value = currentDate;
            yearPicker.value = currentYear;

            // Show month picker by default
            monthPicker.style.display = 'block';

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
                    let counterElement = document.querySelector(
                        `.counter-value[data-branch-id="${branch.id}"]`);

                    if (counterElement) {
                        counterElement.textContent = branch.occupancy.percentage_occupied;
                    }
                });
            }
        });
    </script>
@endpush
