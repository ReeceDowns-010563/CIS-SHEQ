<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Report</title>
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
            background-color: #667eea;
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

        .report-period {
            font-size: 14px;
            margin: 5px 0;
            opacity: 0.9;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #3498db;
            color: #2c3e50;
        }

        .executive-summary {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #3498db;
            border-radius: 4px;
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
            text-align: center;
            margin: 5px;
        }

        .metric-value {
            font-size: 20px;
            font-weight: bold;
            color: #27ae60;
            margin: 5px 0;
        }

        .metric-label {
            font-size: 12px;
            color: #6c757d;
            font-weight: 500;
        }

        .chart-section {
            margin: 30px 0;
            text-align: center;
        }

        .chart-image {
            max-width: 100%;
            height: auto;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: white;
            padding: 10px;
        }

        .weekly-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .weekly-table th,
        .weekly-table td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            text-align: left;
        }

        .weekly-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }

        .weekly-count {
            font-weight: bold;
            color: #27ae60;
        }

        .complaint-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            margin: 5px 0;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 3px solid #6c5ce7;
        }

        .complaint-name {
            font-weight: 500;
        }

        .complaint-count {
            font-weight: bold;
            color: #27ae60;
        }

        .analysis-item {
            margin: 15px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #6c5ce7;
        }

        .analysis-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #2c3e50;
        }

        .analysis-content {
            color: #495057;
            font-size: 13px;
        }

        .footer {
            text-align: center;
            color: #6c757d;
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
<div class="header">
    <h1 class="company-name">CIS SECURITY</h1>
    <h2 class="report-title">MONTHLY REPORT</h2>
    <p class="report-period">{{ $data['period'] }}</p>
</div>

<!-- Executive Summary -->
<div class="executive-summary">
    <h3 class="section-title">Executive Summary</h3>
    <ul>
        @foreach($data['summary'] as $point)
            <li>{{ $point }}</li>
        @endforeach
    </ul>
</div>

<!-- Key Metrics -->
<h3 class="section-title">Key Performance Indicators</h3>
<div class="metrics-grid">
    @foreach(array_chunk($data['metrics'], 2) as $metricChunk)
        <div class="metric-row">
            @foreach($metricChunk as $metric)
                <div class="metric-cell">
                    <div class="metric-card">
                        <div class="metric-value">{{ $metric['value'] }}</div>
                        <div class="metric-label">{{ $metric['label'] }}</div>
                    </div>
                </div>
            @endforeach
            @if(count($metricChunk) == 1)
                <div class="metric-cell"></div>
            @endif
        </div>
    @endforeach
</div>

<!-- Trends Chart -->
@if(!empty($chartImages['trends']))
    <div class="chart-section">
        <h3 class="section-title">6-Month Complaint Trends</h3>
        <img src="data:image/png;base64,{{ base64_encode($chartImages['trends']) }}" alt="Trends Chart" class="chart-image">
    </div>
@endif

<!-- Weekly Breakdown -->
<h3 class="section-title">Weekly Breakdown</h3>
<table class="weekly-table">
    <thead>
    <tr>
        <th>Week Period</th>
        <th>Complaint Count</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data['weeklyData'] as $week)
        <tr>
            <td>{{ $week['week'] }}</td>
            <td class="weekly-count">{{ $week['count'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- Pie Chart -->
@if(!empty($chartImages['pie']))
    <div class="chart-section">
        <h3 class="section-title">Top Complaint Categories</h3>
        <img src="data:image/png;base64,{{ base64_encode($chartImages['pie']) }}" alt="Complaint Categories Chart" class="chart-image" style="max-width: 400px;">
    </div>
@endif

<!-- Top Complaint Categories (Text) -->
<h3 class="section-title">Top Complaint Categories Breakdown</h3>
@if($data['complaintTypes']->count() > 0)
    @php $total = $data['complaintTypes']->sum('count'); @endphp
    @foreach($data['complaintTypes'] as $type)
        @php $percentage = $total > 0 ? round(($type->count / $total) * 100, 1) : 0; @endphp
        <div class="complaint-item">
            <span class="complaint-name">{{ $type->nature }}</span>
            <span class="complaint-count">{{ $type->count }} ({{ $percentage }}%)</span>
        </div>
    @endforeach
@else
    <p style="text-align: center; color: #6c757d; padding: 20px; font-style: italic;">
        No complaints recorded for this period.
    </p>
@endif

<!-- Detailed Analysis -->
<h3 class="section-title">Detailed Analysis</h3>
@foreach($data['analysis'] as $analysis)
    <div class="analysis-item">
        <h4 class="analysis-title">{{ $analysis['title'] }}</h4>
        <p class="analysis-content">{{ $analysis['content'] }}</p>
    </div>
@endforeach

<!-- Footer -->
<div class="footer">
    Report generated on {{ now()->format('F j, Y \a\t g:i A') }}
</div>
</body>
</html>
