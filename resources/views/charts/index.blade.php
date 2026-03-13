<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            Analytics & Charts
        </h2>
    </x-slot>

    <style>
        .selection-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (prefers-color-scheme: dark) {
            .selection-container {
                background: linear-gradient(135deg, #1e3a8a 0%, #581c87 100%);
            }
        }

        .selection-wrapper {
            max-width: 1200px;
            width: 100%;
        }

        .hero-section {
            text-align: center;
            margin-bottom: 4rem;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .hero-description {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
            max-width: 600px;
            margin: 0 auto;
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .option-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .option-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .option-card:hover::before {
            left: 100%;
        }

        .option-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }

        @media (prefers-color-scheme: dark) {
            .option-card {
                background: rgba(31, 41, 55, 0.95);
                border-color: rgba(255, 255, 255, 0.1);
            }

            .option-card:hover {
                border-color: rgba(255, 255, 255, 0.3);
            }
        }

        .option-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
        }

        .option-card:hover .option-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 12px 24px rgba(99, 102, 241, 0.4);
        }

        .incidents-icon {
            background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
            box-shadow: 0 8px 16px rgba(239, 68, 68, 0.3);
        }

        .option-card:hover .incidents-icon {
            box-shadow: 0 12px 24px rgba(239, 68, 68, 0.4);
        }

        .option-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        @media (prefers-color-scheme: dark) {
            .option-title {
                color: #f9fafb;
            }
        }

        .option-description {
            font-size: 1rem;
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        @media (prefers-color-scheme: dark) {
            .option-description {
                color: #d1d5db;
            }
        }

        .option-features {
            list-style: none;
            padding: 0;
            margin: 0 0 2rem 0;
        }

        .option-features li {
            padding: 0.5rem 0;
            color: #4b5563;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .option-features li {
                color: #9ca3af;
            }
        }

        .option-features li::before {
            content: '✓';
            color: #10b981;
            font-weight: bold;
            font-size: 1rem;
        }

        .option-button {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        .option-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .option-button:hover::before {
            left: 100%;
        }

        .option-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.4);
        }

        .incidents-button {
            background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
        }

        .incidents-button:hover {
            box-shadow: 0 8px 16px rgba(239, 68, 68, 0.4);
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 3rem;
            padding-top: 3rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1.125rem;
            }

            .options-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .option-card {
                padding: 2rem 1.5rem;
            }

            .selection-container {
                padding: 1rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-section {
            animation: fadeInUp 0.8s ease-out;
        }

        .option-card:nth-child(1) {
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .option-card:nth-child(2) {
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        .stats-section {
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }
    </style>

    <div class="selection-container">
        <div class="selection-wrapper">

            <!-- Options Grid -->
            <div class="options-grid">
                <!-- Complaints Option -->
                <div class="option-card" onclick="window.location.href='{{ route('charts.complaints') }}'">
                    <div class="option-icon">
                        📋
                    </div>
                    <h3 class="option-title">Complaints Analytics</h3>
                    <p class="option-description">
                        Deep dive into complaint patterns, resolution times, and customer feedback trends to improve service quality.
                    </p>
                    <ul class="option-features">
                        <li>Trend Analysis Over Time</li>
                        <li>Complaints by Category</li>
                        <li>Resolution Time Metrics</li>
                        <li>Site-wise Distribution</li>
                        <li>Status Tracking</li>
                    </ul>
                    <a href="{{ route('charts.complaints') }}" class="option-button">
                        View Complaints Charts →
                    </a>
                </div>

                <!-- Incidents Option -->
                <div class="option-card" onclick="window.location.href='{{ route('charts.incidents') }}'">
                    <div class="option-icon incidents-icon">
                        🚨
                    </div>
                    <h3 class="option-title">Accidents Analytics</h3>
                    <p class="option-description">
                        Analyze incident reports, safety metrics, and response effectiveness to enhance workplace safety protocols.
                    </p>
                    <ul class="option-features">
                        <li>Incident Frequency Trends</li>
                        <li>Type-based Classification</li>
                        <li>Response Time Analysis</li>
                        <li>Location Hotspots</li>
                        <li>Safety Metrics</li>
                    </ul>
                    <a href="{{ route('charts.incidents') }}" class="option-button incidents-button">
                        View Accidents Charts →
                    </a>
                </div>
            </div>

            <!-- Quick Stats Section -->
            <div class="stats-section">
                <div class="stat-card">
                    <div class="stat-number">6+</div>
                    <div class="stat-label">Chart Types</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">Real-time</div>
                    <div class="stat-label">Data Updates</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">Multi-site</div>
                    <div class="stat-label">Filtering</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">Export</div>
                    <div class="stat-label">Ready</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add hover effects and smooth transitions
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.option-card');

            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });

                // Add click animation
                card.addEventListener('mousedown', function() {
                    this.style.transform = 'translateY(-4px) scale(1.01)';
                });

                card.addEventListener('mouseup', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                });
            });

            // Prevent card click when clicking on button
            const buttons = document.querySelectorAll('.option-button');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
    </div>
</x-app-layout>
