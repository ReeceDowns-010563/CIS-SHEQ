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
            }

            .header-left {
                display: flex;
                align-items: center;
                gap: 1rem;
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
            }

            .back-button:hover {
                color: var(--primary-colour);
                border-color: var(--primary-colour);
                background-color: var(--secondary-colour);
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
                    background-color: var(--secondary-colour);
                }
            }

            .header-title {
                font-size: 1.125rem;
                font-weight: 600;
                color: #1e293b;
                margin: 0;
            }

            @media (prefers-color-scheme: dark) {
                .header-title {
                    color: #d1d5db;
                }
            }

            @media (max-width: 768px) {
                .header-container {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .header-left {
                    width: 100%;
                }
            }

            /* Modern branding form styles */
            .branding-container {
                min-height: calc(100vh - 200px);
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                padding: 2rem 1rem;
            }

            @media (prefers-color-scheme: dark) {
                .branding-container {
                    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                }
            }

            .main-wrapper {
                max-width: 1400px;
                margin: 0 auto;
            }

            .grid-layout {
                display: grid;
                grid-template-columns: 1fr 400px;
                gap: 2rem;
                align-items: start;
            }

            @media (max-width: 1024px) {
                .grid-layout {
                    grid-template-columns: 1fr;
                    gap: 1.5rem;
                }
            }

            /* Form card styling */
            .form-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 1rem;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                border: 1px solid rgba(255, 255, 255, 0.2);
                padding: 2rem;
                transition: all 0.3s ease;
            }

            @media (prefers-color-scheme: dark) {
                .form-card {
                    background: rgba(30, 41, 59, 0.95);
                    border-color: rgba(148, 163, 184, 0.1);
                    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
                }
            }

            .form-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 0.5rem;
            }

            .form-subtitle {
                color: #64748b;
                margin-bottom: 2rem;
                font-size: 0.875rem;
                line-height: 1.6;
            }

            @media (prefers-color-scheme: dark) {
                .form-title {
                    color: #f1f5f9;
                }

                .form-subtitle {
                    color: #94a3b8;
                }
            }

            /* Debug message styling */
            .debug-message {
                background: #fee2e2;
                border: 1px solid #fecaca;
                color: #dc2626;
                padding: 1rem 1.5rem;
                border-radius: 0.75rem;
                margin-bottom: 1.5rem;
                font-size: 0.875rem;
            }

            @media (prefers-color-scheme: dark) {
                .debug-message {
                    background: rgba(220, 38, 38, 0.1);
                    border-color: #dc2626;
                    color: #fca5a5;
                }
            }

            /* Input groups */
            .input-group {
                margin-bottom: 2rem;
            }

            .input-label {
                display: block;
                font-size: 0.875rem;
                font-weight: 600;
                color: #374151;
                margin-bottom: 0.75rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            @media (prefers-color-scheme: dark) {
                .input-label {
                    color: #d1d5db;
                }
            }

            .input-label-icon {
                width: 1rem;
                height: 1rem;
                color: var(--primary-colour);
            }

            /* Enhanced File Upload Dropzone */
            .file-dropzone {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
                border: 3px dashed #d1d5db;
                border-radius: 1rem;
                background: linear-gradient(135deg, #f8fafc, #ffffff);
                transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                min-height: 160px;
                overflow: hidden;
            }

            /* Normal hover state */
            .file-dropzone:hover {
                border-color: var(--primary-colour);
                background: linear-gradient(135deg, var(--primary-colour), var(--secondary-colour));
                transform: translateY(-3px);
                box-shadow:
                    0 20px 25px -5px rgba(0, 0, 0, 0.1),
                    0 10px 10px -5px rgba(0, 0, 0, 0.04),
                    0 0 0 1px var(--primary-colour);
            }

            /* Drag hover state - Premium styling */
            .file-dropzone.drag-hover {
                border-color: var(--primary-colour) !important;
                background: linear-gradient(135deg,
                var(--primary-colour),
                var(--secondary-colour)) !important;
                transform: scale(1.02) !important;
                box-shadow:
                    0 25px 50px -12px rgba(0, 0, 0, 0.25),
                    0 0 0 1px var(--primary-colour),
                    0 0 30px rgba(167, 98, 44, 0.4),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3) !important;
                border-style: solid !important;
                animation: pulseGlow 2s ease-in-out infinite alternate !important;
            }

            /* Keyframe animations for premium effect */
            @keyframes pulseGlow {
                0% {
                    box-shadow:
                        0 25px 50px -12px rgba(0, 0, 0, 0.25),
                        0 0 0 1px var(--primary-colour),
                        0 0 20px rgba(167, 98, 44, 0.3),
                        inset 0 1px 0 rgba(255, 255, 255, 0.3);
                }
                100% {
                    box-shadow:
                        0 25px 50px -12px rgba(0, 0, 0, 0.25),
                        0 0 0 1px var(--primary-colour),
                        0 0 40px rgba(167, 98, 44, 0.6),
                        inset 0 1px 0 rgba(255, 255, 255, 0.3);
                }
            }

            /* Content state management */
            .file-dropzone.drag-hover .dropzone-content {
                opacity: 0.1 !important;
                transform: scale(0.95) !important;
            }

            .file-dropzone.drag-hover .drop-message {
                opacity: 1 !important;
                transform: translate(-50%, -50%) scale(1.05) !important;
            }

            .file-dropzone:hover .dropzone-content {
                opacity: 0.2;
            }

            .file-dropzone:hover .hover-message {
                opacity: 1;
                transform: translate(-50%, -50%);
            }

            @media (prefers-color-scheme: dark) {
                .file-dropzone {
                    background: linear-gradient(135deg, #374151, #1f2937);
                    border-color: #4b5563;
                }

                .file-dropzone:hover {
                    border-color: var(--primary-colour);
                    background: linear-gradient(135deg, var(--primary-colour), var(--secondary-colour));
                }

                .file-dropzone.drag-hover {
                    background: linear-gradient(135deg,
                    var(--primary-colour),
                    var(--secondary-colour)) !important;
                }
            }

            .file-input {
                position: absolute;
                opacity: 0;
                width: 100%;
                height: 100%;
                cursor: pointer;
                z-index: 2;
            }

            .dropzone-content {
                text-align: center;
                color: #6b7280;
                pointer-events: none;
                transition: all 0.35s ease;
                z-index: 1;
            }

            @media (prefers-color-scheme: dark) {
                .dropzone-content {
                    color: #9ca3af;
                }
            }

            .dropzone-icon {
                width: 3rem;
                height: 3rem;
                margin: 0 auto 1rem;
                color: var(--primary-colour);
                transition: all 0.3s ease;
            }

            .current-image {
                max-height: 100px;
                max-width: 250px;
                object-fit: contain;
                border-radius: 0.75rem;
                box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.1);
                transition: all 0.35s ease;
            }

            /* Specific favicon sizing */
            .current-image.favicon-image {
                max-height: 96px !important;
                max-width: 96px !important;
            }

            /* Enhanced hover message */
            .hover-message {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) translateY(12px);
                opacity: 0;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                color: white;
                font-weight: 600;
                text-align: center;
                pointer-events: none;
                z-index: 10;
                font-size: 1rem;
                background: rgba(0, 0, 0, 0.8);
                padding: 1rem 2rem;
                border-radius: 1rem;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .hover-message svg {
                width: 1.5rem;
                height: 1.5rem;
                margin: 0 auto 0.5rem;
                display: block;
                animation: bounce 2s ease-in-out infinite;
            }

            @keyframes bounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-4px); }
            }

            /* Enhanced drag message */
            .drop-message {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) translateY(12px);
                opacity: 0;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                color: white;
                font-weight: 700;
                text-align: center;
                pointer-events: none;
                z-index: 15;
                font-size: 1.25rem;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
                background: rgba(0, 0, 0, 0.3);
                padding: 1.5rem 2.5rem;
                border-radius: 1.25rem;
                backdrop-filter: blur(20px);
                border: 2px solid rgba(255, 255, 255, 0.4);
            }

            .drop-message svg {
                width: 3rem;
                height: 3rem;
                margin: 0 auto 0.75rem;
                display: block;
                animation: dropBounce 1s ease-in-out infinite;
                filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
            }

            @keyframes dropBounce {
                0%, 100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-8px) scale(1.1); }
            }

            /* Color input styling */
            .color-input-group {
                display: flex;
                gap: 1rem;
                align-items: center;
                flex-wrap: wrap;
            }

            .color-picker-wrapper {
                position: relative;
                width: 4rem;
                height: 4rem;
                border-radius: 0.75rem;
                overflow: hidden;
                border: 3px solid #e5e7eb;
                transition: all 0.3s ease;
                cursor: pointer;
            }

            .color-picker-wrapper:hover {
                border-color: var(--primary-colour);
                transform: scale(1.05);
            }

            @media (prefers-color-scheme: dark) {
                .color-picker-wrapper {
                    border-color: #4b5563;
                }
            }

            .color-picker {
                width: 100%;
                height: 100%;
                border: none;
                cursor: pointer;
                background: none;
            }

            .color-text-input {
                flex: 1;
                padding: 0.875rem 1rem;
                border: 2px solid #e5e7eb;
                border-radius: 0.75rem;
                font-size: 0.875rem;
                font-family: 'SF Mono', 'Monaco', 'Consolas', monospace;
                background: white;
                color: #1e293b;
                transition: all 0.3s ease;
            }

            .color-text-input:focus {
                outline: none;
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
            }

            @media (prefers-color-scheme: dark) {
                .color-text-input {
                    background: #374151;
                    border-color: #4b5563;
                    color: #e5e7eb;
                }

                .color-text-input:focus {
                    border-color: var(--primary-colour);
                    box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
                }
            }

            /* Success message */
            .success-message {
                background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
                border: 1px solid #22c55e;
                color: #166534;
                padding: 1rem 1.5rem;
                border-radius: 0.75rem;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                font-weight: 500;
            }

            @media (prefers-color-scheme: dark) {
                .success-message {
                    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(34, 197, 94, 0.05) 100%);
                    border-color: #22c55e;
                    color: #4ade80;
                }
            }

            /* Enhanced Save Button */
            .btn-save {
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
                padding: 1rem 3rem;
                background: linear-gradient(135deg, var(--primary-colour) 0%, var(--secondary-colour) 100%);
                color: white;
                border: none;
                border-radius: 0.875rem;
                font-weight: 700;
                font-size: 1rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                cursor: pointer;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow:
                    0 4px 6px -1px rgba(0, 0, 0, 0.1),
                    0 2px 4px -1px rgba(0, 0, 0, 0.06),
                    0 0 0 0 var(--primary-colour);
                overflow: hidden;
                width: auto;
                min-width: 200px;
            }

            .btn-save::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s;
            }

            .btn-save:hover {
                transform: translateY(-3px);
                box-shadow:
                    0 20px 25px -5px rgba(0, 0, 0, 0.1),
                    0 10px 10px -5px rgba(0, 0, 0, 0.04),
                    0 0 0 3px rgba(167, 98, 44, 0.1);
                background: linear-gradient(135deg, var(--secondary-colour) 0%, var(--primary-colour) 100%);
            }

            .btn-save:hover::before {
                left: 100%;
            }

            .btn-save:active {
                transform: translateY(-1px);
                transition: all 0.1s;
            }

            .btn-save:focus {
                outline: none;
                box-shadow:
                    0 20px 25px -5px rgba(0, 0, 0, 0.1),
                    0 10px 10px -5px rgba(0, 0, 0, 0.04),
                    0 0 0 3px rgba(167, 98, 44, 0.1);
            }

            .btn-save-icon {
                width: 1.25rem;
                height: 1.25rem;
                transition: transform 0.3s ease;
            }

            .btn-save:hover .btn-save-icon {
                transform: scale(1.1) rotate(5deg);
            }

            /* Live preview panel */
            .preview-panel {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 1rem;
                padding: 2rem;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                border: 1px solid rgba(255, 255, 255, 0.2);
                position: sticky;
                top: 2rem;
            }

            @media (prefers-color-scheme: dark) {
                .preview-panel {
                    background: rgba(30, 41, 59, 0.95);
                    border-color: rgba(148, 163, 184, 0.1);
                    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
                }
            }

            .preview-title {
                font-size: 1.25rem;
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            @media (prefers-color-scheme: dark) {
                .preview-title {
                    color: #f1f5f9;
                }
            }

            .preview-section {
                margin-bottom: 2rem;
                padding: 1.5rem;
                border-radius: 0.75rem;
                border: 2px solid #e5e7eb;
                background: #f8fafc;
            }

            @media (prefers-color-scheme: dark) {
                .preview-section {
                    background: #334155;
                    border-color: #475569;
                }
            }

            .preview-section-title {
                font-size: 0.875rem;
                font-weight: 600;
                color: #64748b;
                margin-bottom: 1rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            @media (prefers-color-scheme: dark) {
                .preview-section-title {
                    color: #94a3b8;
                }
            }

            .logo-preview {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 80px;
                background: white;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
                margin-bottom: 0.5rem;
            }

            @media (prefers-color-scheme: dark) {
                .logo-preview {
                    background: #1e293b;
                    border-color: #475569;
                }
            }

            .logo-preview img {
                max-height: 60px;
                max-width: 200px;
                object-fit: contain;
            }

            .logo-placeholder {
                color: #9ca3af;
                font-size: 0.875rem;
                text-align: center;
            }

            .favicon-preview {
                display: flex;
                align-items: center;
                gap: 1rem;
                background: white;
                padding: 1rem;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
            }

            @media (prefers-color-scheme: dark) {
                .favicon-preview {
                    background: #1e293b;
                    border-color: #475569;
                }
            }

            .favicon-preview img {
                width: 32px;
                height: 32px;
                border-radius: 0.25rem;
            }

            .color-preview {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
            }

            .color-swatch {
                height: 60px;
                border-radius: 0.5rem;
                border: 2px solid #e5e7eb;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.75rem;
                font-weight: 600;
                color: white;
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
                transition: all 0.3s ease;
            }

            @media (prefers-color-scheme: dark) {
                .color-swatch {
                    border-color: #475569;
                }
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .branding-container {
                    padding: 1rem;
                }

                .form-card, .preview-panel {
                    padding: 1.5rem;
                }

                .color-input-group {
                    flex-direction: column;
                    align-items: stretch;
                }

                .color-picker-wrapper {
                    width: 100%;
                    height: 3rem;
                }

                .btn-save {
                    width: 100%;
                    justify-content: center;
                    padding: 1.25rem 2rem;
                }

                .preview-panel {
                    position: static;
                    order: -1;
                    margin-bottom: 1rem;
                }
            }
        </style>
        <div class="header-container">
            <div class="header-left">
                <a href="{{ route('settings.index') }}" class="back-button" title="Back to Settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">Branding Settings</h2>
            </div>
        </div>
    </x-slot>

    <div class="branding-container">
        <div class="main-wrapper">
            @if($errors->any())
                <div class="debug-message">
                    <h4>Validation Errors:</h4>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="success-message">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid-layout">
                <!-- Form Section -->
                <div class="form-card">
                    <h3 class="form-title">Brand Configuration</h3>
                    <p class="form-subtitle">
                        Customize your application's visual identity with logos, colors, and branding elements.
                        Changes will be reflected in real-time in the preview panel.
                    </p>

                    <form action="{{ route('settings.branding.update') }}" method="POST" enctype="multipart/form-data" id="brandingForm">
                        @csrf
                        @method('PUT')

                        <!-- Light Logo -->
                        <div class="input-group">
                            <label class="input-label">
                                <svg class="input-label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Light Mode Logo
                            </label>

                            <div class="file-dropzone" data-target="light_logo">
                                <input type="file" name="light_logo" accept="image/*" class="file-input" onchange="previewImage(this, 'light-logo-preview')">

                                @if($branding->light_logo)
                                    <div class="dropzone-content">
                                        <img src="{{ asset($branding->light_logo) }}" alt="Current light logo" class="current-image" id="light-preview">
                                    </div>
                                @else
                                    <div class="dropzone-content">
                                        <svg class="dropzone-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <strong>Upload Light Mode Logo</strong>
                                            <p>Click to browse or drag & drop files here</p>
                                            <p class="text-xs opacity-75">PNG, JPG, SVG up to 2MB</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Hover message -->
                                <div class="hover-message">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <div>{{ $branding->light_logo ? 'Click or drag to change' : 'Click to browse files' }}</div>
                                </div>

                                <!-- Drag message -->
                                <div class="drop-message">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3 3-3M12 12l0 7" />
                                    </svg>
                                    <div>Drop your logo here!</div>
                                </div>
                            </div>
                        </div>

                        <!-- Dark Logo -->
                        <div class="input-group">
                            <label class="input-label">
                                <svg class="input-label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                                Dark Mode Logo
                            </label>

                            <div class="file-dropzone" data-target="dark_logo">
                                <input type="file" name="dark_logo" accept="image/*" class="file-input" onchange="previewImage(this, 'dark-logo-preview')">

                                @if($branding->dark_logo)
                                    <div class="dropzone-content">
                                        <img src="{{ asset($branding->dark_logo) }}" alt="Current dark logo" class="current-image" id="dark-preview">
                                    </div>
                                @else
                                    <div class="dropzone-content">
                                        <svg class="dropzone-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <strong>Upload Dark Mode Logo</strong>
                                            <p>Click to browse or drag & drop files here</p>
                                            <p class="text-xs opacity-75">PNG, JPG, SVG up to 2MB</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Hover message -->
                                <div class="hover-message">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <div>{{ $branding->dark_logo ? 'Click or drag to change' : 'Click to browse files' }}</div>
                                </div>

                                <!-- Drag message -->
                                <div class="drop-message">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3 3-3M12 12l0 7" />
                                    </svg>
                                    <div>Drop your logo here!</div>
                                </div>
                            </div>
                        </div>

                        <!-- Favicon -->
                        <div class="input-group">
                            <label class="input-label">
                                <svg class="input-label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM7 3H5a2 2 0 00-2 2v12a4 4 0 004 4h2a2 2 0 002-2V5a2 2 0 00-2-2z" />
                                </svg>
                                Favicon
                            </label>

                            <div class="file-dropzone" data-target="favicon">
                                <input type="file" name="favicon" accept="image/*,.ico" class="file-input" onchange="previewImage(this, 'favicon-logo-preview')">

                                @if($branding->favicon)
                                    <div class="dropzone-content">
                                        <img src="{{ asset($branding->favicon) }}" alt="Current favicon" class="current-image favicon-image" id="favicon-preview">
                                    </div>
                                @else
                                    <div class="dropzone-content">
                                        <svg class="dropzone-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <strong>Upload Favicon</strong>
                                            <p>Click to browse or drag & drop files here</p>
                                            <p class="text-xs opacity-75">ICO, PNG 16x16 or 32x32</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Hover message -->
                                <div class="hover-message">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <div>{{ $branding->favicon ? 'Click or drag to change' : 'Click to browse files' }}</div>
                                </div>

                                <!-- Drag message -->
                                <div class="drop-message">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3 3-3M12 12l0 7" />
                                    </svg>
                                    <div>Drop your favicon here!</div>
                                </div>
                            </div>
                        </div>

                        <!-- Primary Color -->
                        <div class="input-group">
                            <label class="input-label">
                                <svg class="input-label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM7 3H5a2 2 0 00-2 2v12a4 4 0 004 4h2a2 2 0 002-2V5a2 2 0 00-2-2z" />
                                </svg>
                                Primary Color
                            </label>

                            <div class="color-input-group">
                                <div class="color-picker-wrapper">
                                    <input type="color" name="primary_colour" value="{{ $branding->primary_colour ?? '#a7622c' }}" class="color-picker" onchange="updateColorText(this, 'primary-text'); updatePreview();">
                                </div>
                                <input type="text" id="primary-text" value="{{ $branding->primary_colour ?? '#a7622c' }}" class="color-text-input" onchange="updateColorPicker(this, 'primary_colour'); updatePreview();" pattern="^#[0-9A-Fa-f]{6}$">
                            </div>
                        </div>

                        <!-- Secondary Color -->
                        <div class="input-group">
                            <label class="input-label">
                                <svg class="input-label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM7 3H5a2 2 0 00-2 2v12a4 4 0 004 4h2a2 2 0 002-2V5a2 2 0 00-2-2z" />
                                </svg>
                                Secondary Color
                            </label>

                            <div class="color-input-group">
                                <div class="color-picker-wrapper">
                                    <input type="color" name="secondary_colour" value="{{ $branding->secondary_colour ?? '#f97316' }}" class="color-picker" onchange="updateColorText(this, 'secondary-text'); updatePreview();">
                                </div>
                                <input type="text" id="secondary-text" value="{{ $branding->secondary_colour ?? '#f97316' }}" class="color-text-input" onchange="updateColorPicker(this, 'secondary_colour'); updatePreview();" pattern="^#[0-9A-Fa-f]{6}$">
                            </div>
                        </div>

                        <button type="submit" class="btn-save">
                            <svg class="btn-save-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Branding
                        </button>
                    </form>
                </div>

                <!-- Live Preview Panel -->
                <div class="preview-panel">
                    <h3 class="preview-title">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Live Preview
                    </h3>

                    <!-- Logo Preview -->
                    <div class="preview-section">
                        <div class="preview-section-title">Logos</div>
                        <div class="logo-preview">
                            @if($branding->light_logo)
                                <img src="{{ asset($branding->light_logo) }}" alt="Light logo preview" id="light-logo-preview">
                            @else
                                <div class="logo-placeholder" id="light-logo-preview">Light Mode Logo</div>
                            @endif
                        </div>
                        <div class="logo-preview" style="background: #1e293b;">
                            @if($branding->dark_logo)
                                <img src="{{ asset($branding->dark_logo) }}" alt="Dark logo preview" id="dark-logo-preview">
                            @else
                                <div class="logo-placeholder" style="color: #9ca3af;" id="dark-logo-preview">Dark Mode Logo</div>
                            @endif
                        </div>
                    </div>

                    <!-- Favicon Preview -->
                    <div class="preview-section">
                        <div class="preview-section-title">Favicon</div>
                        <div class="favicon-preview">
                            @if($branding->favicon)
                                <img src="{{ asset($branding->favicon) }}" alt="Favicon preview" id="favicon-logo-preview">
                                <span>{{ config('app.name') }}</span>
                            @else
                                <div style="width: 32px; height: 32px; background: #e5e7eb; border-radius: 0.25rem;" id="favicon-logo-preview"></div>
                                <span>{{ config('app.name') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Color Preview -->
                    <div class="preview-section">
                        <div class="preview-section-title">Brand Colors</div>
                        <div class="color-preview">
                            <div class="color-swatch" id="primary-color-swatch" style="background-color: {{ $branding->primary_colour ?? '#a7622c' }}">
                                Primary
                            </div>
                            <div class="color-swatch" id="secondary-color-swatch" style="background-color: {{ $branding->secondary_colour ?? '#f97316' }}">
                                Secondary
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize CSS variables with current values
        document.documentElement.style.setProperty('--primary-colour', '{{ $branding->primary_colour ?? "#a7622c" }}');
        document.documentElement.style.setProperty('--secondary-colour', '{{ $branding->secondary_colour ?? "#f97316" }}');

        function previewImage(input, livePreviewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Update the dropzone with new image
                    const dropzone = input.closest('.file-dropzone');
                    const dropzoneContent = dropzone.querySelector('.dropzone-content');

                    const isFavicon = livePreviewId.includes('favicon');
                    const faviconClass = isFavicon ? ' favicon-image' : '';

                    dropzoneContent.innerHTML = `<img src="${e.target.result}" alt="Preview" class="current-image${faviconClass}">`;

                    // Update hover message
                    const hoverMessage = dropzone.querySelector('.hover-message div');
                    if (hoverMessage) {
                        hoverMessage.textContent = 'Click or drag to change';
                    }

                    // Update live preview
                    const livePreview = document.getElementById(livePreviewId);
                    if (livePreview) {
                        if (livePreview.tagName === 'IMG') {
                            livePreview.src = e.target.result;
                        } else {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = 'Preview';
                            img.style.maxHeight = livePreviewId.includes('favicon') ? '32px' : '60px';
                            img.style.maxWidth = livePreviewId.includes('favicon') ? '32px' : '200px';
                            img.style.objectFit = 'contain';
                            if (livePreviewId.includes('favicon')) {
                                img.style.width = '32px';
                                img.style.height = '32px';
                                img.style.borderRadius = '0.25rem';
                            }
                            livePreview.parentElement.replaceChild(img, livePreview);
                            img.id = livePreviewId;
                        }
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function updateColorText(colorInput, textInputId) {
            document.getElementById(textInputId).value = colorInput.value;
        }

        function updateColorPicker(textInput, colorInputName) {
            const colorPicker = document.querySelector(`input[name="${colorInputName}"]`);
            if (colorPicker) {
                colorPicker.value = textInput.value;
            }
        }

        function updatePreview() {
            const primaryColor = document.querySelector('input[name="primary_colour"]').value;
            const secondaryColor = document.querySelector('input[name="secondary_colour"]').value;

            const primarySwatch = document.getElementById('primary-color-swatch');
            const secondarySwatch = document.getElementById('secondary-color-swatch');

            if (primarySwatch) primarySwatch.style.backgroundColor = primaryColor;
            if (secondarySwatch) secondarySwatch.style.backgroundColor = secondaryColor;

            document.documentElement.style.setProperty('--primary-colour', primaryColor);
            document.documentElement.style.setProperty('--secondary-colour', secondaryColor);
        }

        class FileUploader {
            constructor(dropzone) {
                this.dropzone = dropzone;
                this.input = dropzone.querySelector('.file-input');
                this.init();
            }

            init() {
                // Prevent default drag behaviors
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    this.dropzone.addEventListener(eventName, this.preventDefaults.bind(this), false);
                    document.body.addEventListener(eventName, this.preventDefaults.bind(this), false);
                });

                // Highlight drop area when item is dragged over it
                ['dragenter', 'dragover'].forEach(eventName => {
                    this.dropzone.addEventListener(eventName, this.highlight.bind(this), false);
                });

                // Unhighlight drop area when item is dragged away
                ['dragleave', 'drop'].forEach(eventName => {
                    this.dropzone.addEventListener(eventName, this.unhighlight.bind(this), false);
                });

                // Handle dropped files
                this.dropzone.addEventListener('drop', this.handleDrop.bind(this), false);
            }

            preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            highlight() {
                this.dropzone.classList.add('drag-hover');
            }

            unhighlight() {
                this.dropzone.classList.remove('drag-hover');
            }

            handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    this.input.files = files;
                    this.input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize file uploaders
            document.querySelectorAll('.file-dropzone').forEach(dropzone => {
                new FileUploader(dropzone);
            });

            // Initialize colors
            updatePreview();

            // Live color bindings
            document.querySelectorAll('input[type="color"], .color-text-input').forEach(el => {
                el.addEventListener('input', function() {
                    if (this.type === 'color') {
                        const id = this.getAttribute('onchange').match(/'([^']+)'/)[1];
                        updateColorText(this, id);
                    } else {
                        const name = this.getAttribute('onchange').match(/'([^']+)'/)[1];
                        updateColorPicker(this, name);
                    }
                    updatePreview();
                });
            });
        });
    </script>
</x-app-layout>
