<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
/* Reset */
body {
    margin: 0;
    padding: 0;
    background-color: #f4f5f7;
    font-family: Arial, sans-serif;
}

.inner-table {
    width: 600px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    overflow: hidden;
    margin: 0 auto;
}

/* Logo Title */
.logo-title span {
    font-size: 2rem;
    font-weight: 900;
    color: #0D0D54;
}
.logo-title .highlight { color: #EA7C69; }
.logo-title .subtitle { font-size: 1.125rem; color:black; font-weight:400; }

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

/* Card */
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
    margin-bottom: 16px;
    line-height: 1.5;
}
.score-box {
    background-color: #f4f5f7;
    padding: 12px;
    border-radius: 6px;
    font-size: 14px;
}
.score {
    color: #0D0D54;
    font-weight: bold;
    font-size: 16px;
}
.feedback {
    margin-top: 12px;
    font-size: 14px;
    color: #555;
}

/* Button */
.activity-button {
    display: inline-block;
    background-color: #0D0D54;
    color: white;
    font-weight: bold;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
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
@media screen and (max-width: 640px) {
    .inner-table { width: 95% !important; }
}
</style>
</head>
<body>

<table role="presentation" width="100%" style="background-color:#f4f5f7; text-align:center;">
<tr>
<td>

<table role="presentation" class="inner-table">

<!-- Header -->
<tr>
<td style="padding:32px 40px; border-bottom:1px solid #eeeeee; text-align:center;">
    <div class="logo-title">
        <span>Restau<span class="highlight">Sim</span>
        <span class="subtitle">: Immersive PoS & Inventory Training</span></span>
    </div>
</td>
</tr>

<!-- Content -->
<tr>
<td style="padding:20px;">

    <div style="text-align:center; margin-bottom:24px;">
        <span class="badge">Graded Activity</span>

        <p style="margin-top:20px; font-size:0.875rem; color:black;">
            {{ $section->section_name . ' - ' .$section->class_code. ': ' .$section->class_name }}
        </p>
    </div>

    <h1 style="font-size:1.125rem; color:#0D0D54; font-weight:bold; margin-bottom:20px;">
        Hello {{ $student->name ?? 'Student' }}!
    </h1>

    <div class="activity-card" style="padding:24px;">
        <strong class="title">{{ $activity->name }}</strong>

        <div class="description">
            Your submission has been reviewed and graded.
        </div>

        <div class="score-box">
            Score: <span class="score">{{ $session->score ?? 'N/A' }}</span>
            <br><br>
            <strong>Feedback:</strong><br>
            <div class="feedback">{!! nl2br(e($session->feedback ?? 'No feedback provided.')) !!}</div>
        </div>
    </div>

    <div style="margin-top:24px; text-align:center;">
        <a href="{{ $activityUrl }}" class="activity-button">View Your Submission</a>
    </div>

</td>
</tr>

<!-- Footer -->
<tr>
<td style="background-color:#0D0D54; padding:20px; text-align:center;">
    <p style="font-size:12px; color:#777; margin-top:20px;">
        Sent by RestauSim • This is an automated message.
    </p>
    <p style="color:white;">&copy; 2025 RestauSim. All rights reserved.</p>
</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>
