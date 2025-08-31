@component('mail::message')
# New Appointment Request

Hello {{ $appointment->staff->name }},

You have a new appointment request:

- **Patient/User:** {{ $appointment->user->name }}
- **Purpose:** {{ $appointment->purpose_of_appointment }}
- **Date:** {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}
- **Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}

Please log in to your dashboard to approve or decline this request.

@component('mail::button', ['url' => url('/staff/appointments')])
View Appointment
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent
