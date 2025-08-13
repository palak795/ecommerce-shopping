<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>Send OTP</h2>
    <input type="text" id="phone" placeholder="Enter phone number (10 digits)">
    <button onclick="sendOtp()">Send OTP</button>

    <h2>Verify OTP</h2>
    <input type="text" id="otp" placeholder="Enter OTP">
    <button onclick="verifyOtp()">Verify OTP</button>

    <p id="response"></p>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function sendOtp() {
            const phone = document.getElementById('phone').value;
            fetch('/api/send-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ phone })
            })
            .then(res => res.json())
            .then(data => document.getElementById('response').innerText = data.message);
        }

        function verifyOtp() {
            const phone = document.getElementById('phone').value;
            const otp = document.getElementById('otp').value;
            fetch('/api/verify-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ phone, otp })
            })
            .then(res => res.json())
            .then(data => document.getElementById('response').innerText = data.message);
        }
    </script>
</body>
</html>
