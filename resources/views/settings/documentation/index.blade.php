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
            }

            @media (prefers-color-scheme: dark) {
                .header-title {
                    color: #d1d5db;
                }
            }

            .create-customer-btn {
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
            }

            .create-customer-btn:hover {
                background-color: #924f25;
                border-color: #924f25;
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .create-customer-btn:active {
                transform: translateY(0);
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
        </style>
        <div class="header-container">
            <div class="header-left">
                <a href="{{ route('settings.index') }}" class="back-button" title="Back to Settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="header-title">System Documentation</h2>
            </div>
        </div>
    </x-slot>

    <style>
        :root {
            --radius: 12px;
            --transition: 0.25s ease;
            --shadow: 0 10px 25px -5px rgba(0,0,0,0.15);
        }

        .doc-container {
            padding: 2.5rem 1rem;
            background-color: #f8fafc;
            min-height: 100vh;
            color: #374151;
        }

        @media (prefers-color-scheme: dark) {
            .doc-container {
                background-color: #0f172a;
                color: #d1d5db;
            }
        }

        .inner {
            max-width: 80rem;
            margin: 0 auto;
        }

        .header-block {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: #1f2937;
        }

        @media (prefers-color-scheme: dark) {
            .title {
                color: #f9fafb;
            }
        }

        .subtitle {
            font-size: 1.05rem;
            margin: 0.5rem 0 1.25rem;
            color: #6b7280;
            max-width: 50rem;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.4;
        }

        @media (prefers-color-scheme: dark) {
            .subtitle {
                color: #9ca3af;
            }
        }

        .docs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.75rem;
            margin-top: 1rem;
        }

        .doc-card {
            position: relative;
            background-color: white;
            border-radius: var(--radius);
            padding: 1.75rem 1.75rem 2.25rem;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
            border: 2px solid transparent;
            transition: all var(--transition);
            box-shadow: 0 8px 20px -5px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .doc-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
            border-color: var(--primary-colour);
        }

        .doc-icon {
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            flex-shrink: 0;
        }

        .doc-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 0.5rem;
        }

        .doc-desc {
            font-size: 0.9rem;
            margin: 0 0 1rem;
            line-height: 1.4;
            color: #555f7a;
        }

        .footer {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .action {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #1f2937;
            transition: all var(--transition);
        }

        .action svg {
            transition: transform var(--transition);
        }

        .doc-card:hover .action svg {
            transform: translateX(3px);
        }

        /* Left accent bars */
        .doc-card.api {
            border-left: 6px solid var(--primary-colour);
        }

        .doc-card.github {
            border-left: 6px solid var(--primary-colour);
        }

        .doc-card.sitemap {
            border-left: 6px solid var(--primary-colour);
        }

        .doc-card.architecture {
            border-left: 6px solid var(--primary-colour);
        }

        .doc-card.guides {
            border-left: 6px solid var(--primary-colour);
        }

        .doc-card.er-diagram {
            border-left: 6px solid var(--primary-colour);
        }

        @media (prefers-color-scheme: dark) {
            .doc-card {
                background-color: #1f2937;
                box-shadow: 0 10px 30px -5px rgba(0,0,0,0.6);
            }

            .doc-desc {
                color: #9ca3af;
            }

            .action {
                color: #f9fafb;
            }
        }
    </style>

    <div class="doc-container">
        <div class="inner">
            <div class="header-block">
                <h1 class="title">System Documentation</h1>
                <p class="subtitle">
                    Access all technical and user documentation in one place. Quickly jump to the API reference, code repository, or the sitemap.
                </p>
            </div>

            <div class="docs-grid">

                <!-- API Documentation card -->
                <a href="{{ route('settings.api.settings.index') }}" class="doc-card api">
                    <div class="doc-icon" aria-hidden="true" style="background: rgba(167,98,44,0.1);">
                        <span style="font-size:1.75rem;">🔑</span>
                    </div>
                    <div class="content">
                        <h2 class="doc-title">API Settings</h2>
                        <p class="doc-desc">Manage your API tokens and authentication settings.</p>
                    </div>
                    <div class="footer">
                        <div class="action">
                            <span>Manage API Settings</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform .25s ease;">
                                <path d="M4 12h12" />
                                <path d="M16 8l6 4-6 4" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- GitHub Repository -->
                <a href="https://github.com/Reece-Downs/WiseParking" target="_blank" class="doc-card github">
                    <div class="doc-icon" aria-hidden="true" style="background: rgba(110,84,148,0.1);">
                        <span style="font-size:1.75rem;">🐙</span>
                    </div>
                    <div class="content">
                        <h2 class="doc-title">GitHub Repository</h2>
                        <p class="doc-desc">Inspect source code, branches, commit history and contribute.</p>
                    </div>
                    <div class="footer">
                        <div class="action">
                            <span>Open on GitHub</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform .25s ease;">
                                <path d="M4 12h12" />
                                <path d="M16 8l6 4-6 4" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Sitemap -->
                <a href="{{ route('settings.documentation.file', ['filename' => 'site-map.pdf']) }}"  target="_blank" class="doc-card sitemap">
                    <div class="doc-icon" aria-hidden="true" style="background: rgba(6,182,212,0.1);">
                        <span style="font-size:1.75rem;">🗺️</span>
                    </div>
                    <div class="content">
                        <h2 class="doc-title">Sitemap</h2>
                        <p class="doc-desc">Download the PDF of the full site structure and flow.</p>
                    </div>
                    <div class="footer">
                        <div class="action">
                            <span>Download</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform .25s ease;">
                                <path d="M4 12h12" />
                                <path d="M16 8l6 4-6 4" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- ER Diagram -->
                <a href="{{ route('settings.documentation.file', ['filename' => 'er-diagram.png']) }}"
                   target="_blank"
                   class="doc-card er-diagram"
                   aria-label="Download the ER Diagram">
                    <div class="doc-icon" aria-hidden="true" style="background: rgba(123, 97, 255, 0.1);">
                        <!-- simple ER-style icon: two entities with a relationship diamond -->
                        <svg width="40" height="32" viewBox="0 0 40 32" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <!-- Entity 1 -->
                            <rect x="2" y="8" width="12" height="12" rx="2" />
                            <!-- Entity 2 -->
                            <rect x="26" y="8" width="12" height="12" rx="2" />
                            <!-- Relationship diamond -->
                            <polygon points="19,14 22,17 25,14 22,11" />
                            <!-- Connectors -->
                            <path d="M14 14h5" />
                            <path d="M25 14h5" />
                        </svg>
                    </div>
                    <div class="content">
                        <h2 class="doc-title">ER Diagram</h2>
                        <p class="doc-desc">Download the image of the entity–relationship diagram.</p>
                    </div>
                    <div class="footer">
                        <div class="action">
                            <span>Download</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform .25s ease;">
                                <path d="M4 12h12" />
                                <path d="M16 8l6 4-6 4" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- System Architecture -->
                <a href="{{ route('settings.documentation.file', ['filename' => 'system-architecture.png']) }}" target="_blank" class="doc-card architecture">
                    <div class="doc-icon" aria-hidden="true" style="background: rgba(16,185,129,0.1);">
                        <span style="font-size:1.75rem;">🛠️</span>
                    </div>
                    <div class="content">
                        <h2 class="doc-title">System Architecture</h2>
                        <p class="doc-desc">View the detailed system architecture diagram outlining system components.</p>
                    </div>
                    <div class="footer">
                        <div class="action">
                            <span>View Architecture</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform .25s ease;">
                                <path d="M4 12h12" />
                                <path d="M16 8l6 4-6 4" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Guides -->
                <a href="{{ route('settings.documentation.guides.index') }}" class="doc-card guides">
                    <div class="doc-icon" aria-hidden="true" style="background: rgba(94,129,230,0.1);">
                        <span style="font-size:1.75rem;">📖</span>
                    </div>
                    <div class="content">
                        <h2 class="doc-title">Guides</h2>
                        <p class="doc-desc">Step-by-step walkthroughs and how-tos for common tasks and workflows.</p>
                    </div>
                    <div class="footer">
                        <div class="action">
                            <span>View Guides</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform .25s ease;">
                                <path d="M4 12h12" />
                                <path d="M16 8l6 4-6 4" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
