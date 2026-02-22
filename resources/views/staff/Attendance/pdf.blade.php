<h2>Attendance Report</h2>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th>Date</th>
        <th>Clock In</th>
        <th>Clock Out</th>
    </tr>

    @foreach($attendances as $attendance)
    <tr>
        <td>{{ $attendance->clock_in->format('Y-m-d') }}</td>
        <td>{{ $attendance->clock_in }}</td>
        <td>{{ $attendance->clock_out ?? '—' }}</td>
    </tr>
    @endforeach
</table>
<p>Total Days Present: {{ $attendances->count() }}</p>
<p>Total Hours Worked: {{ $totalHours }} hours</p>