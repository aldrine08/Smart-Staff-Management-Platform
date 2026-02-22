<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attendance Export</title>
</head>
<body>
    <p>Hello Admin,</p>
    <h2 class="text-xl font-semibold text-gray-800">
    Hello, {{ auth()->user()->name }}! 👋
</h2>
    <p>The attendance report you requested has been exported. Please find the attached Excel file.</p>
    <p>Thank you.</p>
</body>
</html>
