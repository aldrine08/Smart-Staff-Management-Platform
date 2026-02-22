<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Clock In</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Staff Clock In Alert</h2>

    <p>
        <strong>{{ $user->name }}</strong> has clocked in.
    </p>

    <p>
        <strong>Date:</strong> {{ $attendance->date }} <br>
        <strong>Clock In:</strong> {{ \Carbon\Carbon::parse($attendance->clock_in)->format('h:i A') }} <br>
        <strong>Status:</strong> {{ $status }} <!-- On-Time or Late -->
    </p>

    <hr>
    <p style="font-size: 12px; color: #666;">HRMS System Notification</p>
</body>
</html>
