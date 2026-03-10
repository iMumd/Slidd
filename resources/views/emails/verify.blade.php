<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verify your email — Slidd</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        body { margin: 0; padding: 0; width: 100% !important; }
        a { color: inherit; }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',Helvetica,Arial,sans-serif;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5;">
<tr><td align="center" style="padding:48px 20px 40px;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:480px;">

        <tr><td align="center" style="padding-bottom:28px;">
            <table role="presentation" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="background-color:#0f172a;border-radius:12px;width:38px;height:38px;text-align:center;vertical-align:middle;">
                        <span style="font-size:20px;font-weight:700;color:#ffffff;font-family:-apple-system,BlinkMacSystemFont,'Inter',Helvetica,Arial,sans-serif;display:block;line-height:38px;">S</span>
                    </td>
                    <td style="padding-left:10px;vertical-align:middle;">
                        <span style="font-size:15px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;font-family:-apple-system,BlinkMacSystemFont,'Inter',Helvetica,Arial,sans-serif;">Slidd</span>
                    </td>
                </tr>
            </table>
        </td></tr>

        <tr><td style="background-color:#ffffff;border-radius:16px;border:1px solid #e4e4e7;padding:40px 40px 36px;">

            <p style="margin:0 0 8px;font-size:20px;font-weight:700;color:#0f172a;letter-spacing:-0.02em;line-height:1.3;font-family:-apple-system,BlinkMacSystemFont,'Inter',Helvetica,Arial,sans-serif;">
                You're almost in.
            </p>
            <p style="margin:0 0 28px;font-size:14px;color:#71717a;line-height:1.65;font-family:-apple-system,BlinkMacSystemFont,'Inter',Helvetica,Arial,sans-serif;">
                Click the button below to verify your email and activate your Slidd account. This link is valid for <strong style="color:#52525b;font-weight:600;">60 minutes</strong>.
            </p>

            <table role="presentation" cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
                <tr>
                    <td style="background-color:#0f172a;border-radius:10px;">
                        <a href="{{ $url }}" style="display:block;padding:13px 32px;font-size:14px;font-weight:600;color:#ffffff;text-decoration:none;letter-spacing:-0.01em;font-family:-apple-system,BlinkMacSystemFont,'Inter',Helvetica,Arial,sans-serif;">
                            Verify email address
                        </a>
                    </td>
                </tr>
            </table>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
                <tr><td style="border-top:1px solid #f4f4f5;font-size:0;line-height:0;">&nbsp;</td></tr>
            </table>

            <p style="margin:0;font-size:12px;color:#a1a1aa;line-height:1.6;font-family:-apple-system,BlinkMacSystemFont,'Inter',Helvetica,Arial,sans-serif;">
                Button not working? Copy and paste this link into your browser:<br>
                <a href="{{ $url }}" style="color:#71717a;word-break:break-all;text-decoration:underline;">{{ $url }}</a>
            </p>

        </td></tr>

        <tr><td align="center" style="padding-top:24px;">
            <p style="margin:0 0 6px;font-size:12px;color:#a1a1aa;line-height:1.7;font-family:-apple-system,BlinkMacSystemFont,'Inter',Helvetica,Arial,sans-serif;">
                If you didn't create a Slidd account, you can safely ignore this email.
            </p>
            <p style="margin:0;font-size:12px;color:#a1a1aa;font-family:-apple-system,BlinkMacSystemFont,'Inter',Helvetica,Arial,sans-serif;">
                &copy; {{ date('Y') }} Slidd &nbsp;&middot;&nbsp;
                <a href="{{ url('/privacy') }}" style="color:#a1a1aa;text-decoration:underline;">Privacy</a>
                &nbsp;&middot;&nbsp;
                <a href="{{ url('/terms') }}" style="color:#a1a1aa;text-decoration:underline;">Terms</a>
            </p>
        </td></tr>

    </table>

</td></tr>
</table>

</body>
</html>
