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
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Analytics & Charts</h2>
            <button onclick="showExportModal()" class="export-chart-btn" id="exportBtn" style="display: none;">
                Export Chart
            </button>
        </div>
     <?php $__env->endSlot(); ?>

    <!-- Include html2canvas for chart export with stats -->
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .charts-container {
            min-height: 100vh;
            background-color: #f9fafb;
            padding: 2rem 1rem;
        }

        @media (prefers-color-scheme: dark) {
            .charts-container {
                background-color: #111827;
            }
        }

        .charts-wrapper {
            max-width: 1400px;
            margin: 0 auto;
        }

        .controls-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .controls-section {
                background: #1f2937;
                border-color: #374151;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            }
        }

        .controls-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            align-items: end;
        }

        .control-group {
            display: flex;
            flex-direction: column;
        }

        .control-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .control-label {
                color: #d1d5db;
            }
        }

        .control-select,
        .control-button {
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
            color: #374151;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .control-select:focus,
        .control-button:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            .control-select,
            .control-button {
                background: #374151;
                border-color: #4b5563;
                color: #d1d5db;
            }

            .control-select:focus,
            .control-button:focus {
                border-color: #6366f1;
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            }
        }

        /* Custom searchable dropdown */
        .searchable-dropdown {
            position: relative;
            width: 100%;
        }

        .dropdown-button {
            width: 100%;
            padding: 0.75rem 2.5rem 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
            color: #374151;
            font-size: 0.875rem;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .dropdown-button:hover {
            border-color: #6366f1;
        }

        .dropdown-button:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .dropdown-arrow {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            transition: transform 0.2s ease;
        }

        .dropdown-button[aria-expanded="true"] .dropdown-arrow {
            transform: translateY(-50%) rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-search-wrapper {
            position: relative;
            border-bottom: 1px solid #e5e7eb;
        }

        .dropdown-search {
            padding: 0.75rem 0.75rem 0.75rem 2.5rem;
            border: none;
            width: 100%;
            font-size: 0.875rem;
            background: #f9fafb;
            color: #000000;
            font-weight: 500;
        }

        .dropdown-search:focus {
            outline: none;
            background: white;
            color: #000000;
        }

        .dropdown-search::placeholder {
            color: #6b7280;
            font-weight: 400;
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #6b7280;
            pointer-events: none;
        }

        .dropdown-option {
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dropdown-option:hover {
            background: #f3f4f6;
        }

        .dropdown-option.selected {
            background: #6366f1;
            color: white;
        }

        .dropdown-option input[type="checkbox"] {
            margin: 0;
        }

        @media (prefers-color-scheme: dark) {
            .dropdown-button {
                background: #374151;
                border-color: #4b5563;
                color: #d1d5db;
            }

            .dropdown-menu {
                background: #374151;
                border-color: #4b5563;
            }

            .dropdown-search-wrapper {
                border-bottom-color: #4b5563;
            }

            .dropdown-search {
                background: #1f2937;
                color: #ffffff;
                border-color: #4b5563;
            }

            .dropdown-search:focus {
                background: #1f2937;
                color: #ffffff;
            }

            .dropdown-search::placeholder {
                color: #9ca3af;
            }

            .search-icon {
                color: #9ca3af;
            }

            .dropdown-option:hover {
                background: #4b5563;
            }

            .dropdown-option.selected {
                background: #6366f1;
                color: white;
            }
        }

        /* Custom date range fields */
        .custom-date-fields {
            display: none;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1rem;
        }

        .custom-date-fields.show {
            display: grid;
        }

        .update-button {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .update-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .export-chart-btn {
            background: var(--primary-colour);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }

        .export-chart-btn:hover {
            background: var(--secondary-colour);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px #000000;
        }

        .chart-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .chart-section {
                background: #1f2937;
                border-color: #374151;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            }
        }

        .chart-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .chart-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        @media (prefers-color-scheme: dark) {
            .chart-title {
                color: #f9fafb;
            }
        }

        .chart-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .chart-subtitle {
                color: #9ca3af;
            }
        }

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }

        .doughnut-container {
            position: relative;
            height: 500px;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .bar-container {
            position: relative;
            height: 450px;
            width: 100%;
        }

        /* Better legend styling */
        .chart-container canvas {
            max-height: 100% !important;
        }

        /* Ensure legends are visible in dark mode */
        @media (prefers-color-scheme: dark) {
            .chart-container {
                color: #e5e7eb;
            }
        }

        /* Responsive legend adjustments */
        @media (max-width: 768px) {
            .chart-container {
                height: 350px;
            }

            .doughnut-container {
                height: 450px;
            }
        }

        .chart-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            .chart-stats {
                border-color: #374151;
            }
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #6366f1;
            margin: 0;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        @media (prefers-color-scheme: dark) {
            .stat-label {
                color: #9ca3af;
            }
        }

        /* Export Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 2rem;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            position: relative;
        }

        @media (prefers-color-scheme: dark) {
            .modal-content {
                background-color: #1f2937;
                color: #e5e7eb;
            }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            right: 1rem;
            top: 1rem;
        }

        .close:hover {
            color: #000;
        }

        @media (prefers-color-scheme: dark) {
            .close:hover {
                color: #fff;
            }
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #1f2937;
        }

        @media (prefers-color-scheme: dark) {
            .modal-title {
                color: #f9fafb;
            }
        }

        .modal-option {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-option:hover {
            border-color: #6366f1;
            background-color: #f8fafc;
        }

        @media (prefers-color-scheme: dark) {
            .modal-option {
                border-color: #4b5563;
                background-color: transparent;
            }

            .modal-option:hover {
                border-color: #6366f1;
                background-color: #374151;
            }
        }

        .modal-option input[type="radio"] {
            margin: 0;
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }

        .modal-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .modal-btn-cancel {
            background: #6b7280;
            color: white;
        }

        .modal-btn-export {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .modal-btn:hover {
            transform: translateY(-1px);
        }

        .no-data {
            text-align: center;
            color: #6b7280;
            padding: 3rem;
            font-size: 1.125rem;
        }

        @media (prefers-color-scheme: dark) {
            .no-data {
                color: #9ca3af;
            }
        }
    </style>

    <div class="charts-container">
        <div class="charts-wrapper">
            <!-- Controls Section -->
            <div class="controls-section">
                <form method="GET" action="<?php echo e(route('charts.complaints')); ?>" id="chartForm">
                    <div class="controls-grid">
                        <!-- Chart Type -->
                        <div class="control-group">
                            <label class="control-label" for="chart-type">Chart Type</label>
                            <div class="searchable-dropdown">
                                <button type="button" class="dropdown-button" id="chart-dropdown-btn" aria-expanded="false">
                                    <span id="chart-selected-text">
                                        <?php echo e($chartTypes[$chartType] ?? 'Select Chart Type'); ?>

                                    </span>
                                    <span class="dropdown-arrow">▼</span>
                                </button>
                                <div class="dropdown-menu" id="chart-dropdown-menu">
                                    <div class="dropdown-search-wrapper">
                                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        <input type="text" class="dropdown-search" placeholder="Search chart types..." id="chart-search">
                                    </div>
                                    <?php $__currentLoopData = $chartTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="dropdown-option" data-value="<?php echo e($key); ?>">
                                            <?php echo e($label); ?>

                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <input type="hidden" name="chart" id="chart-type" value="<?php echo e($chartType); ?>">
                            </div>
                        </div>

                        <!-- Time Range -->
                        <div class="control-group">
                            <label class="control-label" for="time-range">Time Range</label>
                            <select name="range" id="time-range" class="control-select" onchange="toggleCustomDateFields()">
                                <option value="30-days" <?php echo e($timeRange === '30-days' ? 'selected' : ''); ?>>Last 30 Days</option>
                                <option value="3-months" <?php echo e($timeRange === '3-months' ? 'selected' : ''); ?>>Last 3 Months</option>
                                <option value="6-months" <?php echo e($timeRange === '6-months' ? 'selected' : ''); ?>>Last 6 Months</option>
                                <option value="12-months" <?php echo e($timeRange === '12-months' ? 'selected' : ''); ?>>Last 12 Months</option>
                                <option value="custom" <?php echo e($timeRange === 'custom' ? 'selected' : ''); ?>>Custom Range</option>
                            </select>
                        </div>

                        <!-- Sites Filter -->
                        <div class="control-group">
                            <label class="control-label" for="sites">Sites Filter</label>
                            <div class="searchable-dropdown">
                                <button type="button" class="dropdown-button" id="sites-dropdown-btn" aria-expanded="false">
                                    <span id="sites-selected-text">
                                        <?php if(empty($siteIds)): ?>
                                            All Sites
                                        <?php elseif(count($siteIds) == 1): ?>
                                            <?php echo e($sites->where('id', $siteIds[0])->first()->name ?? 'Selected Site'); ?>

                                        <?php else: ?>
                                            <?php echo e(count($siteIds)); ?> sites selected
                                        <?php endif; ?>
                                    </span>
                                    <span class="dropdown-arrow">▼</span>
                                </button>
                                <div class="dropdown-menu" id="sites-dropdown-menu">
                                    <div class="dropdown-search-wrapper">
                                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        <input type="text" class="dropdown-search" placeholder="Search sites..." id="sites-search">
                                    </div>
                                    <div class="dropdown-option" data-value="">
                                        <input type="checkbox" id="all-sites" <?php echo e(empty($siteIds) ? 'checked' : ''); ?>>
                                        <label for="all-sites"><strong>All Sites</strong></label>
                                    </div>
                                    <?php $__currentLoopData = $sites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $site): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="dropdown-option" data-value="<?php echo e($site->id); ?>">
                                            <input type="checkbox" name="sites[]" value="<?php echo e($site->id); ?>" id="site-<?php echo e($site->id); ?>"
                                                <?php echo e(in_array($site->id, $siteIds ?? []) ? 'checked' : ''); ?>>
                                            <label for="site-<?php echo e($site->id); ?>"><?php echo e($site->name); ?></label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Update Button -->
                        <div class="control-group">
                            <button type="submit" class="control-button update-button">
                                📊 Update Chart
                            </button>
                        </div>
                    </div>

                    <!-- Custom Date Range Fields -->
                    <div class="custom-date-fields <?php echo e($timeRange === 'custom' ? 'show' : ''); ?>" id="custom-date-fields">
                        <div class="control-group">
                            <label class="control-label" for="custom-from">From Date</label>
                            <input type="date" name="custom_from" id="custom-from" class="control-select"
                                   value="<?php echo e($customFrom); ?>" max="<?php echo e(date('Y-m-d')); ?>">
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="custom-to">To Date</label>
                            <input type="date" name="custom_to" id="custom-to" class="control-select"
                                   value="<?php echo e($customTo); ?>" max="<?php echo e(date('Y-m-d')); ?>">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Chart Section -->
            <div class="chart-section" id="chart-section">
                <?php if(isset($data)): ?>
                    <div class="chart-header">
                        <h3 class="chart-title"><?php echo e($data['title']); ?></h3>
                        <p class="chart-subtitle">
                            <?php if($timeRange === 'custom' && $customFrom && $customTo): ?>
                                <?php echo e(\Carbon\Carbon::parse($customFrom)->format('M j, Y')); ?> - <?php echo e(\Carbon\Carbon::parse($customTo)->format('M j, Y')); ?>

                            <?php else: ?>
                                <?php echo e(ucwords(str_replace(['-', '_'], ' ', $timeRange))); ?>

                            <?php endif; ?>
                            <?php if(!empty($siteIds)): ?>
                                • Filtered by <?php echo e(count($siteIds)); ?> site(s)
                            <?php else: ?>
                                • All Sites
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="<?php echo e($data['type'] === 'doughnut' ? 'doughnut-container' : ($data['type'] === 'bar' ? 'bar-container' : 'chart-container')); ?>">
                        <canvas id="analyticsChart"></canvas>
                    </div>

                    <!-- Chart Statistics -->
                    <div class="chart-stats">
                        <div class="stat-item">
                            <p class="stat-value"><?php echo e(collect($data['values'])->sum()); ?></p>
                            <p class="stat-label">Total</p>
                        </div>
                        <div class="stat-item">
                            <p class="stat-value"><?php echo e(collect($data['values'])->avg() ? number_format(collect($data['values'])->avg(), 1) : '0'); ?></p>
                            <p class="stat-label">Average</p>
                        </div>
                        <div class="stat-item">
                            <p class="stat-value"><?php echo e(collect($data['values'])->max() ?? 0); ?></p>
                            <p class="stat-label">Peak</p>
                        </div>
                        <div class="stat-item">
                            <p class="stat-value"><?php echo e(collect($data['values'])->count()); ?></p>
                            <p class="stat-label">Data Points</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <p>📊 Select chart parameters and click "Update Chart" to view analytics</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div id="exportModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeExportModal()">&times;</span>
            <h3 class="modal-title">Export Chart</h3>

            <div class="modal-option" onclick="selectExportOption('chart-only')">
                <input type="radio" id="chart-only" name="export-type" value="chart-only">
                <div>
                    <strong>Chart Only</strong>
                    <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">Export just the chart visualization</p>
                </div>
            </div>

            <div class="modal-option" onclick="selectExportOption('chart-stats')">
                <input type="radio" id="chart-stats" name="export-type" value="chart-stats" checked>
                <div>
                    <strong>Chart + Statistics</strong>
                    <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">Export chart with statistics below</p>
                </div>
            </div>

            <div class="modal-buttons">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="closeExportModal()">Cancel</button>
                <button type="button" class="modal-btn modal-btn-export" onclick="exportChart()">Export</button>
            </div>
        </div>
    </div>

    <script>
        let currentChart = null;

        document.addEventListener('DOMContentLoaded', function() {
            initDropdowns();

            <?php if(isset($data)): ?>
            showExportButton();
            initChart();
            <?php endif; ?>
        });

        function initDropdowns() {
            // Chart Type Dropdown
            const chartBtn = document.getElementById('chart-dropdown-btn');
            const chartMenu = document.getElementById('chart-dropdown-menu');
            const chartSearch = document.getElementById('chart-search');
            const chartHidden = document.getElementById('chart-type');

            chartBtn.addEventListener('click', function() {
                const isOpen = chartMenu.classList.contains('show');
                closeAllDropdowns();
                if (!isOpen) {
                    chartMenu.classList.add('show');
                    chartBtn.setAttribute('aria-expanded', 'true');
                    chartSearch.focus();
                }
            });

            chartSearch.addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const options = chartMenu.querySelectorAll('.dropdown-option');
                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    option.style.display = text.includes(filter) ? 'block' : 'none';
                });
            });

            chartMenu.addEventListener('click', function(e) {
                const option = e.target.closest('.dropdown-option');
                if (option && option.dataset.value !== undefined) {
                    const value = option.dataset.value;
                    const text = option.textContent.trim();
                    document.getElementById('chart-selected-text').textContent = text;
                    chartHidden.value = value;
                    chartMenu.classList.remove('show');
                    chartBtn.setAttribute('aria-expanded', 'false');
                }
            });

            // Sites Dropdown
            const sitesBtn = document.getElementById('sites-dropdown-btn');
            const sitesMenu = document.getElementById('sites-dropdown-menu');
            const sitesSearch = document.getElementById('sites-search');
            const allSitesCheckbox = document.getElementById('all-sites');

            sitesBtn.addEventListener('click', function() {
                const isOpen = sitesMenu.classList.contains('show');
                closeAllDropdowns();
                if (!isOpen) {
                    sitesMenu.classList.add('show');
                    sitesBtn.setAttribute('aria-expanded', 'true');
                    sitesSearch.focus();
                }
            });

            sitesSearch.addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const options = sitesMenu.querySelectorAll('.dropdown-option');
                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    option.style.display = text.includes(filter) ? 'block' : 'none';
                });
            });

            // Handle "All Sites" logic
            allSitesCheckbox.addEventListener('change', function() {
                const siteCheckboxes = sitesMenu.querySelectorAll('input[name="sites[]"]');
                if (this.checked) {
                    siteCheckboxes.forEach(cb => cb.checked = false);
                }
                updateSitesDisplay();
            });

            sitesMenu.addEventListener('change', function(e) {
                if (e.target.name === 'sites[]') {
                    if (e.target.checked) {
                        allSitesCheckbox.checked = false;
                    }
                    updateSitesDisplay();
                }
            });

            function updateSitesDisplay() {
                const checkedSites = sitesMenu.querySelectorAll('input[name="sites[]"]:checked');
                const sitesText = document.getElementById('sites-selected-text');

                if (allSitesCheckbox.checked || checkedSites.length === 0) {
                    sitesText.textContent = 'All Sites';
                } else if (checkedSites.length === 1) {
                    const siteLabel = checkedSites[0].nextElementSibling.textContent;
                    sitesText.textContent = siteLabel;
                } else {
                    sitesText.textContent = `${checkedSites.length} sites selected`;
                }
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.searchable-dropdown')) {
                    closeAllDropdowns();
                }
            });
        }

        function closeAllDropdowns() {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
            document.querySelectorAll('.dropdown-button').forEach(btn => {
                btn.setAttribute('aria-expanded', 'false');
            });
        }

        function toggleCustomDateFields() {
            const timeRange = document.getElementById('time-range').value;
            const customFields = document.getElementById('custom-date-fields');

            if (timeRange === 'custom') {
                customFields.classList.add('show');
            } else {
                customFields.classList.remove('show');
            }
        }

        function showExportButton() {
            document.getElementById('exportBtn').style.display = 'inline-block';
        }

        function showExportModal() {
            document.getElementById('exportModal').style.display = 'block';
        }

        function closeExportModal() {
            document.getElementById('exportModal').style.display = 'none';
        }

        function selectExportOption(type) {
            document.getElementById(type).checked = true;
        }

        function exportChart() {
            const selectedOption = document.querySelector('input[name="export-type"]:checked').value;

            if (selectedOption === 'chart-stats') {
                if (typeof html2canvas !== 'undefined') {
                    exportChartWithStats();
                } else {
                    alert('For chart+stats export, html2canvas library is needed. Exported chart only.');
                    exportChartOnly();
                }
            } else {
                exportChartOnly();
            }

            closeExportModal();
        }

        function exportChartWithStats() {
            const chartSection = document.getElementById('chart-section');

            html2canvas(chartSection, {
                backgroundColor: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#1f2937' : '#ffffff',
                scale: 2,
                useCORS: true
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'chart-with-stats-' + new Date().toISOString().split('T')[0] + '.png';
                link.href = canvas.toDataURL();
                link.click();
            }).catch(error => {
                console.error('Export failed:', error);
                alert('Export failed. Trying chart only export.');
                exportChartOnly();
            });
        }

        function exportChartOnly() {
            if (currentChart) {
                const link = document.createElement('a');
                link.download = 'chart-' + new Date().toISOString().split('T')[0] + '.png';
                link.href = currentChart.toBase64Image();
                link.click();
            }
        }

        function initChart() {
            const ctx = document.getElementById('analyticsChart').getContext('2d');
            const chartData = <?php echo json_encode($data ?? [], 15, 512) ?>;

            if (currentChart) {
                currentChart.destroy();
            }

            // Use colors from the controller if available, otherwise fall back to defaults
            const backgroundColors = chartData.backgroundColors || chartData.colors || [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56'
            ];

            const borderColors = chartData.colors || [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56'
            ];

            let datasets;

            // Handle different chart types with proper color application
            if (chartData.datasets) {
                // For charts with predefined datasets (like monthly comparison)
                datasets = chartData.datasets;
            } else {
                // For single dataset charts - apply dynamic colors
                datasets = [{
                    label: chartData.title,
                    data: chartData.values,
                    backgroundColor: chartData.type === 'line'
                        ? 'rgba(99, 102, 241, 0.2)'
                        : backgroundColors,
                    borderColor: chartData.type === 'line'
                        ? '#6366f1'
                        : (chartData.type === 'doughnut' || chartData.type === 'pie')
                            ? borderColors
                            : '#6366f1',
                    borderWidth: chartData.type === 'line' ? 3 :
                        (chartData.type === 'doughnut' || chartData.type === 'pie') ? 2 : 1,
                    fill: chartData.type === 'line',
                    tension: chartData.type === 'line' ? 0.4 : undefined,
                    // Additional styling for better appearance
                    hoverBackgroundColor: chartData.type === 'doughnut' || chartData.type === 'pie'
                        ? backgroundColors.map(color => color.replace(/[\d\.]+\)$/g, '0.9)'))
                        : undefined,
                    hoverBorderWidth: chartData.type === 'doughnut' || chartData.type === 'pie' ? 3 : undefined
                }];
            }

            const config = {
                type: chartData.type,
                data: {
                    labels: chartData.labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: ['doughnut', 'pie', 'radar', 'bar'].includes(chartData.type),
                            position: chartData.type === 'bar' ? 'top' : 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                },
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#e5e7eb' : '#374151'
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(17, 24, 39, 0.95)',
                            titleColor: '#f9fafb',
                            bodyColor: '#f9fafb',
                            borderColor: '#6366f1',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }

                                    // Add percentage for pie/doughnut charts
                                    if (['pie', 'doughnut'].includes(chartData.type)) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.raw / total) * 100).toFixed(1);
                                        label += `${context.raw} (${percentage}%)`;
                                    } else {
                                        label += context.raw;
                                    }

                                    return label;
                                }
                            }
                        }
                    },
                    scales: ['doughnut', 'pie'].includes(chartData.type) ? {} : {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#9ca3af' : '#6b7280'
                            },
                            grid: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#374151' : '#e5e7eb'
                            }
                        },
                        x: {
                            ticks: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#9ca3af' : '#6b7280'
                            },
                            grid: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#374151' : '#e5e7eb'
                            }
                        }
                    },
                    // Animation settings
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                    // Interaction settings
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            };

            // Special handling for radar charts
            if (chartData.type === 'radar') {
                config.options.scales = {
                    r: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#9ca3af' : '#6b7280'
                        },
                        grid: {
                            color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#374151' : '#e5e7eb'
                        },
                        pointLabels: {
                            color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#e5e7eb' : '#374151',
                            font: {
                                size: 11
                            }
                        }
                    }
                };
            }

            currentChart = new Chart(ctx, config);
        }

        // Auto-update from date when to date changes for better UX
        document.getElementById('custom-to')?.addEventListener('change', function() {
            const fromDate = document.getElementById('custom-from');
            if (!fromDate.value && this.value) {
                // Set from date to 30 days before to date
                const toDate = new Date(this.value);
                const fromDateValue = new Date(toDate.getTime() - (30 * 24 * 60 * 60 * 1000));
                fromDate.value = fromDateValue.toISOString().split('T')[0];
            }
        });

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('exportModal');
            if (event.target === modal) {
                closeExportModal();
            }
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
<?php /**PATH /home/sites/29a/4/4aab0e4a5e/public_html/laravel12/resources/views/charts/complaints.blade.php ENDPATH**/ ?>