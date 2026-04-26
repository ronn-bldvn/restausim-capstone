{{-- <h1>Welcome, {{ $user->name }}!</h1>
<p>Your faculty account has been created successfully. Here are your login details:</p>
<ul>
    <li><strong>Username:</strong> {{ $user->username }}</li>
    <li><strong>Email:</strong> {{ $user->email }}</li>
    <li><strong>Password:</strong> {{ $rawPassword }}</li>
</ul>
<p>Please log in and change your password as soon as possible.</p> --}}


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
/* Reset and basic styles */
body {
    margin: 0;
    padding: 0;
    background-color: #f4f5f7;
    font-family: Arial, sans-serif;
}

/* Inner table styling */
.inner-table {
    width: 600px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    overflow: hidden;
    margin: 0 auto;
}

/* Logo & Title */
.logo-title span {
    font-size: 2rem;
    font-weight: 900;
    color: #0D0D54;
    line-height: 1.2;
}
.logo-title .highlight { color: #EA7C69; }
.logo-title .subtitle { font-size: 1.125rem; font-weight: 400; color: black; letter-spacing: 0.025em; }

/* Badge */
.badge {
    display: inline-block;
    background-color: #FFF0ED;
    color: #EA7C69;
    font-size: 11px;
    font-weight: bold;
    padding: 6px 12px;
    border-radius: 9999px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 20px;
}

/* Activity Card */
.activity-card {
    background-color: #FDFDFD;
    border: 1px solid #E0E0E0;
    border-radius: 6px;
}
.activity-card .title {
    display: block;
    font-size: 18px;
    color: #0D0D54;
    margin-bottom: 8px;
    font-weight: bold;
}
.activity-card .description {
    font-size: 14px;
    color: #555;
    line-height: 1.5;
    margin-bottom: 20px;
    border-top: 1px dashed #ddd;
    padding-top: 16px;
}
.due-date {
    background-color: #f4f5f7;
    padding: 12px;
    border-radius: 6px;
    font-size: 13px;
    color: #555;
}
.due-date .date { color: #EA7C69; font-weight: bold; }
.posted-info { margin: 20px 0; font-size: 12px; color: #888; }
.posted-info .instructor { color: #0D0D54; font-weight: bold; }

/* Button */
.activity-button {
    display: inline-block;
    background-color: #0D0D54;
    color: white;
    font-weight: bold;
    font-size: 0.875rem;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
}

.activity-button a{
    color: white;
}

.activity-button:hover { background-color: #11116b; }

/* Footer */
.footer p {
    margin: 0;
    font-size: 12px;
    color: white;
    opacity: 0.7;
}

/* Responsive */
@media screen and (max-width: 640px) { /* Small devices */
    .inner-table { width: 95% !important; }
    .logo-title span { font-size: 1.5rem !important; }
    .logo-title .subtitle { font-size: 1rem !important; }
    .activity-card .title { font-size: 16px !important; }
    .activity-card .description { font-size: 13px !important; }
    .activity-button { font-size: 0.8rem !important; padding: 10px 20px !important; }
}

@media screen and (min-width: 641px) and (max-width: 768px) { /* Medium devices */
    .inner-table { width: 550px !important; }
}

@media screen and (min-width: 769px) and (max-width: 1024px) { /* Large devices */
    .inner-table { width: 600px !important; }
}

@media screen and (min-width: 1025px) { /* XL devices */
    .inner-table { width: 600px !important; }
}
</style>
</head>
<body>

<table role="presentation" width="100%" style="background-color:#f4f5f7; text-align:center;">
    <tr>
        <td>

            <table role="presentation" class="inner-table">

                <!-- Logo & Title -->
                <tr>
                    <td style="padding:32px 40px; border-bottom:1px solid #eeeeee; text-align:center;">
                        <div class="logo-title" style="display:inline-block; vertical-align:middle; text-align:left;">
                            <span>Restau<span class="highlight">Sim</span><span class="subtitle">: Immersive PoS & Inventory Training</span></span>
                        </div>
                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td style="padding:20px;">

                        <div style="text-align:center; margin-bottom:24px;">
                            <span class="badge">Faculty Account Credentials</span>
                            {{-- <p style="margin-top:20px; font-size:0.875rem; color:black;">
                                {{ $section->section_name . ' - ' .$section->class_code. ': ' .$section->class_name }}
                            </p> --}}
                        </div>

                        <h1 style="font-size:1.125rem; color:#0D0D54; font-weight:bold; margin-bottom:20px;">
                            Welcome, {{ $user->name }}!
                        </h1>

                        <div class="activity-card" style="background-color:#FDFDFD; border:1px solid #E0E0E0; border-radius:6px;">
                            <div style="padding:24px;">
                                <strong class="title">Your Faculty Account</strong>
                                <div class="description">Your faculty account has been created successfully. Here are your login details:</div>
                                <div class="posted-info">
                                    <strong>Username:</strong> {{ $user->username }} <br>
                                    <strong>Email:</strong> {{ $user->email }} <br>
                                    <strong>Password:</strong> {{ $rawPassword }} <br>
                                    <p>Please log in and change your password as soon as possible.</p>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background-color:#0D0D54; color:white; padding:20px; text-align:center;">
                        <p style="font-size:12px; color:#777; margin-top:20px;">
                            Sent by RestauSim • This is an automated message.
                        </p>
                        <p>&copy; 2025 RestauSim. All rights reserved.</p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
