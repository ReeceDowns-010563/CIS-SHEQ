<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Existing incident report template
        EmailTemplate::updateOrCreate(
            ['key' => 'incident_report_notification'],
            [
                'name' => 'Incident Report Notification',
                'subject' => 'Incident Report - {incident_id}: {brief_description}',
                'body_html' => $this->getIncidentReportTemplate(),
                'category' => 'incidents',
                'description' => 'Email template for incident report notifications',
                'sample_data' => [
                    'incident_id' => 'INC-2024-001',
                    'brief_description' => 'Slip and fall in main lobby',
                    'location' => 'Main lobby entrance',
                    'date_of_occurrence' => '2024-01-15',
                    'time_of_occurrence' => '14:30',
                    'incident_type' => 'Workplace Injury',
                    'affected_person' => 'John Smith (Employee)',
                    'status' => 'Pending',
                    'reported_by' => 'Jane Doe (Security Officer)',
                    'branch_name' => 'Head Office',
                    'site_name' => 'Main Building',
                    'what_happened' => 'Employee slipped on wet floor near entrance during lunch break. Floor had been recently mopped.',
                    'additional_information' => 'First aid was administered on scene. Employee was able to walk but complained of back pain.',
                    'incident_url' => 'https://example.com/incidents/1',
                    'generated_date' => '2024-01-15 14:45'
                ],
                'is_active' => true,
            ]
        );

        // NEW: Monthly report template
        EmailTemplate::updateOrCreate(
            ['key' => 'monthly_report_notification'],
            [
                'name' => 'Monthly Report Notification',
                'subject' => 'Monthly Security Report - {month_name} {year}',
                'body_html' => $this->getMonthlyReportTemplate(),
                'category' => 'reports',
                'description' => 'Email template for monthly security report notifications with PDF attachment',
                'sample_data' => [
                    'month_name' => 'January',
                    'year' => '2024',
                    'custom_message' => 'This month showed significant improvement in response times and overall security metrics.',
                    'pdf_filename' => 'monthly-report-2024-01.pdf',
                    'generation_date' => 'January 31, 2024 at 5:30 PM'
                ],
                'is_active' => true,
            ]
        );
    }

    private function getIncidentReportTemplate(): string
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report Notification</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc2626; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px; }
        .incident-info { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #dc2626; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #374151; }
        .value { color: #6b7280; }
        .status { padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .footer { text-align: center; color: #6b7280; font-size: 12px; margin-top: 30px; }
        .button { display: inline-block; background: #dc2626; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Incident Report Notification</h1>
            <p>A new incident has been reported and requires attention</p>
        </div>

        <div class="content">
            <div class="incident-info">
                <h2>Incident Details</h2>

                <div class="field">
                    <span class="label">Incident ID:</span>
                    <span class="value">{incident_id}</span>
                </div>

                <div class="field">
                    <span class="label">Brief Description:</span>
                    <span class="value">{brief_description}</span>
                </div>

                <div class="field">
                    <span class="label">Location:</span>
                    <span class="value">{location}</span>
                </div>

                <div class="field">
                    <span class="label">Date of Occurrence:</span>
                    <span class="value">{date_of_occurrence}</span>
                </div>

                <div class="field">
                    <span class="label">Time of Occurrence:</span>
                    <span class="value">{time_of_occurrence}</span>
                </div>

                <div class="field">
                    <span class="label">Incident Type:</span>
                    <span class="value">{incident_type}</span>
                </div>

                <div class="field">
                    <span class="label">Affected Person:</span>
                    <span class="value">{affected_person}</span>
                </div>

                <div class="field">
                    <span class="label">Status:</span>
                    <span class="status status-pending">{status}</span>
                </div>

                <div class="field">
                    <span class="label">Reported By:</span>
                    <span class="value">{reported_by}</span>
                </div>

                <div class="field">
                    <span class="label">Branch:</span>
                    <span class="value">{branch_name}</span>
                </div>

                <div class="field">
                    <span class="label">Site:</span>
                    <span class="value">{site_name}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{incident_url}" class="button">View Full Incident Report</a>
            </div>

            <div class="incident-info">
                <h3>What Happened:</h3>
                <p>{what_happened}</p>

                <h3>Additional Information:</h3>
                <p>{additional_information}</p>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated notification from the CIS Security Incident Management System.</p>
            <p>Please do not reply to this email. For questions, contact your system administrator.</p>
            <p>Report generated on {generated_date}</p>
        </div>
    </div>
</body>
</html>';
    }

    private function getMonthlyReportTemplate(): string
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
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
        <h1 class="report-title">Monthly Report - {month_name} {year}</h1>
    </div>

    <div class="content">
        <p>Dear Recipient,</p>

        <p>Please find attached the monthly security report for {month_name} {year}. This comprehensive report includes detailed analytics, trend analysis, and key insights from the reporting period.</p>

        <div class="custom-message">
            <strong>Additional Message:</strong><br>
            {custom_message}
        </div>

        <div class="attachment-info">
            <strong>📎 Attachment Details:</strong><br>
            • File: {pdf_filename}<br>
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

        <p>If you have any questions about this report or need additional information, please don\'t hesitate to contact us.</p>

        <p>Best regards,<br>
            <strong>CIS Security Team</strong></p>
    </div>

    <div class="footer">
        <p>This is an automated message from the CIS Security reporting system.<br>
            Generated on {generation_date}</p>
    </div>
</div>
</body>
</html>';
    }
}
