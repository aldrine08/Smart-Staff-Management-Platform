<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Clock Out</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Staff Clock Out Alert</h2>

    <p>
        <strong>{{ $user->name }}</strong> has clocked out.
    </p>

    <p>
        <strong>Date:</strong> {{ $attendance->date }} <br>
        <strong>Clock In:</strong> {{ \Carbon\Carbon::parse($attendance->clock_in)->format('h:i A') }} <br>
        <strong>Clock Out:</strong> {{ \Carbon\Carbon::parse($attendance->clock_out)->format('h:i A') }}
    </p>

    <hr>
    <p style="font-size: 12px; color: #666;">HRMS System Notification</p>
</body>
</html>
