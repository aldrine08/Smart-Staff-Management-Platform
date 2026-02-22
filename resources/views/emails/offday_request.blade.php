<p>Dear Admin,</p>
<p>{{ $offDay->user->name }} has requested an off day.</p>
<ul>
    <p><strong>Staff:</strong> {{ $offDayRequest->user->name }}</p>
    <li>Start Date: {{ $offDay->start_date }}</li>
    <li>End Date: {{ $offDay->end_date }}</li>
    <li>Reason: {{ $offDay->reason }}</li>
    <p><strong>Status:</strong> {{ $offDayRequest->status }}</p>
</ul>
<p>Please review this request in your dashboard.</p>
