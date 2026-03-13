<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        .report-title {
            font-size: 20px;
            color: #2d3748;
            margin: 0;
        }
        .content {
            margin-bottom: 25px;
        }
        .custom-message {
            background: #e3f2fd;
            padding: 15px;
            border-left: 4px solid #2196f3;
            border-radius: 4px;
            margin: 20px 0;
        }
        .attachment-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        <div class="logo">CIS Security</div>
        <h1 class="report-title">Monthly Report - {{ $monthName }} {{ $year }}</h1>
    </div>

    <div class="content">
        <p>Dear Recipient,</p>

        <p>Please find attached the monthly security report for {{ $monthName }} {{ $year }}. This comprehensive report includes detailed analytics, trend analysis, and key insights from the reporting period.</p>

        @if($customMessage)
            <div class="custom-message">
                <strong>Additional Message:</strong><br>
                {{ $customMessage }}
            </div>
        @endif

        <div class="attachment-info">
            <strong>📎 Attachment Details:</strong><br>
            • File: monthly-report-{{ $year }}-{{ str_pad(date('n', mktime(0, 0, 0, (int)str_replace($monthName, '', $monthName), 1)), 2, '0', STR_PAD_LEFT) }}.pdf<br>
            • Type: PDF Document<br>
            • Content: Complete monthly report with charts and analytics
        </div>

        <p>The report includes:</p>
        <ul>
            <li>📊 Interactive charts and performance metrics</li>
            <li>📈 6-month trend analysis</li>
            <li>📋 Weekly performance breakdown</li>
            <li>🏷️ Top complaint categories and incident types</li>
            <li>📝 Executive summary and recommendations</li>
        </ul>

        <p>If you have any questions about this report or need additional information, please don't hesitate to contact us.</p>

        <p>Best regards,<br>
            <strong>CIS Security Team</strong></p>
    </div>

    <div class="footer">
        <p>This is an automated message from the CIS Security reporting system.<br>
            Generated on {{ date('F j, Y \a\t g:i A') }}</p>
    </div>
</div>
</body>
</html>
