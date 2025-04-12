<html>
<body>
    <h5>Hello Dr. {{ $name }},</h5>
    <p>{{ $m1 }}</p>
    <p>{{ $m2 }}</p>
    <p>{{ $m3 }}</p>
    <br/>

    <a href="{{ route('doctor.time-slot') }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        Set Time Slot Now
    </a>

    <br/>
    <p>Thank you for using our service.</p>
    <p>Regards,</p>
    <p>Doctor Management System</p>
</body>
</html>
