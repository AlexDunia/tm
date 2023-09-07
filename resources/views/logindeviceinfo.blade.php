<!DOCTYPE html>
<html>
<head>
    <title>Login Device Info</title>
</head>
<body style="font-family: 'Montserrat', sans-serif; -webkit-font-smoothing: antialiased;">

    <div style="width: 80%; margin: auto;">
        <a href="https://www.tixdemand.com/" target="_blank">
            <img src="https://fextellar.com/images/ft.png" alt="tixdemand" style="display: block; margin: 0 auto; margin-bottom: 50px;">
        </a>
        <h2 style="color: #333">Hello {{$firstname}}</h2>
        <p style="color: #333;">We've just noticed a new login device on your account</p>
        <br>
        <br>
        <h3 style="color: #f746a5;">Login device info</h3>
        <br>
        <p style="margin-top: 30px; margin-bottom: 30px; color: #333;">IP Address: {{$locationData->ip}}</p>
        <hr>
        <p style="margin-top: 30px; margin-bottom: 30px; color: #333;">Country: {{$locationData->countryName}}</p>
        <hr>
        <p style="margin-top: 30px; margin-bottom: 30px; color: #333;">Country Code: {{$locationData->countryCode}}</p>
        <hr>
        <p style="margin-top: 30px; margin-bottom: 30px; color: #333;">Region: {{$locationData->regionCode}}</p>
        <hr>
        <p style="line-height: 1.7em; color: #333;">If you did not initiate this, we recommend you  <a href="https://www.youtube.com/" target="_blank" style="color: #f746a5; font-weight: 700;">  click here to reset your password  </a>  immediately to secure your account. You can also reach out to us for assistance.</p>
    </div>

    <!-- You can customize the email content and styling as per your requirements -->
</body>
</html>
