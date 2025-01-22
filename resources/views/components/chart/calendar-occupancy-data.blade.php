<div id='calendar'></div>

@push('body-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.filter-branch');
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
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
                    // Hapus kelas "active" dari semua tombol
                    buttons.forEach(btn => {
                        btn.classList.remove('active', 'btn-secondary');
                        btn.classList.add(
                            'btn-outline-secondary'
                        ); // Tambahkan outline ke tombol lain
                    });

                    // Tambahkan kelas "active" dan "btn-secondary" pada tombol yang diklik
                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('active', 'btn-secondary');

                    // Refresh kalender dengan data baru
                    calendar.refetchEvents();
                });
            });
        });
    </script>
@endpush
