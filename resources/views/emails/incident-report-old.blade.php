<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Incident Report Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #667eea;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .section {
            margin-bottom: 25px;
            padding: 15px;
            border-left: 4px solid #667eea;
            background-color: #f8f9fa;
            border-radius: 0 4px 4px 0;
        }
        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        .detail-row {
            margin-bottom: 8px;
            display: flex;
            flex-wrap: wrap;
        }
        .detail-label {
            font-weight: bold;
            min-width: 150px;
            color: #495057;
        }
        .detail-value {
            flex: 1;
            color: #212529;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .what-happened-box {
            background: white;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>CIS SECURITY</h1>
    <h2>Incident Report Notification</h2>
    <p>Report ID: #{{ $incident->id }}</p>
</div>

<div class="alert">
    <strong>Notice:</strong> A new incident report has been submitted and requires attention.
</div>

<!-- Basic Information -->
<div class="section">
    <div class="section-title">Basic Information</div>
    <div class="detail-row">
        <div class="detail-label">Brief Description:</div>
        <div class="detail-value">{{ $incident->brief_description }}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Incident Type:</div>
        <div class="detail-value">{{ $incident->incidentType->name ?? 'N/A' }}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Location:</div>
        <div class="detail-value">{{ $incident->location }}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Date of Occurrence:</div>
        <div class="detail-value">{{ \Carbon\Carbon::parse($incident->date_of_occurrence)->format('F j, Y') }}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Time:</div>
        <div class="detail-value">{{ $incident->time_of_occurrence }}</div>
    </div>
</div>

<!-- Affected Person -->
<div class="section">
    <div class="section-title">Affected Person</div>
    <div class="detail-row">
        <div class="detail-label">Source:</div>
        <div class="detail-value">{{ $incident->affected_person_source }}</div>
    </div>
    @if($incident->affected_person_source === 'Employee' && $incident->affectedEmployee)
        <div class="detail-row">
            <div class="detail-label">Employee:</div>
            <div class="detail-value">{{ $incident->affectedEmployee->first_name }} {{ $incident->affectedEmployee->last_name }}</div>
        </div>
    @elseif($incident->affected_person_source === 'Customer' && $incident->affectedCustomer)
        <div class="detail-row">
            <div class="detail-label">Customer:</div>
            <div class="detail-value">{{ $incident->affectedCustomer->name }}</div>
        </div>
    @elseif($incident->affected_person_source === 'Other')
        <div class="detail-row">
            <div class="detail-label">Person:</div>
            <div class="detail-value">{{ $incident->affected_person_other }}</div>
        </div>
    @endif
</div>

<!-- Work Details -->
<div class="section">
    <div class="section-title">Work Details</div>
    <div class="detail-row">
        <div class="detail-label">Branch:</div>
        <div class="detail-value">{{ $incident->branch->branch_name ?? 'N/A' }}</div>
    </div>
    @if($incident->site)
        <div class="detail-row">
            <div class="detail-label">Site:</div>
            <div class="detail-value">{{ $incident->site->name }}</div>
        </div>
    @endif
    <div class="detail-row">
        <div class="detail-label">Work Shift:</div>
        <div class="detail-value">{{ $incident->work_shift }}</div>
    </div>
</div>

<!-- Incident Details -->
<div class="section">
    <div class="section-title">What Happened</div>
    <div class="what-happened-box">
        {{ $incident->what_happened }}
    </div>
</div>

<!-- Medical Information -->
<div class="section">
    <div class="section-title">Medical Information</div>
    <div class="detail-row">
        <div class="detail-label">Mechanism:</div>
        <div class="detail-value">{{ $incident->mechanism->name ?? 'N/A' }}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Body Part Affected:</div>
        <div class="detail-value">{{ $incident->bodyPart->name ?? 'N/A' }}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Injury Type:</div>
        <div class="detail-value">{{ $incident->injuryType->name ?? 'N/A' }}</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Treatment Given:</div>
        <div class="detail-value">{{ $incident->treatmentType->name ?? 'N/A' }}</div>
    </div>
</div>

@if($incident->coordinator)
    <div class="section">
        <div class="section-title">Assignment</div>
        <div class="detail-row">
            <div class="detail-label">Coordinator:</div>
            <div class="detail-value">{{ $incident->coordinator->name }}</div>
        </div>
    </div>
@endif

<div class="footer">
    <p>This incident report was submitted on {{ $incident->created_at->format('F j, Y \a\t g:i A') }}</p>
    <p>Report ID: #{{ $incident->id }} | Status: {{ ucfirst($incident->status) }}</p>
    <p><strong>CIS Security Management System</strong></p>
</div>
</body>
</html>
