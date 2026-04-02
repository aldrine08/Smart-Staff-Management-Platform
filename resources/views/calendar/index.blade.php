<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Calendar
        </h2>
    </x-slot>

    <div class="bg-white p-4 rounded shadow">
        <div id="calendar"></div>
    </div>

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

   <script>
document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');

    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',

        selectable: true,

        events: '/events',

        // ✅ CLICK ON DATE
        dateClick: function(info) {

            let title = prompt("Enter Event Title:");
            if (!title) return;

            let startTime = prompt("Start Time (e.g. 07:30):");
            let endTime = prompt("End Time (e.g. 09:00):");
            let location = prompt("Location:");

            fetch('/events/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    title: title,
                    date: info.dateStr,
                    start_time: startTime,
                    end_time: endTime,
                    location: location
                })
            })
            .then(response => response.json())
            .then(data => {
                calendar.refetchEvents(); // reload events
                alert("Event added successfully!");
            });
        }
    });

    calendar.render();
});
</script>
</x-app-layout>