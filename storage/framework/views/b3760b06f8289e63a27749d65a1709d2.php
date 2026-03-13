<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Monthly Report Export</h2>
     <?php $__env->endSlot(); ?>

    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .main-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            padding: 3rem;
            margin-bottom: 2rem;
        }

        @media (prefers-color-scheme: dark) {
            .main-card {
                background: #1f2937;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            }
            h2.export-options {
                color: #fff;
            }
        }

        .intro-section {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            color: white;
        }

        .intro-title {
            font-size: 2rem;
            font-weight: 300;
            margin-bottom: 0.5rem;
        }

        .intro-description {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .form-section {
            margin-bottom: 3rem;
        }

        .form-section h2 {
            font-size: 1.5rem;
            color: #2d3748;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        @media (prefers-color-scheme: dark) {
            .form-section h2 {
                color: #e2e8f0;
            }
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        @media (prefers-color-scheme: dark) {
            .form-group label {
                color: #a0aec0;
            }
        }

        .form-group select {
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            background: white;
            transition: all 0.3s ease;
            color: #2d3748;
        }

        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            .form-group select {
                background: #2d3748;
                border-color: #4a5568;
                color: #e2e8f0;
            }

            .form-group select:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
            }
        }

        .actions-section {
            margin-bottom: 3rem;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .action-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-family: inherit;
            position: relative;
            overflow: hidden;
        }

        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .action-card:hover::before {
            left: 100%;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
        }

        .action-card.preview {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        }

        .action-card.pdf {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        }

        .action-card.word {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        }

        .action-card.email {
            background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
        }

        .action-card.email:hover {
            box-shadow: 0 15px 35px rgba(147, 51, 234, 0.3);
        }

        .action-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
        }

        .action-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .action-description {
            font-size: 0.9rem;
            opacity: 0.9;
            line-height: 1.4;
        }

        .info-section {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        @media (prefers-color-scheme: dark) {
            .info-section {
                background: linear-gradient(135deg, #1e3a8a 0%, #7c2d12 100%);
            }
        }

        .info-section h3 {
            color: #1976d2;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        @media (prefers-color-scheme: dark) {
            .info-section h3 {
                color: #60a5fa;
            }
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .features-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .feature-item {
                padding: 1rem;
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
            }

            .feature-icon {
                margin-right: 0.75rem;
                font-size: 1.1rem;
                flex-shrink: 0;
            }
        }

        @media (max-width: 480px) {
            .features-grid {
                gap: 0.5rem;
            }

            .feature-item {
                padding: 0.75rem;
                font-size: 0.85rem;
                display: flex;
                align-items: flex-start;
                text-align: left;
            }

            .feature-icon {
                margin-right: 0.5rem;
                font-size: 1rem;
                margin-top: 0.1rem;
            }

            .info-section {
                padding: 1.5rem;
            }

            .info-section h3 {
                font-size: 1.2rem;
                margin-bottom: 1rem;
            }
        }

        .feature-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        @media (prefers-color-scheme: dark) {
            .feature-item {
                background: #374151;
                color: #e2e8f0;
            }
        }

        .feature-icon {
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .main-card {
                padding: 2rem;
                border-radius: 16px;
            }

            .intro-title {
                font-size: 1.5rem;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="py-10 px-4">
        <div class="container">
            <div class="main-card">
                <?php if(session('error')): ?>
                    <div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <div class="intro-section">
                    <h1 class="intro-title">Monthly Reports</h1>
                    <p class="intro-description">Generate comprehensive monthly reports with professional charts and analytics</p>
                </div>

                <div class="form-section">
                    <h2>Select Report Period</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="month">Month</label>
                            <select id="month">
                                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($month['value']); ?>" <?php echo e($month['value'] == date('n') ? 'selected' : ''); ?>>
                                        <?php echo e($month['label']); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="year">Year</label>
                            <select id="year">
                                <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($year); ?>" <?php echo e($year == date('Y') ? 'selected' : ''); ?>>
                                        <?php echo e($year); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="actions-section">
                    <h2 class="export-options" style="font-weight: bold; font-size: 1.4em;padding-bottom: 0.75em;">
                        Export Options
                    </h2>
                    <div class="actions-grid">
                        <button class="action-card preview" onclick="previewReport()">
                            <div class="action-icon">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                            </div>
                            <div class="action-title">Preview Report</div>
                            <div class="action-description">View the report online before exporting</div>
                        </button>

                        <button class="action-card pdf" onclick="exportReport('pdf')">
                            <div class="action-icon">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v1h-1.5V7h3v1.5zM9 9.5h1v-1H9v1z"/>
                                </svg>
                            </div>
                            <div class="action-title">Export as PDF</div>
                            <div class="action-description">Professional formatted report for presentations</div>
                        </button>

                        <button class="action-card word" onclick="exportReport('word')">
                            <div class="action-icon">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                </svg>
                            </div>
                            <div class="action-title">Export as Word</div>
                            <div class="action-description">Editable document with detailed analysis</div>
                        </button>

                        <button class="action-card email" onclick="emailReport()">
                            <div class="action-icon">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                </svg>
                            </div>
                            <div class="action-title">Email as PDF</div>
                            <div class="action-description">Send report directly to stakeholders</div>
                        </button>
                    </div>
                </div>

                <div class="info-section">
                    <h3>Report Features</h3>
                    <div class="features-grid">
                        <div class="feature-item">
                            <span class="feature-icon">📊</span>
                            <span>Interactive Charts & Graphs</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">📈</span>
                            <span>6-Month Trend Analysis</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">📋</span>
                            <span>Weekly Performance Breakdown</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">🏷️</span>
                            <span>Top Complaint Categories</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">📝</span>
                            <span>Executive Summary</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">💡</span>
                            <span>Strategic Recommendations</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">📧</span>
                            <span>Email Distribution</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">🔒</span>
                            <span>BCC Privacy Protection</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function getSelectedPeriod() {
            const month = document.getElementById('month').value;
            const year = document.getElementById('year').value;
            return { month, year };
        }

        function previewReport() {
            const { month, year } = getSelectedPeriod();
            window.open(`<?php echo e(route('reports.preview')); ?>?month=${month}&year=${year}`, '_blank');
        }

        function exportReport(format) {
            const { month, year } = getSelectedPeriod();

            const urls = {
                pdf: `<?php echo e(route('reports.pdf')); ?>?month=${month}&year=${year}`,
                word: `<?php echo e(route('reports.word')); ?>?month=${month}&year=${year}`
            };

            window.location.href = urls[format];
        }

        function emailReport() {
            window.location.href = '<?php echo e(route("reports.email")); ?>';
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /home/sites/29a/4/4aab0e4a5e/public_html/laravel12/resources/views/reports/export-options.blade.php ENDPATH**/ ?>