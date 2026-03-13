<x-app-layout>
    <x-slot name="header">
        <style>
            /* Header styling */
            .header-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 1rem;
                padding: 0.5rem 0;
            }

            .header-left {
                display: flex;
                align-items: center;
                gap: 1rem;
                min-width: 0;
                flex: 1;
            }

            .back-button {
                display: inline-flex;
                align-items: center;
                padding: 0.5rem;
                color: #6b7280;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                text-decoration: none;
                transition: all 0.15s ease-in-out;
                background-color: white;
                flex-shrink: 0;
            }

            .back-button:hover {
                color: var(--primary-colour);
                border-color: var(--primary-colour);
                background-color: #fef7f0;
                transform: translateX(-2px);
            }

            @media (prefers-color-scheme: dark) {
                .back-button {
                    color: #9ca3af;
                    border-color: #4b5563;
                    background-color: #1f2937;
                }

                .back-button:hover {
                    color: var(--primary-colour);
                    border-color: var(--primary-colour);
                    background-color: rgba(167, 98, 44, 0.1);
                }
            }

            .header-title {
                font-size: 1.125rem;
                font-weight: 600;
                color: #1f2937;
                margin: 0;
                word-break: break-word;
            }

            @media (prefers-color-scheme: dark) {
                .header-title {
                    color: #d1d5db;
                }
            }

            .create-user-btn {
                display: inline-flex;
                align-items: center;
                padding: 0.75rem 1.5rem;
                font-size: 0.875rem;
                font-weight: 700;
                color: white;
                background-color: var(--primary-colour);
                border: 2px solid var(--primary-colour);
                border-radius: 0.5rem;
                text-decoration: none;
                transition: all 0.15s ease-in-out;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                white-space: nowrap;
                min-width: fit-content;
            }

            .create-user-btn:hover {
                background-color: var(--secondary-colour);
                border-color: var(--secondary-colour);
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .create-user-btn:active {
                transform: translateY(0);
            }

            /* Mobile header adjustments */
            @media (max-width: 768px) {
                .header-container {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 0.75rem;
                }

                .header-left {
                    width: 100%;
                    justify-content: flex-start;
                }

                .header-title {
                    font-size: 1rem;
                }

                .create-user-btn {
                    width: 100%;
                    justify-content: center;
                    padding: 0.875rem;
                }
            }

            @media (max-width: 480px) {
                .header-title {
                    font-size: 0.95rem;
                }

                .create-user-btn {
                    font-size: 0.8rem;
                    padding: 0.75rem;
                }
            }
        </style>
        <div class="header-container">
            <div class="header-left">
                <a href="{{ route('reports.export') }}" class="back-button" title="Back to Settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">Monthly Report Preview - {{ $data['period'] }}</h2>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button onclick="exportPDF()" style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer; font-weight: 500;">
                    📄 Export PDF
                </button>
                <button onclick="exportWord()" style="background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer; font-weight: 500;">
                    📝 Export Word
                </button>
            </div>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Ensure full height dark mode coverage */
        body {
            min-height: 100vh;
        }

        @media (prefers-color-scheme: dark) {
            body {
                background-color: #111827 !important;
            }
        }

        .page-wrapper {
            min-height: 100vh;
            background-color: #f9fafb;
            padding-bottom: 2rem;
        }

        @media (prefers-color-scheme: dark) {
            .page-wrapper {
                background-color: #111827;
            }
        }

        .report-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        @media (prefers-color-scheme: dark) {
            .report-container {
                background: #1f2937;
                color: #e5e7eb;
            }
        }

        .report-header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            color: white;
        }

        .company-name {
            font-size: 2.5rem;
            font-weight: 300;
            margin: 0;
        }

        .report-title {
            font-size: 1.5rem;
            margin: 0.5rem 0;
            opacity: 0.9;
        }

        .report-period {
            font-size: 1.2rem;
            margin: 0;
            opacity: 0.8;
        }

        .executive-summary {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-left: 4px solid #3b82f6;
            padding: 2rem;
            margin: 2rem 0;
            border-radius: 8px;
        }

        @media (prefers-color-scheme: dark) {
            .executive-summary {
                background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
            }
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin: 2rem 0 1rem 0;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .section-title {
                color: #e5e7eb;
                border-color: #4b5563;
            }
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .metric-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        @media (prefers-color-scheme: dark) {
            .metric-card {
                background: #374151;
                border-color: #4b5563;
            }
        }

        .metric-value {
            font-size: 2rem;
            font-weight: bold;
            color: #059669;
            margin: 0.5rem 0;
        }

        .metric-label {
            font-size: 0.9rem;
            color: #6b7280;
            font-weight: 500;
        }

        @media (prefers-color-scheme: dark) {
            .metric-label {
                color: #9ca3af;
            }
        }

        .chart-container {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 2rem;
            margin: 2rem 0;
        }

        @media (prefers-color-scheme: dark) {
            .chart-container {
                background: #374151;
                border-color: #4b5563;
            }
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
            text-align: center;
        }

        @media (prefers-color-scheme: dark) {
            .chart-title {
                color: #e5e7eb;
            }
        }

        /* Wider chart container for line chart */
        .wide-chart-container {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 2rem;
            margin: 2rem 0;
            width: 100%;
        }

        @media (prefers-color-scheme: dark) {
            .wide-chart-container {
                background: #374151;
                border-color: #4b5563;
            }
        }

        .trends-chart-wrapper {
            height: 350px;
            position: relative;
            width: 100%;
        }

        .complaints-chart-wrapper {
            height: 300px;
            position: relative;
            max-width: 400px;
            margin: 0 auto;
        }

        .trend-item {
            display: inline-block;
            margin: 0.25rem 1rem;
            padding: 0.5rem;
            background: #f3f4f6;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        @media (prefers-color-scheme: dark) {
            .trend-item {
                background: #4b5563;
            }
        }

        .weekly-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .weekly-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
        }

        @media (prefers-color-scheme: dark) {
            .weekly-card {
                background: #374151;
                border-color: #4b5563;
            }
        }

        .weekly-period {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .weekly-period {
                color: #9ca3af;
            }
        }

        .weekly-count {
            font-size: 1.5rem;
            font-weight: bold;
            color: #059669;
        }

        .analysis-section {
            margin: 2rem 0;
        }

        .analysis-item {
            margin: 1.5rem 0;
            padding: 1.5rem;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #6366f1;
        }

        @media (prefers-color-scheme: dark) {
            .analysis-item {
                background: #374151;
            }
        }

        .analysis-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .analysis-title {
                color: #e5e7eb;
            }
        }

        .analysis-content {
            color: #4b5563;
            line-height: 1.6;
        }

        @media (prefers-color-scheme: dark) {
            .analysis-content {
                color: #d1d5db;
            }
        }

        .complaint-types-list {
            margin: 1rem 0;
        }

        .complaint-type-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            margin: 0.5rem 0;
            background: #f3f4f6;
            border-radius: 6px;
            border-left: 3px solid #6366f1;
        }

        @media (prefers-color-scheme: dark) {
            .complaint-type-item {
                background: #4b5563;
            }
        }

        .complaint-type-name {
            font-weight: 500;
        }

        .complaint-type-count {
            font-weight: bold;
            color: #059669;
        }

        .footer-text {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .footer-text {
                color: #9ca3af;
                border-color: #4b5563;
            }
        }
    </style>

    <div class="page-wrapper">
        <div class="report-container">
            <!-- Report Header -->
            <div class="report-header">
                <h1 class="company-name">CIS SECURITY</h1>
                <h2 class="report-title">MONTHLY REPORT</h2>
                <p class="report-period">{{ $data['period'] }}</p>
            </div>

            <!-- Executive Summary -->
            <div class="executive-summary">
                <h3 class="section-title">Executive Summary</h3>
                <ul style="list-style-type: disc; padding-left: 1.5rem;">
                    @foreach($data['summary'] as $point)
                        <li style="margin: 0.5rem 0;">{{ $point }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Key Metrics -->
            <h3 class="section-title">Key Performance Indicators</h3>
            <div class="metrics-grid">
                @foreach($data['metrics'] as $metric)
                    <div class="metric-card">
                        <div class="metric-value">{{ $metric['value'] }}</div>
                        <div class="metric-label">{{ $metric['label'] }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Monthly Complaint Volume Chart -->
            <div class="wide-chart-container">
                <div class="chart-title">Monthly Complaint Volume</div>
                <div class="trends-chart-wrapper">
                    <canvas id="trendsChart"></canvas>
                </div>
                <div style="margin-top: 1rem; text-align: center;">
                    @foreach($data['trendsData'] as $trend)
                        <div class="trend-item">
                            <strong>{{ $trend['month'] }}</strong>: {{ $trend['count'] }} complaints
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Weekly Breakdown -->
            <h3 class="section-title">Weekly Breakdown</h3>
            <div class="weekly-grid">
                @foreach($data['weeklyData'] as $week)
                    <div class="weekly-card">
                        <div class="weekly-period">{{ $week['week'] }}</div>
                        <div class="weekly-count">{{ $week['count'] }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Top Complaint Categories Chart -->
            @if($data['complaintTypes']->count() > 0)
                <div class="chart-container">
                    <div class="chart-title">Top Complaint Categories</div>
                    <div class="complaints-chart-wrapper">
                        <canvas id="complaintsChart"></canvas>
                    </div>

                    <div class="complaint-types-list">
                        @php $total = $data['complaintTypes']->sum('count'); @endphp
                        @foreach($data['complaintTypes'] as $type)
                            @php $percentage = $total > 0 ? round(($type->count / $total) * 100, 1) : 0; @endphp
                            <div class="complaint-type-item">
                                <span class="complaint-type-name">{{ $type->nature }}</span>
                                <span class="complaint-type-count">{{ $type->count }} ({{ $percentage }}%)</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="chart-container">
                    <div class="chart-title">Top Complaint Categories</div>
                    <p style="text-align: center; color: #6b7280; padding: 2rem;">
                        No complaints recorded for this period.
                    </p>
                </div>
            @endif

            <!-- Detailed Analysis -->
            <h3 class="section-title">Detailed Analysis</h3>
            <div class="analysis-section">
                @foreach($data['analysis'] as $analysis)
                    <div class="analysis-item">
                        <h4 class="analysis-title">{{ $analysis['title'] }}</h4>
                        <p class="analysis-content">{{ $analysis['content'] }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Footer -->
            <div class="footer-text">
                Report generated on {{ now()->format('F j, Y \a\t g:i A') }}
            </div>
        </div>
    </div>

    <script>
        // Trends Chart
        const trendsCtx = document.getElementById('trendsChart').getContext('2d');
        const trendsData = @json($data['trendsData']);

        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: trendsData.map(item => item.month),
                datasets: [{
                    label: 'Complaints',
                    data: trendsData.map(item => item.count),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        @if($data['complaintTypes']->count() > 0)
        // Complaints Chart
        const complaintsCtx = document.getElementById('complaintsChart').getContext('2d');
        const complaintsData = @json($data['complaintTypes']);

        new Chart(complaintsCtx, {
            type: 'doughnut',
            data: {
                labels: complaintsData.map(item => item.nature),
                datasets: [{
                    data: complaintsData.map(item => item.count),
                    backgroundColor: [
                        '#6366f1',
                        '#8b5cf6',
                        '#06b6d4',
                        '#10b981',
                        '#f59e0b'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
        @endif

        // Export functions
        function exportPDF() {
            const month = {{ $month ?? 'null' }};
            const year = {{ $year ?? 'null' }};

            const params = new URLSearchParams();
            if (month) params.append('month', month);
            if (year) params.append('year', year);

            window.location.href = `{{ route('reports.pdf') }}?${params.toString()}`;
        }

        function exportWord() {
            const month = {{ $month ?? 'null' }};
            const year = {{ $year ?? 'null' }};

            const params = new URLSearchParams();
            if (month) params.append('month', month);
            if (year) params.append('year', year);

            window.location.href = `{{ route('reports.word') }}?${params.toString()}`;
        }
    </script>
</x-app-layout>
