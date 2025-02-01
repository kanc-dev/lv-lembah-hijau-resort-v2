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
                            <select name="branch_id" class="border form-select" id="branch_id">
                                <option value="">Daily</option>
                                <option value="">Monthly</option>
                                <option value="">Yearly</option>
                            </select>
                            <div class="input-group">
                                <input type="month" class="border form-control" id="month-picker" value="">
                                <div class="text-white input-group-text bg-primary border-primary">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                            </div>
                            <button id="filter-btn-occupancy-monthly" class="btn btn-primary ms-3"
                                type="button">Filter</button>
                        </div>
                    </div>
                </form>
                <form action="javascript:void(0);">
                    <div class="mb-0 row g-3 align-items-center">
                        <div class="col-sm-auto">
                            <div class="input-group">
                                <input type="month" class="border form-control" id="filterMonth">
                                <div class="text-white input-group-text bg-primary border-primary">
                                    <i class="ri-calendar-2-line"></i>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </form>
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
            let monthInput = document.getElementById('filterMonth');

            let today = new Date();
            let currentMonth = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0');

            monthInput.value = currentMonth;

            fetchDataMonthly(currentMonth);
        });

        document.getElementById('filterMonth').addEventListener('change', function() {
            fetchDataMonthly(this.value);
        });

        function fetchDataMonthly(selectedMonth) {
            console.log('Fetching data for month:', selectedMonth);

            fetch('/monitoring/occupancy-monthly?month=' + selectedMonth)
                .then(response => response.json())
                .then(data => {
                    console.log('Data:', data);
                    updateDashboardMonthly(data);
                })
                .catch(error => console.error('Error:', error));
        }

        function updateDashboardMonthly(data) {
            data.occupancy_of_branch.forEach(branch => {
                let counterElement = document.querySelector(`.counter-value[data-branch-id="${branch.id}"]`);

                if (counterElement) {
                    counterElement.textContent = branch.occupancy.percentage_occupied;
                }
            });
        }
    </script>
@endpush
