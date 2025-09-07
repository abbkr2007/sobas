<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASUU National Conference 2024 in Abuja: Invitation Letter (170720155907)</title>
    <style>
        body {
            line-height: 1.5; /* Set line spacing to 1.5 for better readability */
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Replace 'logo.png' with the actual path to your logo image -->
        <img src="https://member.asuu.org.ng/images/logo.png" alt="ASUU Logo" width="300">
    </div>
    <h4>Dear {{ $user->first_name }} {{ $user->last_name }},</h4>
    
    <p>Welcome to the State of the Nation Conference; <strong>NIGERIA IN A STATE OF GENERAL CRISIS: THE SEARCH FOR A NEW PATH TO DEVELOPMENT</strong></p>

    <p>You are invited to submit an Abstract on one or two of the Sub-Themes of the Conference. To learn more, follow the link: <a href="https://conference.asuu.org.ng/">https://conference.asuu.org.ng/</a></p>

    <p>
        <strong>Login to submit the abstract at:</strong>
        <a href="https://account-conference.asuu.org.ng/auth/signin">https://account-conference.asuu.org.ng/auth/signin</a><br>
        <strong>Username:</strong> {{ $user->email }}<br>
        <strong>Password:</strong>  {{ $user->plain_password }}
    </p>

    <p>The Conference invitation letter and the Registration Receipt can be downloaded from your portal. Also, a copy of the Registration Receipt has been sent to your mail.</p>

    <p>
        <strong>Conference site:</strong> <a href="https://conference.asuu.org.ng/">https://conference.asuu.org.ng/</a><br>
        <strong>Hotel information:</strong> <a href="https://conference.asuu.org.ng/locale">https://conference.asuu.org.ng/locale</a><br>
        <strong>For Visa Application information:</strong> <a href="https://immigration.gov.ng/">https://immigration.gov.ng/</a>
    </p>

    <p>
        Thank you very much for your kind attention.<br><br>
        With our best regards,<br><br>
        ASUU National Conference in Abuja<br>
        Email: <a href="mailto:conference@asuu.org.ng">conference@asuu.org.ng</a>
    </p>
</body>
</html>
