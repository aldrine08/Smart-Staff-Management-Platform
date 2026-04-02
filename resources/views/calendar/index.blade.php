<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800">
            📅 Event Calendar
        </h2>
    </x-slot>

    <div class="bg-white p-6 rounded-2xl shadow-lg">
        <div id="calendar"></div>
    </div>

    <!-- MODAL -->
    <div id="eventModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6">
            <h3 class="text-xl font-semibold mb-4">Create Event</h3>

            <div class="space-y-4">
                <input id="title" type="text" placeholder="Event Title"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500" />

                <div class="grid grid-cols-2 gap-2">
                    <input id="start" type="time" class="border rounded-lg p-2" />
                    <input id="end" type="time" class="border rounded-lg p-2" />
                </div>

                <input id="location" type="text" placeholder="Location"
                    class="w-full border rounded-lg p-2" />
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button onclick="closeModal()"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">Cancel</button>

                <button onclick="saveEvent()"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Save Event
                </button>
            </div>
        </div>
    </div>

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <script>
        let selectedDate = null;
        let calendar;

        document.addEventListener('DOMContentLoaded', function () {
            let calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                height: 'auto',

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },

                events: '/events',

                dateClick: function(info) {
                    selectedDate = info.dateStr;
                    openModal();
                }
            });

            calendar.render();
        });

        function openModal() {
            document.getElementById('eventModal').classList.remove('hidden');
            document.getElementById('eventModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('eventModal').classList.add('hidden');
        }

        function saveEvent() {
            let title = document.getElementById('title').value;
            let start = document.getElementById('start').value;
            let end = document.getElementById('end').value;
            let location = document.getElementById('location').value;

            if (!title) {
                alert('Title is required');
                return;
            }

            fetch('/events/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    title: title,
                    date: selectedDate,
                    start_time: start,
                    end_time: end,
                    location: location
                })
            })
            .then(res => res.json())
            .then(() => {
                closeModal();
                calendar.refetchEvents();

                // reset form
                document.getElementById('title').value = '';
                document.getElementById('start').value = '';
                document.getElementById('end').value = '';
                document.getElementById('location').value = '';
            });
        }
    </script>
</x-app-layout>
