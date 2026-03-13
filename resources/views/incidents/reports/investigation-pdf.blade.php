<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Accident Report - {{ $incident->brief_description }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #131F34;
            color: white;
            border-radius: 8px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .report-title {
            font-size: 18px;
            margin: 5px 0;
        }

        .report-id {
            font-size: 14px;
            margin: 5px 0;
            opacity: 0.9;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #131F34;
            color: #2c3e50;
        }

        .summary-box {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #131F34;
            border-radius: 4px;
        }

        .summary-label {
            font-size: 12px;
            color: #131F34;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 14px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .metrics-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
        }

        .metric-row {
            display: table-row;
        }

        .metric-cell {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: top;
        }

        .metric-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 15px;
            margin: 5px;
        }

        .metric-label {
            font-size: 11px;
            color: #131F34;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .metric-value {
            font-size: 14px;
            color: #2c3e50;
            font-weight: 500;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .info-table td {
            border: 1px solid #dee2e6;
            padding: 10px 12px;
            vertical-align: top;
        }

        .info-table td:first-child {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            width: 35%;
        }

        .person-card {
            display: flex;
            justify-content: space-between;
            padding: 10px 12px;
            margin: 5px 0;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 3px solid #6c5ce7;
        }

        .person-label {
            font-weight: 600;
            color: #495057;
        }

        .person-details {
            color: #131F34;
            font-size: 13px;
        }

        .tag-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 10px 0;
        }

        .tag {
            background-color: #6c5ce7;
            color: white;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .action-item {
            margin: 15px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #6c5ce7;
        }

        .action-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .action-number {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
        }

        .action-assignee {
            color: #131F34;
            font-size: 12px;
        }

        .action-content {
            color: #495057;
            font-size: 13px;
            margin: 8px 0;
        }

        .action-footer {
            color: #131F34;
            font-size: 11px;
            font-style: italic;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #dee2e6;
        }

        .comment-item {
            margin: 15px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #131F34;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .comment-author {
            font-weight: bold;
            color: #2c3e50;
        }

        .comment-date {
            color: #131F34;
            font-size: 12px;
        }

        .comment-text {
            color: #495057;
            font-size: 13px;
        }

        .attachment-list {
            list-style: none;
            padding: 0;
        }

        .attachment-list li {
            padding: 8px 12px;
            margin: 5px 0;
            background-color: #f8f9fa;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .attachment-name {
            font-weight: 500;
            color: #495057;
        }

        .attachment-size {
            color: #131F34;
            font-size: 12px;
        }

        .admin-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
        }

        .admin-row {
            display: table-row;
        }

        .admin-cell {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            vertical-align: top;
        }

        .admin-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 12px;
            margin: 5px;
            text-align: center;
        }

        .admin-label {
            font-size: 10px;
            color: #131F34;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .admin-value {
            font-size: 12px;
            color: #2c3e50;
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-completed { background-color: #d1fae5; color: #065f46; }
        .status-closed { background-color: #fee2e2; color: #991b1b; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-investigating { background-color: #dbeafe; color: #1e40af; }

        .footer {
            text-align: center;
            color: #131F34;
            font-style: italic;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
        }

        ul {
            list-style-type: disc;
            padding-left: 20px;
        }

        li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
@php
    use App\Models\BrandingSetting;
    $branding = BrandingSetting::first();
    $companyName = $branding->company_name ?? config('app.name', 'CIS Security');
@endphp

    <!-- Header -->
<div class="header">
    <h1 class="company-name">{{ strtoupper($companyName) }}</h1>
    <h2 class="report-title">ACCIDENT REPORT</h2>
    <p class="report-id">Report #{{ str_pad($incident->id, 6, '0', STR_PAD_LEFT) }} | Generated {{ now()->format('M j, Y') }}</p>
</div>

<!-- Incident Overview -->
<div class="summary-box">
    <div class="summary-label">Accident Description</div>
    <div class="summary-value">{{ $incident->brief_description }}</div>

    <div class="summary-label">Status</div>
    <div class="summary-value">
            <span class="status-badge status-{{ strtolower($incident->status) }}">
                {{ ucfirst($incident->status) }}
            </span>
    </div>
</div>

<!-- Key Details -->
<h3 class="section-title">Key Details</h3>
<div class="metrics-grid">
    <div class="metric-row">
        <div class="metric-cell">
            <div class="metric-card">
                <div class="metric-label">Date of Occurrence</div>
                <div class="metric-value">{{ $incident->date_of_occurrence ? $incident->date_of_occurrence->format('M d, Y') : 'N/A' }}</div>
            </div>
        </div>
        <div class="metric-cell">
            <div class="metric-card">
                <div class="metric-label">Time of Occurrence</div>
                <div class="metric-value">{{ $incident->time_of_occurrence ? \Carbon\Carbon::parse($incident->time_of_occurrence)->format('H:i') : 'N/A' }}</div>
            </div>
        </div>
    </div>
    <div class="metric-row">
        <div class="metric-cell">
            <div class="metric-card">
                <div class="metric-label">Accident Type</div>
                <div class="metric-value">{{ $incident->incidentType->name ?? 'N/A' }}</div>
            </div>
        </div>
        <div class="metric-cell">
            <div class="metric-card">
                <div class="metric-label">Location</div>
                <div class="metric-value">{{ $incident->location }}</div>
            </div>
        </div>
    </div>
</div>

<!-- What Happened -->
<h3 class="section-title">What Happened</h3>
<div class="summary-box">
    <p>{{ $incident->what_happened }}</p>
</div>

@if($incident->additional_information)
    <h3 class="section-title">Additional Information</h3>
    <div class="summary-box">
        <p>{{ $incident->additional_information }}</p>
    </div>
@endif

<!-- Location Details -->
<h3 class="section-title">Location Details</h3>
<table class="info-table">
    <tr>
        <td>Branch</td>
        <td>{{ $incident->branch->display_name ?? $incident->branch->branch_name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td>Site</td>
        <td>{{ $incident->site->name ?? 'N/A' }}</td>
    </tr>
    @if($incident->agency)
        <tr>
            <td>Agency</td>
            <td>{{ $incident->agency->name }}</td>
        </tr>
    @endif
</table>

<!-- People Involved -->
<h3 class="section-title">People Involved</h3>

<div class="person-card">
    <div>
        <div class="person-label">Affected Person</div>
        @if($incident->affected_person_source === 'Employee' && $incident->affectedEmployee)
            <div style="margin-top: 5px;">{{ $incident->affectedEmployee->first_name }} {{ $incident->affectedEmployee->last_name }}</div>
            <div class="person-details">
                Employee ID: {{ $incident->affectedEmployee->id }} |
                Site: {{ $incident->affectedEmployee->site->name ?? 'N/A' }}
                @if($incident->affectedEmployee->site && $incident->affectedEmployee->site->branch)
                    | Branch: {{ $incident->affectedEmployee->site->branch->branch_name }}
                @endif
            </div>
        @elseif($incident->affected_person_source === 'Customer' && $incident->affectedCustomer)
            <div style="margin-top: 5px;">{{ $incident->affectedCustomer->name }}</div>
            <div class="person-details">Customer</div>
        @elseif($incident->affected_person_source === 'Other')
            <div style="margin-top: 5px;">{{ $incident->affected_person_other }}</div>
            <div class="person-details">Other</div>
        @else
            <div style="margin-top: 5px;">Not specified</div>
        @endif
    </div>
</div>

<div class="person-card">
    <div>
        <div class="person-label">Reported By</div>
        @if($incident->reported_by_source === 'Employee' && $incident->reportedEmployee)
            <div style="margin-top: 5px;">{{ $incident->reportedEmployee->first_name }} {{ $incident->reportedEmployee->last_name }}</div>
            <div class="person-details">
                Employee ID: {{ $incident->reportedEmployee->id }} |
                Site: {{ $incident->reportedEmployee->site->name ?? 'N/A' }}
                @if($incident->reportedEmployee->site && $incident->reportedEmployee->site->branch)
                    | Branch: {{ $incident->reportedEmployee->site->branch->branch_name }}
                @endif
            </div>
        @elseif($incident->reported_by_source === 'Customer' && $incident->reportedCustomer)
            <div style="margin-top: 5px;">{{ $incident->reportedCustomer->name }}</div>
            <div class="person-details">Customer</div>
        @elseif($incident->reported_by_source === 'Other')
            <div style="margin-top: 5px;">{{ $incident->reported_by_other }}</div>
            <div class="person-details">Other</div>
        @else
            <div style="margin-top: 5px;">Not specified</div>
        @endif
    </div>
</div>

<!-- Medical Information -->
<h3 class="section-title">Medical Information</h3>
<table class="info-table">
    <tr>
        <td>Treatment Type</td>
        <td>{{ $incident->treatmentType->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td>Mechanism</td>
        <td>{{ $incident->mechanism->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td>Injury Type</td>
        <td>
            @if($incident->injuryType)
                {{ $incident->injuryType->name }}
            @elseif($incident->injury_type_other)
                {{ $incident->injury_type_other }}
            @else
                N/A
            @endif
        </td>
    </tr>
</table>

@php
    $bodyPartsList = [];
    if(!empty($incident->body_part_id) && is_array($incident->body_part_id)) {
        foreach($incident->body_part_id as $bodyPartId) {
            $bodyPart = \App\Models\BodyPart::find($bodyPartId);
            if ($bodyPart) $bodyPartsList[] = $bodyPart->name;
        }
    } elseif(!empty($incident->body_part_id) && is_numeric($incident->body_part_id)) {
        $bodyPart = \App\Models\BodyPart::find($incident->body_part_id);
        if ($bodyPart) $bodyPartsList[] = $bodyPart->name;
    }
    if(!empty($incident->body_part_other)) $bodyPartsList[] = $incident->body_part_other;
@endphp

@if(!empty($bodyPartsList))
    <div style="margin-top: 10px;">
        <strong style="font-size: 12px; color: #131F34;">Body Parts Affected:</strong>
        <div class="tag-container">
            @foreach($bodyPartsList as $bodyPart)
                <span class="tag">{{ $bodyPart }}</span>
            @endforeach
        </div>
    </div>
@endif

@if($incident->physician_details)
    <div style="margin-top: 15px;">
        <strong style="font-size: 12px; color: #131F34;">Physician / Healthcare Provider Details:</strong>
        <div class="summary-box" style="margin-top: 8px;">
            <p>{{ $incident->physician_details }}</p>
        </div>
    </div>
@endif

<!-- Corrective Actions -->
@if($incident->correctiveActions && $incident->correctiveActions->count() > 0)
    <h3 class="section-title">Corrective Actions</h3>
    @foreach($incident->correctiveActions as $action)
        <div class="action-item">
            <div class="action-header">
                <div class="action-number">Action #{{ $loop->iteration }}</div>
                <div class="action-assignee">Assigned to: {{ $action->user->name ?? 'Unassigned' }}</div>
            </div>
            <div class="action-content">
                {{ $action->corrective_actions ?? $action->description ?? 'No description provided' }}
            </div>
            <div class="action-footer">
                Created: {{ $action->created_at->format('M d, Y') }}
                @if($action->date_concluded)
                    | Concluded: {{ \Carbon\Carbon::parse($action->date_concluded)->format('M d, Y') }}
                @else
                    | Status: In Progress
                @endif
            </div>
        </div>
    @endforeach
@endif

<!-- Investigation Comments -->
@if($incident->comments && $incident->comments->count() > 0)
    <h3 class="section-title">Investigation Comments</h3>
    @foreach($incident->comments as $comment)
        <div class="comment-item">
            <div class="comment-header">
                <div class="comment-author">{{ $comment->user->name ?? 'Unknown User' }}</div>
                <div class="comment-date">{{ $comment->created_at->format('M d, Y \\a\\t H:i') }}</div>
            </div>
            <div class="comment-text">
                {{ $comment->comment }}
            </div>
        </div>
    @endforeach
@endif

<!-- Attachments -->
@if($incident->attachments && count($incident->attachments) > 0)
    <h3 class="section-title">Attachments</h3>
    <ul class="attachment-list">
        @foreach($incident->attachments as $index => $attachment)
            <li>
                <span class="attachment-name">{{ $attachment['name'] ?? 'Attachment ' . ($index + 1) }}</span>
                <span class="attachment-size">
                    {{ isset($attachment['size']) ? number_format($attachment['size'] / 1024, 1) . ' KB' : 'Unknown size' }}
                    ({{ strtoupper(pathinfo($attachment['name'] ?? '', PATHINFO_EXTENSION)) }})
                </span>
            </li>
        @endforeach
    </ul>
@endif

<!-- Administrative Details -->
<h3 class="section-title">Administrative Details</h3>
<div class="admin-grid">
    <div class="admin-row">
        <div class="admin-cell">
            <div class="admin-card">
                <div class="admin-label">Coordinator</div>
                <div class="admin-value">{{ $incident->coordinator->name ?? 'Not assigned' }}</div>
            </div>
        </div>
        <div class="admin-cell">
            <div class="admin-card">
                <div class="admin-label">Created By</div>
                <div class="admin-value">{{ $incident->creator->name ?? 'Unknown' }}</div>
            </div>
        </div>
        <div class="admin-cell">
            <div class="admin-card">
                <div class="admin-label">Date Created</div>
                <div class="admin-value">{{ $incident->created_at->format('M d, Y') }}</div>
            </div>
        </div>
    </div>
    @if($incident->updater)
        <div class="admin-row">
            <div class="admin-cell">
                <div class="admin-card">
                    <div class="admin-label">Last Updated By</div>
                    <div class="admin-value">{{ $incident->updater->name }}</div>
                </div>
            </div>
            <div class="admin-cell">
                <div class="admin-card">
                    <div class="admin-label">Last Updated</div>
                    <div class="admin-value">{{ $incident->updated_at->format('M d, Y') }}</div>
                </div>
            </div>
            <div class="admin-cell"></div>
        </div>
    @endif
</div>

<!-- Footer -->
<div class="footer">
    Report generated on {{ now()->format('F j, Y \\a\\t g:i A') }}<br>
    {{ $companyName }} - Confidential Document
</div>
</body>
</html>
