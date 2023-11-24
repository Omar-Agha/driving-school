<!DOCTYPE html>
<html lang='en'>

<head>

    <meta charset='utf-8' />

    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>

</head>

<body>
    <div id='calendar'></div>



    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>




    <script>
        $(document).ready(() => {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const OnSelectCallback = function(start, end, allDay) {
                var title = prompt('Event Title:');

                if (title) {
                    var start = FullCalendar.formatDate(start, 'Y-MM-DD HH:mm:ss');

                    var end = FullCalendar.formatDate(end, 'Y-MM-DD HH:mm:ss');

                    $.ajax({
                        url: "/full-calender/action",
                        type: "POST",
                        data: {
                            name: title,
                            start: start,
                            end: end,
                            type: 'add'
                        },
                        success: function(data) {
                            calendar.refetchEvents()
                            alert("Event Created Successfully");
                        }
                    })
                }
            }

            const HeaderToolbar = {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            };

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                editable: true,
                selectable: true,
                selectHelper: true,
                events: '/full-calender',
                headerToolbar: HeaderToolbar,

                select: OnSelectCallback

            });
            calendar.render();
        });
    </script>
</body>

</html>
