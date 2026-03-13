<x-app-layout>
    <x-slot name="header">
        <div class="header-container">
            <h2 class="header-title">
                <span class="header-main">View Accident/Incident</span>
            </h2>
            <div class="status-badge status-{{ $incident->status }}">
                {{ ucfirst($incident->status) }}
            </div>
        </div>
    </x-slot>

    <div class="page-container">
        <div class="form-container">
            <div class="card">
                <!-- Progress Header -->
                <div class="progress-header">
                    <div class="progress-content">
                        <div class="progress-icon">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span class="progress-text">Viewing Accident</span>
                        </div>
                        <div class="progress-date">
                            Created {{ $incident->created_at->format('M j, Y') }}
                        </div>
                    </div>
                </div>

                <div class="form">
                    <!-- Basic Information Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Basic Information</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Description
                                    <span class="required">*</span>
                                </label>
                                <div class="form-display">
                                    {{ $incident->brief_description }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Incident Type
                                    <span class="required">*</span>
                                </label>
                                <div class="form-display">
                                    @if(!empty($incident->incident_type_other_description) && $incident->incident_type_other_description !== null)
                                        Other
                                    @else
                                        {{ $incident->incidentType->name ?? 'N/A' }}
                                    @endif
                                </div>
                            </div>

                            @if(!empty($incident->incident_type_other_description) && $incident->incident_type_other_description !== null)
                                <div class="form-group">
                                    <label class="form-label">
                                        Details
                                    </label>
                                    <div class="form-display" style="padding: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--bg-alt);">
                                        {{ $incident->incident_type_other_description }}
                                    </div>
                                </div>
                            @endif

                            <div class="form-group full-width">
                                <label class="form-label">
                                    Location
                                    <span class="required">*</span>
                                </label>
                                <div class="form-display">
                                    {{ $incident->location }}
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">
                                    What Happened
                                    <span class="required">*</span>
                                </label>
                                <div class="form-display form-display-textarea">{{ trim($incident->what_happened) }}</div>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">
                                    Additional Information
                                </label>
                                <div class="form-display form-display-textarea">{{ $incident->additional_information ? trim($incident->additional_information) : 'None provided' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Date and Time Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Date and Time Information</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Date of Occurrence
                                    <span class="required">*</span>
                                </label>
                                <div class="form-display">
                                    {{ $incident->date_of_occurrence ? $incident->date_of_occurrence->format('Y-m-d') : 'N/A' }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Time of Occurrence
                                    <span class="required">*</span>
                                </label>
                                <div class="form-display">
                                    {{ $incident->time_of_occurrence ? \Carbon\Carbon::parse($incident->time_of_occurrence)->format('H:i') : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Work Location Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Work Location</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Branch
                                    <span class="required">*</span>
                                </label>
                                <div class="form-display">
                                    {{ $incident->branch->display_name ?? $incident->branch->branch_name ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Site
                                </label>
                                <div class="form-display">
                                    {{ $incident->site->name ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Affected Person Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Affected Person</h3>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Person Type
                                <span class="required">*</span>
                            </label>
                            <div class="form-display">
                                {{ $incident->affected_person_source }}
                            </div>
                        </div>

                        @if($incident->affected_person_source === 'Employee')
                            <div class="form-group">
                                <label class="form-label">
                                    Affected Employee
                                    <span class="required">*</span>
                                </label>
                                <div class="form-display">
                                    {{ $incident->affectedEmployee ? $incident->affectedEmployee->first_name . ' ' . $incident->affectedEmployee->last_name : 'N/A' }}
                                </div>
                            </div>
                        @elseif($incident->affected_person_source === 'Customer')
                            <div class="form-group">
                                <label class="form-label">
                                    Affected Customer
                                    <span class="required">*</span>
                                </label>
                                <div class="form-display">
                                    {{ $incident->affectedCustomer ? $incident->affectedCustomer->name : 'N/A' }}
                                </div>
                            </div>
                        @elseif($incident->affected_person_source === 'Other')
                            <div class="form-group">
                                <label class="form-label">
                                    Person's Name
                                    <span class="required">*</span>
                                </label>
                                <div class="form-display">
                                    {{ $incident->affected_person_other ?? 'N/A' }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Medical Information Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Medical Information</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Treatment Type
                                    <span class="required">*</span>
                                </label>
                                <div class="form-display">
                                    {{ $incident->treatmentType->name ?? 'N/A' }}
                                </div>
                            </div>

                            <!-- Updated Body Parts Display -->
                            <div class="form-group">
                                <label class="form-label">
                                    Body Parts Affected
                                </label>
                                <div class="form-display">
                                    @php
                                        $displayItems = [];

                                        // Handle array of body part IDs
                                        if(!empty($incident->body_part_id) && is_array($incident->body_part_id)) {
                                            foreach($incident->body_part_id as $bodyPartId) {
                                                $bodyPart = \App\Models\BodyPart::find($bodyPartId);
                                                if ($bodyPart) {
                                                    $displayItems[] = $bodyPart->name;
                                                }
                                            }
                                        }
                                        // Handle legacy single body part ID
                                        elseif(!empty($incident->body_part_id) && is_numeric($incident->body_part_id)) {
                                            $bodyPart = \App\Models\BodyPart::find($incident->body_part_id);
                                            if ($bodyPart) {
                                                $displayItems[] = $bodyPart->name;
                                            }
                                        }

                                        // Add "Other" body part if specified
                                        if(!empty($incident->body_part_other)) {
                                            $displayItems[] = $incident->body_part_other;
                                        }
                                    @endphp

                                    @if(!empty($displayItems))
                                        <div class="body-parts-tags">
                                            @foreach($displayItems as $bodyPart)
                                                <span class="body-part-tag">{{ $bodyPart }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Injury Type
                                </label>
                                <div class="form-display">
                                    @if($incident->injuryType)
                                        {{ $incident->injuryType->name }}
                                    @elseif($incident->injury_type_other)
                                        {{ $incident->injury_type_other }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">
                                    Physician / Health Care Provider Details
                                </label>
                                <div class="form-display form-display-textarea">{{ $incident->physician_details ? trim($incident->physician_details) : 'None provided' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Reported By Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Reported By</h3>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Reporter Type
                                <span class="required">*</span>
                            </label>
                            <div class="form-display">
                                {{ $incident->reported_by_source }}
                            </div>
                        </div>

                        @if($incident->reported_by_source === 'Employee')
                            <div class="form-group">
                                <label class="form-label">
                                    Reported By (Employee)
                                    <span class="required">*</span>
                                </label>
                                <div class="form-display">
                                    {{ $incident->reportedEmployee ? $incident->reportedEmployee->first_name . ' ' . $incident->reportedEmployee->last_name : 'N/A' }}
                                </div>
                            </div>
                        @elseif($incident->reported_by_source === 'Customer')
                            <div class="form-group">
                                <label class="form-label">
                                    Reported By (Customer)
                                    <span class="required">*</span>
                                </label>
                                <div class="form-display">
                                    {{ $incident->reportedCustomer ? $incident->reportedCustomer->name : 'N/A' }}
                                </div>
                            </div>
                        @elseif($incident->reported_by_source === 'Other')
                            <div class="form-group">
                                <label class="form-label">
                                    Reporter's Name
                                    <span class="required">*</span>
                                </label>
                                <div class="form-display">
                                    {{ $incident->reported_by_other ?? 'N/A' }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Administrative Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Administrative</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Coordinator
                                </label>
                                <div class="form-display">
                                    {{ $incident->coordinator->name ?? 'Not assigned' }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Status
                                </label>
                                <div class="form-display">
                                    <span class="status-badge status-{{ $incident->status }}">
                                        {{ ucfirst($incident->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments Section -->
                    @if($incident->attachments && count($incident->attachments) > 0)
                        <div class="section-container">
                            <div class="section-header">
                                <div class="section-icon">
                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                </div>
                                <h3 class="section-title">Attachments</h3>
                            </div>

                            <div class="attachments-list">
                                @foreach($incident->attachments as $index => $attachment)
                                    <div class="attachment-item">
                                        <div class="attachment-info">
                                            <div class="attachment-icon">
                                                @php
                                                    $fileExtension = strtolower(pathinfo($attachment['name'] ?? '', PATHINFO_EXTENSION));
                                                    $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                    $isPdf = $fileExtension === 'pdf';
                                                @endphp

                                                @if($isImage)
                                                    <svg class="icon text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                @elseif($isPdf)
                                                    <svg class="icon text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                @else
                                                    <svg class="icon text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="attachment-details">
                                                <span class="attachment-name">{{ $attachment['name'] ?? 'Attachment ' . ($index + 1) }}</span>
                                                @if(isset($attachment['size']))
                                                    <span class="attachment-size">({{ number_format($attachment['size'] / 1024, 1) }} KB)</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="attachment-actions">
                                            @if($isImage || $isPdf)
                                                <a href="{{ route('incidents.view-attachment', [$incident, $index]) }}"
                                                   target="_blank"
                                                   class="attachment-btn view-btn"
                                                   title="View {{ $isImage ? 'image' : 'PDF' }}">
                                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                            @endif
                                            <a href="{{ route('incidents.download-attachment', [$incident, $index]) }}"
                                               class="attachment-btn download-btn"
                                               title="Download file">
                                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <div class="button-group">
                            <a href="{{ route('incidents.register') }}" class="cancel-btn">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                                </svg>
                                <span>Back to List</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Same CSS as edit page with read-only modifications -->
    <style>
        /* Simple CSS Variables */
        :root {
            --primary: #131F34;
            --success: #10b981;
            --error: #ef4444;
            --warning: #f59e0b;
            --bg: #ffffff;
            --bg-alt: #f9fafb;
            --border: #e5e7eb;
            --text: #111827;
            --text-muted: #6b7280;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #1f2937;
                --bg-alt: #374151;
                --border: #4b5563;
                --text: #f9fafb;
                --text-muted: #9ca3af;
            }
        }

        .page-container {
            min-height: 100vh;
            background: var(--bg-alt);
            padding: 24px 16px;
        }

        .form-container {
            max-width: 1024px;
            margin: 0 auto;
        }

        .card {
            background: var(--bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin: 0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-badge.status-pending { background: #fef3c7; color: #92400e; }
        .status-badge.status-investigating { background: #00334C; color: #fff; }
        .status-badge.status-completed { background: #d1fae5; color: #065f46; }
        .status-badge.status-closed { background: var(--bg-alt); color: var(--text-muted); }

        .progress-header {
            background: linear-gradient(135deg, #131F34 0%, #131F34 100%);
            color: white;
            padding: 24px;
        }

        .progress-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .progress-icon {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .progress-text {
            font-size: 18px;
            font-weight: 600;
        }

        .progress-date {
            font-size: 14px;
            opacity: 0.9;
        }

        .form {
            padding: 32px 24px;
        }

        .section-container {
            background: var(--bg-alt);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--border);
        }

        .section-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #131F34 0%, #131F34 100%);
            border-radius: 12px;
            color: white;
            flex-shrink: 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin: 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 8px;
        }

        .required {
            color: var(--error);
            margin-left: 4px;
        }

        /* Read-only display styles */
        .form-display {
            width: 100%;
            padding: 12px 16px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 14px;
            min-height: 44px;
            display: flex;
            align-items: center;
        }

        .form-display-textarea {
            min-height: 80px;
            
            padding-top: 12px;
            white-space: pre-wrap;
            line-height: 1.5;
            display: block;
            text-align: left;
        }

        /* Body Parts Tags Styles */
        .body-parts-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
        }

        .body-part-tag {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            background: #00334C;
            color: #fff;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 500;
            border: 1px solid #bfdbfe;
        }

        @media (prefers-color-scheme: dark) {
            .body-part-tag {
                background: #1e3a8a;
                color: #bfdbfe;
                border-color: #1e40af;
            }
        }

        .attachments-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
        }

        .attachment-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .attachment-icon {
            width: 32px;
            height: 32px;
            flex-shrink: 0;
        }

        .attachment-details {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .attachment-name {
            font-size: 14px;
            font-weight: 500;
            color: var(--text);
        }

        .attachment-size {
            font-size: 12px;
            color: var(--text-muted);
        }

        .attachment-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .attachment-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .view-btn {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }

        .view-btn:hover {
            background: rgba(34, 197, 94, 0.2);
        }

        .download-btn {
            background: rgba(59, 130, 246, 0.1);
            color: #131F34;
        }

        .download-btn:hover {
            background: rgba(59, 130, 246, 0.2);
        }

        .action-buttons {
            position: sticky;
            bottom: 0;
            background: var(--bg);
            border-top: 2px solid var(--border);
            padding: 24px;
            margin: 0 -24px -32px -24px;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 16px;
        }

        .cancel-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            background: var(--bg-alt);
            color: var(--text-muted);
            border: 2px solid var(--border);
        }

        .cancel-btn:hover {
            background: var(--bg);
            color: var(--text);
        }

        .icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .page-container {
                padding: 16px 8px;
            }

            .form {
                padding: 24px 16px;
            }

            .section-container {
                padding: 16px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .button-group {
                flex-direction: column-reverse;
                gap: 12px;
            }

            .cancel-btn {
                width: 100%;
                justify-content: center;
            }

            .header-container {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .progress-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .body-parts-tags {
                gap: 4px;
            }

            .body-part-tag {
                font-size: 11px;
                padding: 3px 8px;
            }
        }
    </style>
</x-app-layout>
