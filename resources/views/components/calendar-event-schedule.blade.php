<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="border-0 card-header">
                <h4 class="mb-0 card-title">Calender Occupancy</h4>
            </div><!-- end cardheader -->
            <div class="p-0 pb-2 card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="gap-2 d-flex flex-column">
                                    <button class="btn btn-secondary w-100 filter-branch active"
                                        data-branch-id="">Semua</button>
                                    @foreach ($data['branchs'] as $branch)
                                        <button class="btn btn-outline-secondary w-100 filter-branch"
                                            data-branch-id="{{ $branch->id }}">
                                            {{ $branch->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div id='calendar-event-schedule'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('body-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.filter-branch');
            let calendarEl = document.getElementById('calendar-event-schedule');

            let calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                events: function(fetchInfo, successCallback, failureCallback) {
                    const activeButton = document.querySelector('.filter-branch.active');
                    const branchId = activeButton ? activeButton.getAttribute('data-branch-id') : '';

                    fetch(
                            `/api/occupancy/calendar-data?branch_id=${branchId}`
                        )
                        .then(response => response.json())
                        .then(data => {
                            console.log(data.data);
                            successCallback(data.data)

                        })
                        .catch(error => failureCallback(error));
                },
            });
            calendar.render();

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    buttons.forEach(btn => {
                        btn.classList.remove('active', 'btn-secondary');
                        btn.classList.add(
                            'btn-outline-secondary'
                        );
                    });

                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('active', 'btn-secondary');

                    calendar.refetchEvents();
                });
            });
        });
    </script>
@endpush
