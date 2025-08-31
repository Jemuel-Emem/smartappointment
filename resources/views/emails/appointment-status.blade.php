<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Appointment Update</title>
</head>
<body>
    <h2>Appointment Update</h2>
    <p>Hello {{ $appointment->user->name }},</p>

    <p>Your appointment with <strong>{{ $appointment->staff->name }}</strong>
    on <strong>{{ $appointment->appointment_date }} at {{ $appointment->appointment_time }}</strong>
    has been <strong>{{ $statusMessage }}</strong>.</p>

    <p>Thank you,<br> {{ config('SMART APPOINTMENT') }}</p>
</body>
</html>
