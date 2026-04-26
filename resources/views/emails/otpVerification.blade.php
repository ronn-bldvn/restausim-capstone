<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RestauSim OTP Verification</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f5f7;
            font-family: Arial, sans-serif;
        }

        .wrapper {
            width: 100%;
            background-color: #f4f5f7;
            padding: 30px 15px;
        }

        .inner-table {
            width: 600px;
            max-width: 100%;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .header {
            padding: 32px 40px;
            border-bottom: 1px solid #eeeeee;
            text-align: center;
        }

        .logo-title {
            font-size: 32px;
            font-weight: 900;
            color: #0D0D54;
            line-height: 1.2;
        }

        .logo-title .highlight {
            color: #EA7C69;
        }

        .subtitle {
            display: block;
            font-size: 16px;
            font-weight: 400;
            color: #333333;
            margin-top: 6px;
        }

        .content {
            padding: 35px 30px;
            text-align: center;
        }

        .badge {
            display: inline-block;
            background-color: #FFF0ED;
            color: #EA7C69;
            font-size: 11px;
            font-weight: bold;
            padding: 6px 14px;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 20px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #0D0D54;
            margin-bottom: 10px;
        }

        .text {
            font-size: 15px;
            color: #555555;
            line-height: 1.7;
            margin: 0 auto 24px;
            max-width: 480px;
        }

        .otp-box {
            background: linear-gradient(135deg, #0D0D54, #1c1c7a);
            color: #ffffff;
            font-size: 34px;
            font-weight: bold;
            letter-spacing: 8px;
            padding: 18px 24px;
            border-radius: 12px;
            display: inline-block;
            margin: 20px 0;
        }

        .note-box {
            margin-top: 24px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            text-align: left;
            color: #666666;
            font-size: 14px;
            line-height: 1.6;
        }

        .footer {
            background-color: #0D0D54;
            color: #ffffff;
            text-align: center;
            padding: 22px 20px;
        }

        .footer p {
            margin: 6px 0;
            font-size: 12px;
            color: rgba(255,255,255,0.75);
        }

        @media screen and (max-width: 640px) {
            .header,
            .content {
                padding: 24px 18px !important;
            }

            .logo-title {
                font-size: 26px !important;
            }

            .subtitle {
                font-size: 14px !important;
            }

            .title {
                font-size: 20px !important;
            }

            .otp-box {
                font-size: 28px !important;
                letter-spacing: 5px !important;
                padding: 16px 18px !important;
            }

            .text {
                font-size: 14px !important;
            }
        }
    </style>
</head>
<body>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="wrapper">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" class="inner-table">

                    <!-- Header -->
                    <tr>
                        <td class="header">
                            <div class="logo-title">
                                Restau<span class="highlight">Sim</span>
                                <span class="subtitle">Immersive PoS & Inventory Training</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <span class="badge">Email Verification</span>

                            <div class="title">Verify Your Email Address</div>

                            <p class="text">
                                Hello,
                                use the verification code below to continue your registration in RestauSim.
                            </p>

                            <div class="otp-box">{{ $otp }}</div>

                            <p class="text">
                                This OTP was requested for:
                                <strong>{{ $email }}</strong>
                            </p>

                            <div class="note-box">
                                <strong>Important:</strong><br>
                                • Enter this 6-digit code in the verification form.<br>
                                • Do not share this code with anyone.<br>
                                • If you did not request this, you may safely ignore this email.
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <p>Sent by RestauSim • This is an automated message.</p>
                            <p>&copy; 2025 RestauSim. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>