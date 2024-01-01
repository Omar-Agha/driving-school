<x-filament-panels::page x-data="app">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>


    <style>
        .my-time-picker select {

            appearance: none;
            background: none;
            background-image: none;
            border: none;
            box-shadow: none;
            color: inherit;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            font-weight: 400;

            line-height: 16px;
            margin: 0;
            outline: none;
            position: relative;
        }
    </style>


    <style>
        .datepicker.datepicker-dropdown.dropdown {
            z-index: 1000;
        }
    </style>


    <div class="bg-blue-700"></div>


    <div id="calendar" class="h-[80vh]" wire:ignore></div>


    <x-filament::modal id="event-create-modal" icon="heroicon-o-plus" icon-color="info" slide-over sticky-header
        sticky-footer width="md" :close-by-clicking-away="false" alignment="center" :close-button="false" x-data="{ event: {}, open: true }"
        x-init="window.addEventListener('event-changed', args => {
        
            e = args.detail.event;
            let startDate = new Date(e.start);
            let endDate = new Date(e.end);
            let props = e.extendedProps;
            let diff = getDiffBetweenTwoDates(startDate, endDate);
            let duration = zeroPad(diff.hours) + ':' + zeroPad(diff.mins);
        
            console.log(props)
        
        
            event['time_hour'] = startDate.getHours() % 12 || 12;
            event['time_min'] = startDate.getMinutes();
            event['id'] = e.id ?? null;
        
            event['duration'] = duration;
            event['start_date'] = moment(startDate).format('Y-MM-DD');
        
            event['lesson'] = props?.school_lesson_id ?? {{ $lessons[0]->id ?? 0 }};
            event['instructor'] = props?.instructor_id ?? {{ $instructors[0]->id ?? 0 }};
            event['student'] = props?.student_id ?? {{ $students[0]->id ?? 0 }};
            event['vehicle'] = props?.vehicle_id ?? {{ $vehicles[0]->id ?? 0 }};
        
        })">

        <x-slot name="heading">
            <div x-data="{ newEvent: ture }">
                <template x-if="event['id'] == null">
                    <div>{{ __('dashboard.new_event') }} </div>
                </template>

                <template x-if="event['id'] != null">
                    <div>{{ __('dashboard.edit_event') }} </div>
                </template>
            </div>
        </x-slot>





        <x-slot name="description">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quae, veniam?
        </x-slot>

        <section>
            <form>
                <x-filament::input.wrapper>
                    <x-filament::input.select x-model=event.lesson name="lesson">
                        @foreach ($lessons as $lesson)
                            <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>

                <div class="grid grid-cols-12 ">

                    <div class="my-time-picker col-span-6">
                        <label for="hour-picker"
                            class="text-sm font-medium leading-6 text-gray-950 dark:text-white">{{ __('dashboard.time') }}</label>
                        <div class="select-group flex justify-start items-center">
                            <select name="hour" id="hour-picker" class="p-0 ps-[5px]" x-model="event.time_hour"
                                style="padding: 0">
                                @for ($i = 0; $i <= 23; $i++)
                                    <option value="{{ $i }}">{{ sprintf('%02d', $i) }}</option>
                                @endfor

                            </select>
                            <span>:</span>
                            <select name="min" id="minute-picker" class="p-0 ps-[5px]" x-model=event.time_min>
                                @for ($i = 0; $i <= 55; $i += 5)
                                    <option value="{{ $i }}">{{ sprintf('%02d', $i) }}</option>
                                @endfor

                            </select>
                        </div>

                    </div>

                    <div class="col-span-6">
                        <div class="my-time-picker">
                            <label for="duration"
                                class="text-sm font-medium leading-6 text-gray-950 dark:text-white block">{{ __('dashboard.duration') }}</label>
                            <select name="duration" id="duration-picker" class="p-0 ps-[5px]" x-model="event.duration">
                                @foreach (range(900, 28800, 300) as $increment)
                                    <option value="{{ gmdate('H:i', $increment) }}">{{ gmdate('H:i', $increment) }}
                                    </option>
                                @endforeach


                            </select>
                        </div>

                    </div>

                    <div class="col-span-12">
                        <div class="relative max-w-sm">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input datepicker datepicker-buttons type="text" x-model="event.start_date" disabled
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Select date">
                        </div>
                    </div>


                    <div class="col-span-12 mt-3">
                        <label for="instructor">instructor</label>
                        <x-filament::input.wrapper>
                            <x-filament::input.select f="eventInstructor" name="instructor">
                                @foreach ($instructors as $instructor)
                                    <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                @endforeach
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>

                    <div class="col-span-12 mt-3">
                        <label for="instructor">student</label>

                        <x-filament::input.wrapper>

                            <x-filament::input.select model="event.student" name="student">
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->full_name }}</option>
                                @endforeach
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>


                    <div class="col-span-12 mt-3">
                        <label for="instructor">vehicle</label>

                        <x-filament::input.wrapper>

                            <x-filament::input.select model="event.vehicle" name="vehicle">
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->number_plate }}</option>
                                @endforeach
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>



                </div>

                <button id="submit-event-form" type="submit" class="invisible">Save</button>


            </form>

        </section>


        <x-slot name="footerActions">

            <x-filament::button color="warning" @click="closeCreateEventModal">
                {{ __('dashboard.cancel') }}
            </x-filament::button>


            <x-filament::button color="success" @click="submitEventForm">
                {{ __('dashboard.submit') }}
            </x-filament::button>

            <template x-if="event['id'] != null">
                <x-filament::button color="danger" @click="deleteEvent" class="ml-auto">
                    {{ __('dashboard.delete') }}
                </x-filament::button>
            </template>

        </x-slot>


    </x-filament::modal>



    {{-- init alpine --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                openCreateEventModal() {
                    this.$dispatch('open-modal', {
                        id: 'event-create-modal'
                    })
                },
                closeCreateEventModal() {


                    if (this.event.id == null)
                        removeMirrorEvent()

                    this.$dispatch('close-modal', {
                        id: 'event-create-modal'
                    })
                },
                submitEventForm() {

                    console.log(this.event);

                    this.$wire.save(this.event).then(() => {
                        this.closeCreateEventModal();
                        calendar.refetchEvents();

                    });
                },
                deleteEvent() {
                    this.$wire.deleteEvent(this.event.id).then(() => {
                        this.closeCreateEventModal();
                        calendar.refetchEvents();
                    })
                }
            }))


            Alpine.store('darkMode', {
                on: false,

                toggle() {
                    this.$dispatch('open-modal', {
                        id: 'event-create-modal'
                    })
                }
            })

        })
    </script>
    {{-- init alpine --}}


    <!-- setup -->
    <script>
        const zeroPad = (num, places = 2) => String(num).padStart(places, '0')

        function getDiffBetweenTwoDates(date1, date2) {
            var diffMs = (date2 - date1); // milliseconds between now & Christmas
            var diffDays = Math.floor(diffMs / 86400000); // days
            var diffHrs = Math.floor((diffMs % 86400000) / 3600000); // hours
            var diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000); // minutes
            return {
                hours: diffHrs,
                mins: diffMins
            }
            console.log(diffDays + " days, " + diffHrs + " hours, " + diffMins + " minutes until Christmas =)");
        }

        function openCreateEventModal() {
            window.dispatchEvent(
                new CustomEvent('open-modal', {
                    detail: {
                        id: 'event-create-modal'
                    }
                })
            )
        }

        function closeCreateEventModal() {

            window.dispatchEvent(
                new CustomEvent('close-modal', {
                    detail: {
                        id: 'event-create-modal'
                    }
                })
            )
        }

        $(document).ready(() => {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    "Accept": "application/json"
                }
            });
        })
    </script>
    <!-- setup -->

    {{-- core --}}
    <script>
        function removeMirrorEvent() {
            calendar.getEvents().pop().remove()
        }

        function onEventFromChanged(e) {

            window.dispatchEvent(
                new CustomEvent('event-changed', {
                    detail: {
                        event: e
                    }
                })
            )
        }

        var calendar = null;
        var newEvent = null;
        $(document).ready(function() {

            const OnSelectCallback = function(selectionInfo) {

                var e = {
                    start: selectionInfo.startStr,
                    end: selectionInfo.endStr
                };
                calendar.addEvent(e)
                newEvent = e;
                openCreateEventModal();
                onEventFromChanged(newEvent);


            }

            const OnEventResizeCallback = function(infoData) {
                var event = infoData.event
                console.log(event.start);
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


                    }
                })
            }

            const OnEventClickCallback = function(args) {
                let event = args.event;

                openCreateEventModal();
                onEventFromChanged(event);
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

                        // Call the successCallback with the transformed events
                        successCallback(data);
                    },
                    error: function(error) {
                        // Call the failureCallback in case of an error

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

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',

                allDaySlot: false,
                editable: true,
                selectable: true,
                selectMirror: false,
                headerToolbar: HeaderToolbar,
                events: OnEventsCallback,
                select: OnSelectCallback,
                eventResize: OnEventResizeCallback,
                eventDrop: OnEventDropCallback,
                eventClick: OnEventClickCallback,
                eventContent: function(arg) {
                    let italicEl = document.createElement('i')
                    console.log(arg.event.extendedProps.isUrgent);
                    console.log(arg.event.extendedProps);
                    console.log('----------------');
                    let props = arg.event.extendedProps;
                    let start = arg.event.start
                    let end = arg.event.end

                    let date = moment(start).format("YYYY-M-D")
                    let start_time = moment(start).format("LT")
                    let end_time = moment(end).format("LT")
                    let student = props.student_name
                    let instructor = props.instructor_name
                    let vehicle = props.vehicle_name
                    let lesson = props.lesson_name


                    let space = document.createElement('br');



                    let dateDom = document.createElement('div');
                    dateDom.innerHTML = date

                    let timeDom = document.createElement('div');
                    timeDom.innerHTML = `${start_time} - ${end_time}`

                    let studentDom = document.createElement('div');
                    // studentDom.innerHTML = `<p>student</p>  <p>${student}</p>`
                    studentDom.innerHTML = `${student}`


                    let instructorDom = document.createElement('div');
                    instructorDom.innerHTML = `${instructor}`

                    let vehicleDom = document.createElement('div');
                    vehicleDom.innerHTML = `${vehicle}`

                    let lessonDom = document.createElement('div');
                    lessonDom.innerHTML = `${lesson}`


                    // let arrayOfDomNodes = [dateDom, timeDom, space, lessonDom, space, studentDom, space,
                    //     instructor, space, vehicleDom
                    // ]
                    let arrayOfDomNodes = [dateDom, timeDom, space, lessonDom, space, studentDom, space,
                        instructorDom, space, vehicleDom
                    ]
                    return {
                        domNodes: arrayOfDomNodes
                    }
                }

            });
            calendar.render();

        });
    </script>
    {{-- core --}}
</x-filament-panels::page>
