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
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --success-color: #16a34a;
            --warning-color: #ea580c;
            --danger-color: #dc2626;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
        }

        .dashboard-container {
            min-height: 100vh;
            background-color: var(--gray-50);
            padding: 24px 16px;
            overflow-x: hidden;
        }

        .dashboard-wrapper {
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
        }

        /* Welcome Section - Cleaner Design */
        .welcome-section {
            background: var(--white);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
        }

        .welcome-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .welcome-content {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
        }

        .welcome-avatar {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 20px;
            font-weight: 600;
        }

        .welcome-text h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0 0 4px 0;
        }

        .welcome-text p {
            color: var(--gray-600);
            margin: 0;
            font-size: 14px;
        }

        .welcome-controls {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
        }

        .welcome-time {
            text-align: right;
            color: var(--gray-500);
            font-size: 14px;
            line-height: 1.4;
            margin-right: 8px;
        }

        /* Enhanced Toggle Control Styles with White Icons in Dark Mode */
        .dashboard-toggle {
            background: var(--white);
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            padding: 4px;
            display: flex;
            gap: 4px;
            box-shadow: var(--shadow-sm);
            position: relative;
        }

        /* Hidden toggle when only one feature is available */
        .dashboard-toggle.hidden {
            display: none;
        }

        .toggle-option {
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--gray-600);
            background: transparent;
            border: none;
            min-width: 120px;
            text-align: center;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-option.active {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: var(--white);
            box-shadow: var(--shadow-lg);
            transform: translateY(-1px);
        }

        .toggle-option:hover:not(.active) {
            color: var(--gray-700);
            background: var(--gray-50);
            transform: translateY(-1px);
        }

        .toggle-option:active {
            transform: translateY(0);
        }

        /* Icons for toggle options */
        .toggle-option::before {
            content: '';
            display: inline-block;
            width: 16px;
            height: 16px;
            margin-right: 8px;
            background-size: contain;
            vertical-align: middle;
        }

        .toggle-option[data-view="incidents"]::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23374151'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'/%3E%3C/svg%3E");
        }

        .toggle-option[data-view="complaints"]::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23374151'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'/%3E%3C/svg%3E");
        }

        .toggle-option.active[data-view="incidents"]::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23ffffff'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'/%3E%3C/svg%3E");
        }

        .toggle-option.active[data-view="complaints"]::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23ffffff'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'/%3E%3C/svg%3E");
        }

        .reset-layout-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: var(--gray-100);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .reset-layout-btn:hover {
            background: var(--gray-200);
            border-color: var(--gray-400);
        }

        .reset-layout-btn svg {
            width: 16px;
            height: 16px;
        }

        /* Swapy Container - Responsive Grid */
        .swapy-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 32px;
            width: 100%;
        }

        .swapy-slot {
            min-height: 200px;
        }

        /* Individual widget styling - Enhanced for dragging, disabled on mobile */
        .swapy-item {
            height: 100%;
            cursor: grab;
            touch-action: none;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        .swapy-item:active {
            cursor: grabbing;
        }

        .swapy-item.is-dragging {
            opacity: 0.5;
            z-index: 1000;
            transform: rotate(5deg);
        }

        .swapy-slot.is-drag-over {
            background-color: rgba(37, 99, 235, 0.1);
            border: 2px dashed var(--primary-color);
            border-radius: 12px;
        }

        /* Stats Grid - individual items */
        .stat-card {
            background: var(--white);
            border-radius: 12px;
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            height: 100%;
            cursor: grab;
            touch-action: none;
            transition: all 0.3s ease;
        }

        .stat-card:active {
            cursor: grabbing;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .stat-icon.green {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .stat-icon.orange {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .stat-icon.red {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .stat-icon svg {
            width: 24px;
            height: 24px;
            color: var(--white);
        }

        .stat-trend {
            font-size: 12px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 12px;
            background: var(--gray-100);
            color: var(--gray-600);
        }

        .stat-content h3 {
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 8px 0;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0 0 4px 0;
        }

        .stat-description {
            font-size: 12px;
            color: var(--gray-500);
            margin: 0;
        }

        /* Chart Cards */
        .chart-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            height: 100%;
            cursor: grab;
            touch-action: none;
            transition: all 0.3s ease;
        }

        .chart-card:active {
            cursor: grabbing;
        }

        .chart-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .chart-header {
            padding: 24px 24px 0 24px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 4px 0;
        }

        .chart-subtitle {
            font-size: 14px;
            color: var(--gray-500);
            margin: 0 0 24px 0;
        }

        .chart-body {
            padding: 0 24px 24px 24px;
        }

        /* Chart.js container with responsive design */
        .line-chart-container {
            position: relative;
            height: 300px;
            width: 100%;
            overflow: hidden;
        }

        .line-chart-container canvas {
            max-width: 100%;
            height: auto !important;
        }

        /* Large chart spans 3 columns */
        .chart-large {
            grid-column: span 3;
        }

        /* Small chart spans 1 column */
        .chart-small {
            grid-column: span 1;
        }

        /* Table spans full width */
        .table-full {
            grid-column: span 4;
        }

        /* Doughnut Chart */
        .doughnut-chart {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .doughnut-svg {
            transform: rotate(-90deg);
            width: 100%;
            height: 100%;
        }

        .doughnut-background {
            fill: none;
            stroke: var(--gray-100);
            stroke-width: 30;
        }

        .doughnut-segment {
            fill: none;
            stroke-width: 30;
            transition: all 0.3s ease;
            stroke-linecap: round;
        }

        .doughnut-segment:hover {
            stroke-width: 35;
            filter: brightness(1.1);
        }

        .doughnut-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .doughnut-total {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
            line-height: 1;
        }

        .doughnut-label {
            font-size: 12px;
            color: var(--gray-500);
            margin: 0;
            margin-top: 2px;
            line-height: 1;
        }

        .legend {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 2px;
            flex-shrink: 0;
        }

        /* Table Section */
        .table-section {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            cursor: grab;
            touch-action: none;
            transition: all 0.3s ease;
        }

        .table-section:active {
            cursor: grabbing;
        }

        .table-section:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .table-header {
            padding: 24px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 4px 0;
        }

        .table-subtitle {
            font-size: 14px;
            color: var(--gray-500);
            margin: 0;
        }

        .table-action {
            background: var(--primary-color);
            color: var(--white);
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .table-action:hover {
            background: var(--primary-hover);
        }

        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .reports-table {
            width: 100%;
            border-collapse: collapse;
        }

        .reports-table th {
            background: var(--gray-50);
            padding: 16px 24px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--gray-200);
        }

        .reports-table td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--gray-100);
            font-size: 14px;
            color: var(--gray-900);
        }

        .reports-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .reports-table tbody tr:hover {
            background: var(--gray-50);
        }

        .reports-table tbody tr:last-child td {
            border-bottom: none;
        }

        .customer-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .customer-avatar {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 600;
            font-size: 14px;
        }

        .customer-info h4 {
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-900);
            margin: 0 0 2px 0;
        }

        .customer-info p {
            font-size: 12px;
            color: var(--gray-500);
            margin: 0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.closed {
            background: #dcfce7;
            color: #166534;
        }

        .status-badge.open {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .status-dot.closed {
            background: var(--success-color);
        }

        .status-dot.open {
            background: var(--warning-color);
        }

        .status-dot.pending {
            background: var(--warning-color);
        }

        .action-btn {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .action-btn:hover {
            background: var(--gray-200);
            border-color: var(--gray-400);
        }

        .empty-state {
            padding: 64px 24px;
            text-align: center;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            background: var(--gray-100);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .empty-icon svg {
            width: 32px;
            height: 32px;
            color: var(--gray-400);
        }

        .empty-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 8px 0;
        }

        .empty-description {
            font-size: 14px;
            color: var(--gray-500);
            margin: 0 0 24px 0;
        }

        .empty-action {
            background: var(--primary-color);
            color: var(--white);
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .empty-action:hover {
            background: var(--primary-hover);
        }

        /* RESPONSIVE DESIGN - COMPLETE OVERHAUL */

        /* Desktop Large (1200px+) */
        @media (max-width: 1200px) {
            .swapy-container {
                grid-template-columns: repeat(3, 1fr);
            }

            .chart-large {
                grid-column: span 2;
            }

            .table-full {
                grid-column: span 3;
            }
        }

        /* Desktop Medium (1024px) */
        @media (max-width: 1024px) {
            .dashboard-container {
                padding: 20px 12px;
            }

            .swapy-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .chart-large {
                grid-column: span 2;
            }

            .chart-small {
                grid-column: span 2;
            }

            .table-full {
                grid-column: span 2;
            }

            .welcome-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .welcome-controls {
                justify-content: space-between;
                width: 100%;
            }

            .line-chart-container {
                height: 250px;
            }
        }

        /* Tablet (768px) */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 16px 10px;
            }

            .welcome-section {
                padding: 18px;
            }

            .welcome-header {
                gap: 12px;
            }

            .welcome-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .welcome-avatar {
                width: 48px;
                height: 48px;
                font-size: 18px;
            }

            .welcome-text h1 {
                font-size: 22px;
            }

            .welcome-controls {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .dashboard-toggle {
                width: 100%;
            }

            .toggle-option {
                flex: 1;
                min-width: auto;
                padding: 8px 12px;
                font-size: 13px;
            }

            .reset-layout-btn {
                align-self: flex-start;
                padding: 8px 12px;
                font-size: 13px;
            }

            .welcome-time {
                text-align: left;
                margin-right: 0;
            }

            .swapy-container {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .chart-large,
            .chart-small,
            .table-full {
                grid-column: span 1;
            }

            .stat-card {
                padding: 18px;
            }

            .chart-header,
            .chart-body,
            .table-header {
                padding: 16px;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .reports-table th,
            .reports-table td {
                padding: 12px 16px;
            }

            .line-chart-container {
                height: 220px;
            }

            .doughnut-chart {
                width: 170px;
                height: 170px;
            }
        }

        /* Mobile Large (600px) - DISABLE DRAG & DROP */
        @media (max-width: 600px) {
            .dashboard-container {
                padding: 12px 8px;
                overflow-x: hidden;
            }

            .dashboard-wrapper {
                overflow-x: hidden;
            }

            .welcome-section {
                padding: 16px;
                margin-bottom: 16px;
            }

            .welcome-text h1 {
                font-size: 20px;
            }

            .toggle-option {
                padding: 6px 10px;
                font-size: 12px;
            }

            .reset-layout-btn {
                padding: 6px 10px;
                font-size: 12px;
            }

            /* DISABLE DRAG AND DROP ON MOBILE */
            .swapy-item {
                cursor: default !important;
                touch-action: auto !important;
            }

            .swapy-item:active {
                cursor: default !important;
            }

            .stat-card {
                cursor: default !important;
                touch-action: auto !important;
            }

            .stat-card:active {
                cursor: default !important;
            }

            .stat-card:hover {
                transform: none !important;
            }

            .chart-card {
                cursor: default !important;
                touch-action: auto !important;
                overflow-x: auto;
            }

            .chart-card:active {
                cursor: default !important;
            }

            .chart-card:hover {
                transform: none !important;
            }

            .table-section {
                cursor: default !important;
                touch-action: auto !important;
            }

            .table-section:active {
                cursor: default !important;
            }

            .table-section:hover {
                transform: none !important;
            }

            .swapy-container {
                gap: 12px;
            }

            .stat-card {
                padding: 16px;
            }

            .chart-header {
                padding: 12px;
            }

            .chart-body {
                padding: 0 12px 12px 12px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .chart-title {
                font-size: 16px;
            }

            .chart-subtitle {
                font-size: 12px;
            }

            /* MAKE CHARTS SCROLL HORIZONTALLY ON MOBILE */
            .line-chart-container {
                height: 200px;
                min-width: 400px;
                overflow-x: auto;
            }

            .doughnut-chart {
                width: 150px;
                height: 150px;
            }

            .table-header {
                padding: 12px;
            }

            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .reports-table {
                min-width: 500px;
                font-size: 12px;
            }

            .reports-table th,
            .reports-table td {
                padding: 8px 12px;
                font-size: 11px;
            }

            .customer-avatar {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }
        }

        /* Mobile Medium (500px) */
        @media (max-width: 500px) {
            .dashboard-container {
                padding: 8px 4px;
            }

            .welcome-section {
                padding: 12px;
            }

            .welcome-text h1 {
                font-size: 18px;
            }

            .welcome-avatar {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .stat-card {
                padding: 14px;
            }

            .stat-value {
                font-size: 28px;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
            }

            .stat-icon svg {
                width: 20px;
                height: 20px;
            }

            .line-chart-container {
                height: 180px;
                min-width: 350px;
            }

            .doughnut-chart {
                width: 130px;
                height: 130px;
            }

            .doughnut-total {
                font-size: 18px;
            }

            .doughnut-label {
                font-size: 10px;
            }

            .reports-table {
                min-width: 450px;
            }
        }

        /* Mobile Small (400px) */
        @media (max-width: 400px) {
            .dashboard-container {
                padding: 6px 2px;
            }

            .welcome-section {
                padding: 10px;
                border-radius: 8px;
            }

            .welcome-text h1 {
                font-size: 16px;
            }

            .welcome-avatar {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }

            .stat-card {
                padding: 12px;
                border-radius: 8px;
            }

            .stat-value {
                font-size: 24px;
            }

            .chart-card {
                border-radius: 8px;
            }

            .line-chart-container {
                height: 160px;
                min-width: 320px;
            }

            .doughnut-chart {
                width: 110px;
                height: 110px;
            }

            .table-section {
                border-radius: 8px;
            }

            .reports-table {
                min-width: 400px;
            }
        }

        /* Mobile Extra Small (300px) */
        @media (max-width: 300px) {
            .dashboard-container {
                padding: 4px 1px;
            }

            .welcome-section {
                padding: 8px;
            }

            .welcome-text h1 {
                font-size: 14px;
            }

            .stat-card {
                padding: 10px;
            }

            .stat-value {
                font-size: 20px;
            }

            .line-chart-container {
                height: 140px;
                min-width: 280px;
            }

            .doughnut-chart {
                width: 90px;
                height: 90px;
            }

            .reports-table {
                min-width: 350px;
            }
        }

        /* Dark mode support with proper toggle icons */
        @media (prefers-color-scheme: dark) {
            :root {
                --gray-50: #111827;
                --gray-100: #1f2937;
                --gray-200: #374151;
                --gray-300: #4b5563;
                --gray-400: #6b7280;
                --gray-500: #9ca3af;
                --gray-600: #d1d5db;
                --gray-700: #e5e7eb;
                --gray-800: #f3f4f6;
                --gray-900: #f9fafb;
                --white: #1f2937;
            }

            .dashboard-container {
                background-color: #111827;
            }

            .status-badge.closed {
                background: rgba(34, 197, 94, 0.2);
                color: #4ade80;
            }

            .status-badge.open {
                background: rgba(245, 158, 11, 0.2);
                color: #fbbf24;
            }

            .legend-item {
                color: #ffffff;
            }

            /* Dark mode toggle icons - WHITE when active */
            .toggle-option[data-view="incidents"]::before {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23d1d5db'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'/%3E%3C/svg%3E");
            }

            .toggle-option[data-view="complaints"]::before {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23d1d5db'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'/%3E%3C/svg%3E");
            }

            .reset-layout-btn {
                background: var(--gray-700);
                color: var(--gray-300);
                border-color: var(--gray-600);
            }

            .reset-layout-btn:hover {
                background: var(--gray-600);
                border-color: var(--gray-500);
            }
        }

        /* Touch-friendly targets */
        @media (hover: none) and (pointer: coarse) {
            .reset-layout-btn,
            .toggle-option {
                min-height: 44px;
                touch-action: manipulation;
            }

            .table-action,
            .action-btn,
            .empty-action {
                min-height: 44px;
                touch-action: manipulation;
            }
        }

        /* Ensure horizontal scroll works properly */
        .chart-body {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Prevent body horizontal scroll on mobile */
        @media (max-width: 600px) {
            body {
                overflow-x: hidden;
            }

            .dashboard-container,
            .dashboard-wrapper {
                max-width: 100vw;
                overflow-x: hidden;
            }
        }
    </style>

    <div class="dashboard-container">
        <div class="dashboard-wrapper">
            <!-- Welcome Section - Cleaner Design -->
            <div class="welcome-section">
                <div class="welcome-header">
                    <div class="welcome-content">
                        <div class="welcome-avatar">
                            <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                        </div>
                        <div class="welcome-text">
                            <h1>Welcome back, <?php echo e(auth()->user()->name); ?>!</h1>
                            <p><?php echo e(now()->format('l, F j, Y')); ?></p>
                        </div>
                    </div>
                    <div class="welcome-controls">
                        <div class="welcome-time">
                            <div><?php echo e(now()->format('g:i A')); ?></div>
                            <div>Dashboard Overview</div>
                        </div>

                        
                        <?php if($canAccessComplaints && $canAccessIncidents): ?>
                            <div class="dashboard-toggle">
                                <button class="toggle-option active" data-view="incidents">
                                    Accident Reports
                                </button>
                                <button class="toggle-option" data-view="complaints">
                                    Complaints
                                </button>
                            </div>
                        <?php elseif($canAccessIncidents && !$canAccessComplaints): ?>
                            
                            <div class="dashboard-toggle">
                                <button class="toggle-option active" data-view="incidents" disabled style="cursor: default;">
                                    Accident Reports
                                </button>
                            </div>
                        <?php elseif($canAccessComplaints && !$canAccessIncidents): ?>
                            
                            <div class="dashboard-toggle">
                                <button class="toggle-option active" data-view="complaints" disabled style="cursor: default;">
                                    Complaints
                                </button>
                            </div>
                        <?php endif; ?>

                        <button id="resetLayoutBtn" class="reset-layout-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Layout
                        </button>
                    </div>
                </div>
            </div>

            <!-- Swapy Container for Draggable Widgets -->
            <div class="swapy-container" id="swapyContainer">
                <!-- Stat Cards -->
                <div class="swapy-slot" data-swapy-slot="stat1">
                    <div class="swapy-item stat-card" data-swapy-item="stat1">
                        <div class="stat-header">
                            <div class="stat-icon blue">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div class="stat-trend">This Month</div>
                        </div>
                        <div class="stat-content">
                            <h3 id="monthlyStatLabel">
                                <?php if($canAccessIncidents && !$canAccessComplaints): ?>
                                    Monthly Accident Reports
                                <?php elseif($canAccessComplaints && !$canAccessIncidents): ?>
                                    Monthly Complaints
                                <?php else: ?>
                                    Monthly Accident Reports
                                <?php endif; ?>
                            </h3>
                            <div class="stat-value" id="monthlyStatValue">
                                <?php if($canAccessIncidents && !$canAccessComplaints): ?>
                                    <?php echo e($monthlyIncidents); ?>

                                <?php elseif($canAccessComplaints && !$canAccessIncidents): ?>
                                    <?php echo e($monthlyComplaints); ?>

                                <?php else: ?>
                                    <?php echo e($monthlyIncidents); ?>

                                <?php endif; ?>
                            </div>
                            <p class="stat-description"><?php echo e(now()->format('F Y')); ?></p>
                        </div>
                    </div>
                </div>

                <div class="swapy-slot" data-swapy-slot="stat2">
                    <div class="swapy-item stat-card" data-swapy-item="stat2">
                        <div class="stat-header">
                            <div class="stat-icon green">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="stat-trend" id="resolutionTrend">
                                <?php
                                    $avgTime = ($canAccessIncidents && !$canAccessComplaints) ? $averageClosureTimeIncidents :
                                              (($canAccessComplaints && !$canAccessIncidents) ? $averageClosureTime : $averageClosureTimeIncidents);
                                ?>
                                <?php echo e($avgTime == 0 ? 'No Data' : 'Average'); ?>

                            </div>
                        </div>
                        <div class="stat-content">
                            <h3>Resolution Time</h3>
                            <div class="stat-value" id="resolutionValue"><?php echo e($avgTime); ?></div>
                            <p class="stat-description" id="resolutionDesc"><?php echo e($avgTime == 1 ? 'day' : 'days'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="swapy-slot" data-swapy-slot="stat3">
                    <div class="swapy-item stat-card" data-swapy-item="stat3">
                        <div class="stat-header">
                            <div class="stat-icon orange">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="stat-trend">Active</div>
                        </div>
                        <div class="stat-content">
                            <h3 id="openStatLabel">
                                <?php if($canAccessIncidents && !$canAccessComplaints): ?>
                                    Open Accident Reports
                                <?php elseif($canAccessComplaints && !$canAccessIncidents): ?>
                                    Open Complaints
                                <?php else: ?>
                                    Open Accident Reports
                                <?php endif; ?>
                            </h3>
                            <div class="stat-value" id="openStatValue">
                                <?php if($canAccessIncidents && !$canAccessComplaints): ?>
                                    <?php echo e($openIncidents); ?>

                                <?php elseif($canAccessComplaints && !$canAccessIncidents): ?>
                                    <?php echo e($openComplaints); ?>

                                <?php else: ?>
                                    <?php echo e($openIncidents); ?>

                                <?php endif; ?>
                            </div>
                            <p class="stat-description">need attention</p>
                        </div>
                    </div>
                </div>

                <div class="swapy-slot" data-swapy-slot="stat4">
                    <div class="swapy-item stat-card" data-swapy-item="stat4">
                        <div class="stat-header">
                            <div class="stat-icon red">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="stat-trend">Resolved</div>
                        </div>
                        <div class="stat-content">
                            <h3 id="closedStatLabel">
                                <?php if($canAccessIncidents && !$canAccessComplaints): ?>
                                    Closed Accident Reports
                                <?php elseif($canAccessComplaints && !$canAccessIncidents): ?>
                                    Closed Complaints
                                <?php else: ?>
                                    Closed Accident Reports
                                <?php endif; ?>
                            </h3>
                            <div class="stat-value" id="closedStatValue">
                                <?php if($canAccessIncidents && !$canAccessComplaints): ?>
                                    <?php echo e($closedIncidents); ?>

                                <?php elseif($canAccessComplaints && !$canAccessIncidents): ?>
                                    <?php echo e($closedComplaints); ?>

                                <?php else: ?>
                                    <?php echo e($closedIncidents); ?>

                                <?php endif; ?>
                            </div>
                            <p class="stat-description">total resolved</p>
                        </div>
                    </div>
                </div>

                <!-- Line Chart -->
                <div class="swapy-slot chart-large" data-swapy-slot="chart1">
                    <div class="swapy-item chart-card" data-swapy-item="chart1">
                        <div class="chart-header">
                            <h3 class="chart-title" id="chartTitle">
                                <?php if($canAccessIncidents && !$canAccessComplaints): ?>
                                    Incident Reports Trend
                                <?php elseif($canAccessComplaints && !$canAccessIncidents): ?>
                                    Complaints Trend
                                <?php else: ?>
                                    Accident Reports Trend
                                <?php endif; ?>
                            </h3>
                            <p class="chart-subtitle">Monthly overview for the last 6 months</p>
                        </div>
                        <div class="chart-body">
                            <div class="line-chart-container">
                                <canvas id="reportsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doughnut Chart -->
                <div class="swapy-slot chart-small" data-swapy-slot="chart2">
                    <div class="swapy-item chart-card" data-swapy-item="chart2">
                        <div class="chart-header">
                            <h3 class="chart-title" id="doughnutTitle">
                                <?php if($canAccessIncidents && !$canAccessComplaints): ?>
                                    Accident Report Types
                                <?php elseif($canAccessComplaints && !$canAccessIncidents): ?>
                                    Complaint Types
                                <?php else: ?>
                                    Accident Report Types
                                <?php endif; ?>
                            </h3>
                            <p class="chart-subtitle" id="doughnutSubtitle">
                                <?php if($canAccessIncidents && !$canAccessComplaints): ?>
                                    Most common incident categories
                                <?php elseif($canAccessComplaints && !$canAccessIncidents): ?>
                                    Most common complaint categories
                                <?php else: ?>
                                    Most common incident categories
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="chart-body" id="doughnutChartBody">
                            <!-- This will be dynamically populated on page load -->
                        </div>
                    </div>
                </div>

                <!-- Recent Reports Table -->
                <div class="swapy-slot table-full" data-swapy-slot="table1">
                    <div class="swapy-item table-section" data-swapy-item="table1">
                        <div class="table-header">
                            <div>
                                <h3 class="table-title" id="tableTitle">
                                    <?php if($canAccessIncidents && !$canAccessComplaints): ?>
                                        Recent Accident Reports
                                    <?php elseif($canAccessComplaints && !$canAccessIncidents): ?>
                                        Recent Complaints
                                    <?php else: ?>
                                        Recent Accident Reports
                                    <?php endif; ?>
                                </h3>
                                <p class="table-subtitle" id="tableSubtitle">
                                    <?php if($canAccessIncidents && !$canAccessComplaints): ?>
                                        Latest Accident report submissions and their status
                                    <?php elseif($canAccessComplaints && !$canAccessIncidents): ?>
                                        Latest complaint submissions and their status
                                    <?php else: ?>
                                        Latest Accident report submissions and their status
                                    <?php endif; ?>
                                </p>
                            </div>
                            <a href="<?php if($canAccessIncidents && !$canAccessComplaints): ?><?php echo e(route('charts.incidents') ?? '#'); ?><?php elseif($canAccessComplaints && !$canAccessIncidents): ?><?php echo e(route('complaints.manage') ?? '#'); ?><?php else: ?><?php echo e(route('charts.incidents') ?? '#'); ?><?php endif; ?>" class="table-action" id="tableViewAllLink">
                                View All
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>

                        <div class="table-container">
                            <table class="reports-table">
                                <thead>
                                <tr id="tableHeaders">
                                    <th>Date</th>
                                    <th>Reporter</th>
                                    <th id="typeHeader">
                                        <?php if($canAccessIncidents && !$canAccessComplaints): ?>
                                            Incident Type
                                        <?php elseif($canAccessComplaints && !$canAccessIncidents): ?>
                                            Complaint Nature
                                        <?php else: ?>
                                            Accident Type
                                        <?php endif; ?>
                                    </th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                <!-- This will be dynamically populated on page load -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Swapy CDN -->
    <script src="https://unpkg.com/swapy/dist/swapy.min.js"></script>

    <script>
        // Global variables to store data for both views
        const dashboardData = {
            incidents: {
                monthly: <?php echo e($monthlyIncidents ?? 0); ?>,
                avgResolution: <?php echo e($averageClosureTimeIncidents ?? 0); ?>,
                open: <?php echo e($openIncidents ?? 0); ?>,
                closed: <?php echo e($closedIncidents ?? 0); ?>,
                trends: <?php echo json_encode($incidentTrends ?? [], 15, 512) ?>,
                types: <?php echo json_encode($incidentTypes ?? [], 15, 512) ?>,
                recent: <?php echo json_encode($recentIncidents ?? [], 15, 512) ?>
            },
            complaints: {
                monthly: <?php echo e($monthlyComplaints ?? 0); ?>,
                avgResolution: <?php echo e($averageClosureTime ?? 0); ?>,
                open: <?php echo e($openComplaints ?? 0); ?>,
                closed: <?php echo e($closedComplaints ?? 0); ?>,
                trends: <?php echo json_encode($complaintTrends ?? [], 15, 512) ?>,
                types: <?php echo json_encode($complaintTypes ?? [], 15, 512) ?>,
                recent: <?php echo json_encode($recentComplaints ?? [], 15, 512) ?>
            }
        };

        // User access permissions
        const userAccess = {
            canAccessIncidents: <?php echo e($canAccessIncidents ? 'true' : 'false'); ?>,
            canAccessComplaints: <?php echo e($canAccessComplaints ? 'true' : 'false'); ?>,
            hasToggle: <?php echo e(($canAccessIncidents && $canAccessComplaints) ? 'true' : 'false'); ?>

        };

        // Available routes (fallback to # if route doesn't exist)
        const routes = {
            incidents: {
                manage: '<?php echo e(route("charts.incidents") ?? "#"); ?>',
                create: '#',
                edit: function(id) { return '<?php echo e(url("/incidents")); ?>/' + id + '/edit'; }
            },
            complaints: {
                manage: '<?php echo e(route("complaints.manage") ?? "#"); ?>',
                create: '<?php echo e(route("complaints.create") ?? "#"); ?>',
                edit: function(id) { return '<?php echo e(route("complaints.edit", ":id") ?? "#"); ?>'.replace(':id', id); }
            }
        };

        // Determine initial view based on user access
        let currentView = 'incidents';
        if (userAccess.canAccessComplaints && !userAccess.canAccessIncidents) {
            currentView = 'complaints';
        }

        let currentChart = null;

        // Check if screen is mobile (less than 600px)
        function isMobile() {
            return window.innerWidth < 600;
        }

        // Initialize everything when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeToggleSystem();
            initializeDashboard();

            // Always initialize dashboard view (important for single-feature users)
            initializeDashboardView();

            // Listen for window resize to handle mobile/desktop transitions
            window.addEventListener('resize', function() {
                if (typeof window.currentSwapy !== 'undefined') {
                    initializeSwapySystem();
                }
            });
        });

        function initializeToggleSystem() {
            // Only initialize toggle if user has access to both features
            if (!userAccess.hasToggle) {
                return;
            }

            const toggleOptions = document.querySelectorAll('.toggle-option');

            toggleOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const view = this.getAttribute('data-view');

                    // Check if user can access this view
                    if (view === 'incidents' && !userAccess.canAccessIncidents) {
                        console.warn('User cannot access incidents');
                        return;
                    }
                    if (view === 'complaints' && !userAccess.canAccessComplaints) {
                        console.warn('User cannot access complaints');
                        return;
                    }

                    // Update active state with smooth transition
                    toggleOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');

                    // Switch view
                    currentView = view;
                    updateDashboardView();
                });
            });
        }

        // Initialize dashboard view for single-feature users
        function initializeDashboardView() {
            // Initialize doughnut chart with current data
            updateDoughnutChart();

            // Initialize table with current data
            updateTableData();
        }

        function updateDashboardView() {
            // Only run dynamic updates if user has toggle enabled
            if (!userAccess.hasToggle) {
                return;
            }

            const data = dashboardData[currentView];
            const isIncidents = currentView === 'incidents';

            // Update stat cards
            document.getElementById('monthlyStatLabel').textContent = isIncidents ? 'Monthly Accident Reports' : 'Monthly Complaints';
            document.getElementById('monthlyStatValue').textContent = data.monthly;

            document.getElementById('resolutionTrend').textContent = data.avgResolution == 0 ? 'No Data' : 'Average';
            document.getElementById('resolutionValue').textContent = data.avgResolution;
            document.getElementById('resolutionDesc').textContent = data.avgResolution == 1 ? 'day' : 'days';

            document.getElementById('openStatLabel').textContent = isIncidents ? 'Open Accident Reports' : 'Open Complaints';
            document.getElementById('openStatValue').textContent = data.open;

            document.getElementById('closedStatLabel').textContent = isIncidents ? 'Closed Accident Reports' : 'Closed Complaints';
            document.getElementById('closedStatValue').textContent = data.closed;

            // Update chart titles
            document.getElementById('chartTitle').textContent = isIncidents ? 'Accident Reports Trend' : 'Complaints Trend';
            document.getElementById('doughnutTitle').textContent = isIncidents ? 'Accident Report Types' : 'Complaint Types';
            document.getElementById('doughnutSubtitle').textContent = isIncidents ? 'Most common incident categories' : 'Most common complaint categories';

            // Update table
            document.getElementById('tableTitle').textContent = isIncidents ? 'Recent Accident Reports' : 'Recent Complaints';
            document.getElementById('tableSubtitle').textContent = isIncidents ? 'Latest Accident report submissions and their status' : 'Latest complaint submissions and their status';
            document.getElementById('typeHeader').textContent = isIncidents ? 'Accident Type' : 'Complaint Nature';

            // Update table action link with proper route
            const tableLink = document.getElementById('tableViewAllLink');
            tableLink.href = routes[currentView].manage;

            // Update chart
            updateChart();

            // Update doughnut chart
            updateDoughnutChart();

            // Update table data
            updateTableData();
        }

        function updateChart() {
            const data = dashboardData[currentView];

            if (currentChart && data.trends) {
                // Update existing chart
                const labels = data.trends.map(item => item.month);
                const chartData = data.trends.map(item => item.count);

                currentChart.data.labels = labels;
                currentChart.data.datasets[0].data = chartData;
                currentChart.data.datasets[0].label = currentView === 'Accidents' ? 'Accident Reports' : 'Complaints';
                currentChart.update('active');
            }
        }

        function updateDoughnutChart() {
            const data = dashboardData[currentView];
            const isIncidents = currentView === 'incidents';
            const chartBody = document.getElementById('doughnutChartBody');

            if (!data.types || data.types.length === 0) {
                chartBody.innerHTML = `
                    <div style="text-align: center; color: var(--gray-500); padding: 40px;">
                        <span>No ${isIncidents ? 'accident' : 'complaint'} data available</span>
                    </div>
                `;
                return;
            }

            const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
            const total = data.types.reduce((sum, type) => sum + type.count, 0);
            const radius = 70;
            const circumference = 2 * Math.PI * radius;

            let segments = '';
            let legendItems = '';
            let currentOffset = 0;

            data.types.forEach((type, index) => {
                const percentage = (type.count / total) * 100;
                const strokeLength = (percentage / 100) * circumference;
                const dashArray = `${strokeLength} ${circumference}`;
                const dashOffset = -currentOffset;
                currentOffset += strokeLength;
                const color = colors[index % colors.length];

                segments += `
                    <circle
                        class="doughnut-segment"
                        cx="100"
                        cy="100"
                        r="${radius}"
                        stroke="${color}"
                        stroke-dasharray="${dashArray}"
                        stroke-dashoffset="${dashOffset}"
                        fill="none"
                        stroke-width="30"
                        stroke-linecap="round">
                        <title>${type.nature}: ${type.count} (${percentage.toFixed(1)}%)</title>
                    </circle>
                `;

                legendItems += `
                    <div class="legend-item">
                        <div class="legend-color" style="background: ${color}"></div>
                        <span>${type.nature} (${type.count})</span>
                    </div>
                `;
            });

            chartBody.innerHTML = `
                <div class="doughnut-chart">
                    <svg class="doughnut-svg" width="200" height="200" viewBox="0 0 200 200">
                        <circle class="doughnut-background" cx="100" cy="100" r="${radius}" fill="none" stroke="var(--gray-100)" stroke-width="30" />
                        ${segments}
                    </svg>
                    <div class="doughnut-center">
                        <div class="doughnut-total">${total}</div>
                        <div class="doughnut-label">Total</div>
                    </div>
                </div>
                <div class="legend">
                    ${legendItems}
                </div>
            `;
        }

        function updateTableData() {
            const data = dashboardData[currentView];
            const isIncidents = currentView === 'incidents';
            const tableBody = document.getElementById('tableBody');

            if (!data.recent || data.recent.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="empty-title">No ${isIncidents ? 'accident reports' : 'complaints'} yet</h3>
                                <p class="empty-description">New ${isIncidents ? 'accident report' : 'complaint'} submissions will appear here</p>
                                <a href="${routes[currentView].create}" class="empty-action">
                                    Create First ${isIncidents ? 'Accident Report' : 'Complaint'}
                                </a>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            let tableRows = '';
            data.recent.forEach(report => {
                const rawDate = isIncidents
                    ? (report.date_of_occurrence || report.created_at)
                    : (report.date_received || report.created_at);

                const formattedDate = rawDate ? new Date(rawDate).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                }) : 'N/A';

                const timeAgo = rawDate ? formatTimeAgo(new Date(rawDate)) : '';

                const name = isIncidents
                    ? (report.reported_employee?.first_name || report.affected_employee?.first_name || 'Unknown')
                    : (report.name || 'Unknown');

                const identifier = isIncidents
                    ? (report.reported_employee?.employee_number || 'No ID')
                    : (report.pcn_number || 'No PCN');

                const type = isIncidents
                    ? (report.incident_type?.name || 'Not specified')
                    : (report.nature || 'Not specified');

                // Generate the appropriate edit URL based on the current view and report ID
                const editUrl = routes[currentView].edit(report.id);

                tableRows += `
                    <tr>
                        <td>
                            <div>
                                <strong>${formattedDate}</strong>
                            </div>
                            <div style="font-size: 12px; color: var(--gray-500);">
                                ${timeAgo}
                            </div>
                        </td>
                        <td>
                            <div class="customer-cell">
                                <div class="customer-avatar">
                                    ${name.charAt(0).toUpperCase()}
                                </div>
                                <div class="customer-info">
                                    <h4>${name}</h4>
                                    <p>${identifier}</p>
                                </div>
                            </div>
                        </td>
                        <td>${type}</td>
                        <td>
                            <span class="status-badge ${report.status || 'pending'}">
                                <span class="status-dot ${report.status || 'pending'}"></span>
                                ${(report.status || 'pending').charAt(0).toUpperCase() + (report.status || 'pending').slice(1)}
                            </span>
                        </td>
                        <td>
                            <a href="${editUrl}" class="action-btn">
                                View Details
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                `;
            });

            tableBody.innerHTML = tableRows;
        }

        function formatTimeAgo(date) {
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays === 1) return '1 day ago';
            if (diffDays < 7) return `${diffDays} days ago`;
            if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;
            return `${Math.floor(diffDays / 30)} months ago`;
        }

        function initializeDashboard() {
            // Initialize chart first
            initializeChart();

            // Then initialize Swapy and other features
            initializeSwapySystem();
        }

        function initializeChart() {
            const ctx = document.getElementById('reportsChart');
            if (!ctx) return;

            // Start with the appropriate data based on user access
            let initialData;
            let initialLabel;

            if (userAccess.canAccessIncidents && !userAccess.canAccessComplaints) {
                initialData = dashboardData.incidents;
                initialLabel = 'Incident Reports';
            } else if (userAccess.canAccessComplaints && !userAccess.canAccessIncidents) {
                initialData = dashboardData.complaints;
                initialLabel = 'Complaints';
            } else {
                initialData = dashboardData.incidents;
                initialLabel = 'Incident Reports';
            }

            currentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: initialData.trends.map(item => item.month),
                    datasets: [{
                        label: initialLabel,
                        data: initialData.trends.map(item => item.count),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.06)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.25,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2563eb',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: '#2563eb',
                        pointHoverBorderColor: '#ffffff',
                        pointHoverBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(31, 41, 55, 0.95)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#374151',
                            borderWidth: 1,
                            cornerRadius: 6,
                            displayColors: false,
                            callbacks: {
                                title: function(context) {
                                    return context[0].label;
                                },
                                label: function(context) {
                                    const label = currentView === 'incidents' ? 'incident report' : 'complaint';
                                    return `${context.parsed.y} ${label}${context.parsed.y !== 1 ? 's' : ''}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 12,
                                    weight: '500'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(229, 231, 235, 0.5)',
                                borderDash: [1, 1]
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 11
                                },
                                stepSize: 1
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    elements: {
                        point: {
                            radius: 3,
                            hoverRadius: 5,
                            hoverBorderWidth: 2
                        },
                        line: {
                            borderWidth: 2
                        }
                    }
                }
            });
        }

        // Function to manually apply layout from saved data
        function applyLayoutManually(layoutData) {
            const container = document.querySelector('#swapyContainer');
            if (!container) {
                console.error('Container not found');
                return;
            }

            // Create a map of current items by their data-swapy-item attribute
            const items = {};
            container.querySelectorAll('[data-swapy-item]').forEach(item => {
                const itemId = item.getAttribute('data-swapy-item');
                items[itemId] = item;
            });

            // Apply the layout by moving items to their saved positions
            Object.entries(layoutData).forEach(([slotId, itemId]) => {
                const slot = container.querySelector(`[data-swapy-slot="${slotId}"]`);
                const item = items[itemId];

                if (slot && item) {
                    slot.appendChild(item);
                } else {
                    console.warn(`Could not move item ${itemId} to slot ${slotId}`, {
                        slotExists: !!slot,
                        itemExists: !!item
                    });
                }
            });
        }

        function initializeSwapySystem() {
            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Disable drag and drop on mobile screens
            if (isMobile()) {
                disableDragOnMobile();
                setupResetButtonForMobile();
                return;
            }

            // Check if Swapy is loaded
            if (typeof Swapy === 'undefined') {
                console.error('Swapy library not loaded, falling back to basic functionality');
                initializeFallbackDragDrop();
                return;
            }

            // Initialize Swapy with error handling
            const container = document.querySelector('#swapyContainer');
            if (!container) {
                console.error('Swapy container not found');
                return;
            }

            let swapy;
            try {
                // Destroy existing swapy instance if it exists
                if (window.currentSwapy && typeof window.currentSwapy.destroy === 'function') {
                    window.currentSwapy.destroy();
                }

                // Initialize Swapy
                swapy = new Swapy(container, {
                    animation: 'dynamic'
                });

                // Store the swapy instance globally
                window.currentSwapy = swapy;

            } catch (error) {
                console.error('Error initializing Swapy:', error);
                initializeFallbackDragDrop();
                return;
            }

            // Load saved layout from server on page load
            <?php if(isset($dashboardLayout) && $dashboardLayout): ?>
                try {
                const savedLayout = <?php echo json_encode($dashboardLayout, 15, 512) ?>;

                if (savedLayout && typeof savedLayout === 'object' && Object.keys(savedLayout).length > 0) {
                    // Apply layout immediately after DOM is ready
                    setTimeout(() => {
                        applyLayoutManually(savedLayout);
                    }, 100);

                    // Also try Swapy's setData method if available
                    if (swapy && typeof swapy.setData === 'function') {
                        try {
                            setTimeout(() => {
                                swapy.setData(savedLayout);
                            }, 200);
                        } catch (e) {
                            // Silently fall back to manual layout
                        }
                    }
                }
            } catch (e) {
                console.error('Could not restore saved layout:', e);
            }
            <?php endif; ?>

            // Debounce function to prevent too many saves
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Function to create layout data from current DOM structure
            function createLayoutFromDOM() {
                const slots = container.querySelectorAll('[data-swapy-slot]');
                const layout = {};

                slots.forEach(slot => {
                    const slotId = slot.getAttribute('data-swapy-slot');
                    const item = slot.querySelector('[data-swapy-item]');
                    if (item) {
                        const itemId = item.getAttribute('data-swapy-item');
                        layout[slotId] = itemId;
                    }
                });

                return layout;
            }

            // Save layout to database when changed
            const saveLayout = debounce(function(data) {
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    return;
                }

                let layoutData = data;
                if (data === undefined || data === null) {
                    layoutData = createLayoutFromDOM();
                }

                if (typeof layoutData !== 'object') {
                    layoutData = {};
                }

                fetch('<?php echo e(route("dashboard.save-layout")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        layout_config: layoutData
                    })
                }).then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                }).catch(error => {
                    console.error('Error saving layout:', error);
                });
            }, 1000);

            // Listen for layout changes with error handling
            try {
                swapy.onSwap(({ data }) => {
                    saveLayout(data);
                });
            } catch (error) {
                console.error('Error setting up swap listener:', error);
            }

            // Reset layout button functionality
            setupResetButton(csrfToken);
        }

        function disableDragOnMobile() {
            const container = document.querySelector('#swapyContainer');
            if (!container) return;

            const items = container.querySelectorAll('.swapy-item');
            items.forEach(item => {
                item.draggable = false;
                item.style.cursor = 'default';
                item.style.touchAction = 'auto';
            });
        }

        function setupResetButtonForMobile() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            setupResetButton(csrfToken);
        }

        function setupResetButton(csrfToken) {
            document.getElementById('resetLayoutBtn').addEventListener('click', function() {
                if (confirm('Are you sure you want to reset the dashboard layout to default?')) {
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        return;
                    }

                    fetch('<?php echo e(route("dashboard.reset-layout")); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    }).then(result => {
                        window.location.reload();
                    }).catch(error => {
                        console.error('Error resetting layout:', error);
                        alert('Failed to reset layout. Please try again.');
                    });
                }
            });
        }

        function initializeFallbackDragDrop() {
            const container = document.querySelector('#swapyContainer');
            if (!container) return;

            if (isMobile()) {
                disableDragOnMobile();
                setupResetButtonForMobile();
                return;
            }

            // Desktop fallback drag and drop implementation
            const items = container.querySelectorAll('.swapy-item');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            function createLayoutFromDOM() {
                const slots = container.querySelectorAll('[data-swapy-slot]');
                const layout = {};

                slots.forEach(slot => {
                    const slotId = slot.getAttribute('data-swapy-slot');
                    const item = slot.querySelector('[data-swapy-item]');
                    if (item) {
                        const itemId = item.getAttribute('data-swapy-item');
                        layout[slotId] = itemId;
                    }
                });

                return layout;
            }

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            const saveLayout = debounce(function(data) {
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    return;
                }

                fetch('<?php echo e(route("dashboard.save-layout")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        layout_config: data
                    })
                }).catch(error => {
                    console.error('Error saving layout:', error);
                });
            }, 1000);

            // Load and apply saved layout for fallback mode
            <?php if(isset($dashboardLayout) && $dashboardLayout): ?>
                try {
                const savedLayout = <?php echo json_encode($dashboardLayout, 15, 512) ?>;

                if (savedLayout && typeof savedLayout === 'object' && Object.keys(savedLayout).length > 0) {
                    setTimeout(() => {
                        applyLayoutManually(savedLayout);
                    }, 100);
                }
            } catch (e) {
                console.error('Could not restore saved layout in fallback mode:', e);
            }
            <?php endif; ?>

            items.forEach(item => {
                item.draggable = true;

                item.addEventListener('dragstart', function(e) {
                    e.dataTransfer.setData('text/plain', item.getAttribute('data-swapy-item'));
                    item.classList.add('is-dragging');
                    item.style.opacity = '0.5';
                });

                item.addEventListener('dragend', function(e) {
                    item.classList.remove('is-dragging');
                    item.style.opacity = '1';

                    setTimeout(() => {
                        const layoutData = createLayoutFromDOM();
                        saveLayout(layoutData);
                    }, 100);
                });
            });

            const slots = container.querySelectorAll('.swapy-slot');

            slots.forEach(slot => {
                slot.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    slot.classList.add('is-drag-over');
                });

                slot.addEventListener('dragleave', function(e) {
                    slot.classList.remove('is-drag-over');
                });

                slot.addEventListener('drop', function(e) {
                    e.preventDefault();
                    slot.classList.remove('is-drag-over');

                    const itemId = e.dataTransfer.getData('text/plain');
                    const draggedItem = container.querySelector(`[data-swapy-item="${itemId}"]`);

                    if (draggedItem && slot) {
                        const currentItem = slot.querySelector('.swapy-item');
                        const draggedSlot = draggedItem.closest('.swapy-slot');

                        if (currentItem && draggedSlot && currentItem !== draggedItem) {
                            draggedSlot.appendChild(currentItem);
                            slot.appendChild(draggedItem);
                        } else if (draggedSlot && !currentItem) {
                            slot.appendChild(draggedItem);
                        }
                    }
                });
            });

            setupResetButton(csrfToken);
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
<?php /**PATH /home/sites/29a/4/4aab0e4a5e/public_html/laravel12/resources/views/dashboard.blade.php ENDPATH**/ ?>