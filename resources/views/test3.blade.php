<!DOCTYPE html>
<html>

<head>
    <title>How to Use Fullcalendar in Laravel 8</title>

    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script> -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
</head>

<body>

    <div class="container">
        <br />
        <h1 class="text-center text-primary"><u>How to Use Fullcalendar in Laravel 8</u></h1>
        <br />

        <div id="calendar"></div>

    </div>



    <!-- methods -->
    <script></script>

    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    "Accept": "application/json"
                }
            });


            const OnSelectCallback = function(selectionInfo) {

                const ACTION_API = "/full-calender/action"
                var title = prompt('Event Title:');
                if (title) {
                    var start = moment(selectionInfo.start).format('Y-MM-DD HH:mm:ss');

                    var end = moment(selectionInfo.end).format('Y-MM-DD HH:mm:ss');

                    $.ajax({
                        url: ACTION_API,
                        type: "POST",
                        data: {
                            name: title,
                            start: start,
                            end: end,
                            type: 'add'
                        },
                        success: function(data) {
                            calendar.refetchEvents()
                            console.log(data);
                            alert("Event Created Successfully");
                        }
                    })
                }
            }

            const OnEventResizeCallback = function(event, delta) {
                var start = FullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
                var end = FullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
                var title = event.title;
                var id = event.id;
                $.ajax({
                    url: "/full-calender/action",
                    type: "POST",
                    data: {
                        name: title,
                        start: start,
                        end: end,
                        id: id,
                        type: 'update'
                    },
                    success: function(response) {
                        calendar.refetchEvents()

                        alert("Event Updated Successfully");
                    }
                })
            }

            const OnEventDropCallback = function(info, delta) {

                const event = info.event
                console.log(event);

                var start = moment(event.start).format('Y-MM-DD HH:mm:ss');
                var end = moment(event.end).format('Y-MM-DD HH:mm:ss');

                var title = event.title;
                var id = event.id;
                $.ajax({
                    url: "/full-calender/action",
                    type: "POST",
                    data: {
                        name: title,
                        start: start,
                        end: end,
                        id: id,

                        type: 'update'
                    },
                    success: function(response) {
                        calendar.refetchEvents()

                        alert("Event Updated Successfully");
                    }
                })
            }

            const OnEventClickCallback = function(args) {

                // if (confirm("Are you sure you want to remove it?")) {
                //     const event = args.event;
                //     var id = event.id;

                //     console.log(event);
                //     $.ajax({
                //         url: "/full-calender/action",
                //         type: "POST",
                //         data: {
                //             id: id,
                //             type: "delete"
                //         },
                //         success: function(response) {
                //             calendar.refetchEvents()

                //             alert("Event Deleted Successfully");
                //         }
                //     })
                // }

                var event = args.event;
                console.log(event);
                // Handle double-click logic
                var now = new Date().getTime();
                if (calendar.lastClick && (now - calendar.lastClick) < 300) {
                    // Handle double-click action
                    console.log('double click');
                    console.log('Double-clicked on: ' + event.start);
                } else {
                    console.log('single click');
                    // Store the timestamp of the last click for comparison
                    calendar.lastClick = now;

                }

            }

            const OnEventsCallback = function(fetchInfo, successCallback, failureCallback) {
                console.log(fetchInfo);
                const api = '/api-full-calender';
                // Make a fetch request to your API
                $.ajax({
                    type: "GET",
                    url: api,
                    data: fetchInfo,
                    success: function(data) {
                        console.log(data);
                        // Transform your API data into FullCalendar events
                        var events = data.map(event => ({
                            id: event.id,
                            title: event.title,
                            start: event.start,
                            end: event.end

                        }));

                        // Call the successCallback with the transformed events
                        successCallback(events);
                    },
                    error: function(error) {
                        // Call the failureCallback in case of an error
                        console.log('jjjj');
                        console.log(error.message);
                        failureCallback(error);
                    }
                });

            };

            const HeaderToolbar = {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek,timeGridDay'
            }

            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                allDaySlot: false,
                editable: true,
                selectable: true,
                selectMirror: true,
                headerToolbar: HeaderToolbar,
                events: OnEventsCallback,
                select: OnSelectCallback,
                eventResize: OnEventResizeCallback,
                eventDrop: OnEventDropCallback,
                eventClick: OnEventClickCallback
            });
            calendar.render();

        });
    </script>


</body>

</html>
