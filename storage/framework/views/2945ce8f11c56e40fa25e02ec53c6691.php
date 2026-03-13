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
        <div class="header-container">
            <h2 class="header-title">
                <span class="header-main">Accident Report</span>
            </h2>
            <div class="header-right">
                <a href="<?php echo e(route('incidents.reports.pdf', $incident)); ?>" class="pdf-download-btn">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Download PDF</span>
                </a>
                <div class="status-badge status-<?php echo e($incident->status); ?>">
                    <?php echo e(ucfirst($incident->status)); ?>

                </div>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="page-container">
        <div class="form-container">
            <div class="card">
                <!-- Progress Header -->
                <div class="progress-header">
                    <div class="progress-content">
                        <div class="progress-icon">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="progress-text">Accident Report</span>
                        </div>
                        <div class="progress-date">
                            Report #<?php echo e(str_pad($incident->id, 6, '0', STR_PAD_LEFT)); ?> | Generated <?php echo e(now()->format('M j, Y')); ?>

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
                                <div class="text-display">
                                    <?php echo e($incident->brief_description); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Incident Type
                                    <span class="required">*</span>
                                </label>
                                <div class="text-display">
                                    <?php echo e($incident->incidentType->name ?? 'N/A'); ?>

                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">
                                    Location
                                    <span class="required">*</span>
                                </label>
                                <div class="text-display">
                                    <?php echo e($incident->location); ?>

                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">
                                    What Happened
                                    <span class="required">*</span>
                                </label>
                                <div class="text-display text-display-large"><?php echo e($incident->what_happened); ?></div>
                            </div>

                            <?php if($incident->additional_information): ?>
                                <div class="form-group full-width">
                                    <label class="form-label">
                                        Additional Information
                                    </label>
                                    <div class="text-display text-display-large"><?php echo e($incident->additional_information); ?></div>
                                </div>
                            <?php endif; ?>
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
                                <div class="text-display">
                                    <?php echo e($incident->date_of_occurrence ? $incident->date_of_occurrence->format('Y-m-d') : 'N/A'); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Time of Occurrence
                                    <span class="required">*</span>
                                </label>
                                <div class="text-display">
                                    <?php echo e($incident->time_of_occurrence ? \Carbon\Carbon::parse($incident->time_of_occurrence)->format('H:i') : 'N/A'); ?>

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
                                <div class="text-display">
                                    <?php echo e($incident->branch->display_name ?? $incident->branch->branch_name ?? 'N/A'); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Site
                                </label>
                                <div class="text-display">
                                    <?php echo e($incident->site->name ?? 'N/A'); ?>

                                </div>
                            </div>

                            <?php if($incident->agency): ?>
                                <div class="form-group full-width">
                                    <label class="form-label">
                                        Agency
                                    </label>
                                    <div class="text-display">
                                        <?php echo e($incident->agency->name); ?>

                                    </div>
                                </div>
                            <?php endif; ?>
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

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Person Type
                                    <span class="required">*</span>
                                </label>
                                <div class="text-display">
                                    <?php echo e($incident->affected_person_source); ?>

                                </div>
                            </div>

                            <?php if($incident->affected_person_source === 'Employee'): ?>
                                <div class="form-group">
                                    <label class="form-label">
                                        Affected Employee
                                        <span class="required">*</span>
                                    </label>
                                    <div class="text-display">
                                        <?php echo e($incident->affectedEmployee->name ?? 'N/A'); ?>

                                    </div>
                                </div>
                            <?php elseif($incident->affected_person_source === 'Customer'): ?>
                                <div class="form-group">
                                    <label class="form-label">
                                        Affected Customer
                                        <span class="required">*</span>
                                    </label>
                                    <div class="text-display">
                                        <?php echo e($incident->affectedCustomer->name ?? 'N/A'); ?>

                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="form-group">
                                    <label class="form-label">
                                        Affected Person
                                        <span class="required">*</span>
                                    </label>
                                    <div class="text-display">
                                        <?php echo e($incident->affected_person_other ?? 'N/A'); ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Reported By Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Reported By</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Reporter Type
                                    <span class="required">*</span>
                                </label>
                                <div class="text-display">
                                    <?php echo e($incident->reported_by_source); ?>

                                </div>
                            </div>

                            <?php if($incident->reported_by_source === 'Employee'): ?>
                                <div class="form-group">
                                    <label class="form-label">
                                        Reporter Employee
                                        <span class="required">*</span>
                                    </label>
                                    <div class="text-display">
                                        <?php echo e($incident->reportedEmployee->name ?? 'N/A'); ?>

                                    </div>
                                </div>
                            <?php elseif($incident->reported_by_source === 'Customer'): ?>
                                <div class="form-group">
                                    <label class="form-label">
                                        Reporter Customer
                                        <span class="required">*</span>
                                    </label>
                                    <div class="text-display">
                                        <?php echo e($incident->reportedCustomer->name ?? 'N/A'); ?>

                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="form-group">
                                    <label class="form-label">
                                        Reporter Name
                                        <span class="required">*</span>
                                    </label>
                                    <div class="text-display">
                                        <?php echo e($incident->reported_by_other ?? 'N/A'); ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Treatment Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Treatment Information</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Treatment Type
                                    <span class="required">*</span>
                                </label>
                                <div class="text-display">
                                    <?php echo e($incident->treatmentType->name ?? 'N/A'); ?>

                                </div>
                            </div>

                            <?php if($incident->physician_details): ?>
                                <div class="form-group full-width">
                                    <label class="form-label">
                                        Physician/Medical Details
                                    </label>
                                    <div class="text-display text-display-large"><?php echo e($incident->physician_details); ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Work Details Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Work Details</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Work Shift
                                    <span class="required">*</span>
                                </label>
                                <div class="text-display">
                                    <?php echo e($incident->work_shift ?? 'N/A'); ?>

                                </div>
                            </div>

                            <?php if($incident->hours_worked_prior): ?>
                                <div class="form-group">
                                    <label class="form-label">
                                        Hours Worked Prior to Incident
                                    </label>
                                    <div class="text-display">
                                        <?php echo e($incident->hours_worked_prior); ?> hours
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Injury Details Section -->
                    <?php if($incident->body_part_id || $incident->mechanism_id || $incident->injury_type_id): ?>
                        <div class="section-container">
                            <div class="section-header">
                                <div class="section-icon">
                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="section-title">Injury Details</h3>
                            </div>

                            <div class="form-grid">
                                <?php if($incident->body_part_id): ?>
                                    <div class="form-group full-width">
                                        <label class="form-label">
                                            Body Parts Affected
                                        </label>
                                        <div class="body-parts-tags">
                                            <?php
                                                $bodyPartIds = is_array($incident->body_part_id) ? $incident->body_part_id : json_decode($incident->body_part_id, true);
                                                $bodyParts = \App\Models\BodyPart::whereIn('id', $bodyPartIds ?? [])->get();
                                            ?>
                                            <?php $__currentLoopData = $bodyParts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bodyPart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="body-part-tag"><?php echo e($bodyPart->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if($incident->body_part_other): ?>
                                    <div class="form-group full-width">
                                        <label class="form-label">
                                            Other Body Part Details
                                        </label>
                                        <div class="text-display"><?php echo e($incident->body_part_other); ?></div>
                                    </div>
                                <?php endif; ?>

                                <?php if($incident->mechanism): ?>
                                    <div class="form-group">
                                        <label class="form-label">
                                            Mechanism of Injury
                                        </label>
                                        <div class="text-display">
                                            <?php echo e($incident->mechanism->name ?? 'N/A'); ?>

                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if($incident->injuryType): ?>
                                    <div class="form-group">
                                        <label class="form-label">
                                            Injury Type
                                        </label>
                                        <div class="text-display">
                                            <?php echo e($incident->injuryType->name ?? 'N/A'); ?>

                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if($incident->injury_type_other): ?>
                                    <div class="form-group full-width">
                                        <label class="form-label">
                                            Other Injury Type Details
                                        </label>
                                        <div class="text-display"><?php echo e($incident->injury_type_other); ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Investigation Details Section -->
                    <div class="section-container">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <h3 class="section-title">Investigation Details</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    Coordinator
                                    <span class="required">*</span>
                                </label>
                                <div class="text-display">
                                    <?php echo e($incident->coordinator->name ?? 'N/A'); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Status
                                </label>
                                <div class="text-display">
                                    <span class="status-badge status-<?php echo e($incident->status); ?>">
                                        <?php echo e(ucfirst($incident->status)); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Corrective Actions Section -->
                    <?php if($incident->correctiveActions && $incident->correctiveActions->count() > 0): ?>
                        <div class="section-container">
                            <div class="section-header">
                                <div class="section-icon">
                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <h3 class="section-title">Corrective Actions</h3>
                            </div>

                            <div class="corrective-actions-list">
                                <?php $__currentLoopData = $incident->correctiveActions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="corrective-action-card">
                                        <div class="action-header">
                                            <span class="action-number">Action #<?php echo e($index + 1); ?></span>
                                            <span class="action-status status-<?php echo e($action->status); ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $action->status))); ?>

                                            </span>
                                        </div>
                                        <div class="action-content">
                                            <div class="action-description">
                                                <?php echo e($action->corrective_actions); ?>

                                            </div>
                                            <?php if($action->user): ?>
                                                <div class="action-details">
                                                    <div class="action-detail-item">
                                                        <span class="detail-label">Assigned To:</span>
                                                        <span class="detail-value"><?php echo e($action->user->name); ?></span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Comments Section -->
                    <?php if($incident->comments && $incident->comments->count() > 0): ?>
                        <div class="section-container">
                            <div class="section-header">
                                <div class="section-icon">
                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                </div>
                                <h3 class="section-title">Comments</h3>
                            </div>

                            <div class="comments-list">
                                <?php $__currentLoopData = $incident->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="comment-card">
                                        <div class="comment-header">
                                            <div class="comment-author">
                                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <span><?php echo e($comment->user->name ?? 'Unknown User'); ?></span>
                                            </div>
                                            <div class="comment-date">
                                                <?php echo e($comment->created_at->format('M j, Y \a\t g:i A')); ?>

                                            </div>
                                        </div>
                                        <div class="comment-content">
                                            <?php echo e($comment->comment); ?>

                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Attachments Section -->
                    <?php if($incident->attachments && count($incident->attachments) > 0): ?>
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
                                <?php $__currentLoopData = $incident->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $ext = strtolower(pathinfo($attachment['path'] ?? '', PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        $isPdf = $ext === 'pdf';
                                    ?>
                                    <div class="attachment-item">
                                        <div class="attachment-info">
                                            <div class="attachment-icon">
                                                <?php if($isImage): ?>
                                                    <svg class="icon text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                <?php elseif($isPdf): ?>
                                                    <svg class="icon text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                <?php else: ?>
                                                    <svg class="icon text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                <?php endif; ?>
                                            </div>
                                            <div class="attachment-details">
                                                <span class="attachment-name"><?php echo e($attachment['name'] ?? 'Attachment ' . ($index + 1)); ?></span>
                                                <?php if(isset($attachment['size'])): ?>
                                                    <span class="attachment-size">(<?php echo e(number_format($attachment['size'] / 1024, 1)); ?> KB)</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="attachment-actions">
                                            <?php if($isImage || $isPdf): ?>
                                                <a href="<?php echo e(route('incidents.view-attachment', [$incident, $index])); ?>"
                                                   target="_blank"
                                                   class="attachment-btn view-btn"
                                                   title="View <?php echo e($isImage ? 'image' : 'PDF'); ?>">
                                                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?php echo e(route('incidents.download-attachment', [$incident, $index])); ?>"
                                               class="attachment-btn download-btn"
                                               title="Download file">
                                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Return Button -->
                    <div class="action-buttons">
                        <div class="button-group">
                            <a href="<?php echo e(route('incidents.register')); ?>" class="return-btn">
                                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                                </svg>
                                <span>Return to Register</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CSS Styles -->
    <style>
        /* Simple CSS Variables */
        :root {
            --primary: #00334C;
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

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .pdf-download-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: var(--error);
            color: white;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pdf-download-btn:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-in_progress,
        .status-in-progress {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-completed,
        .status-closed {
            background: #d1fae5;
            color: #065f46;
        }

        .progress-header {
            background: linear-gradient(135deg, var(--primary) 0%, #00537a 100%);
            padding: 32px 24px;
            color: white;
        }

        .progress-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .progress-icon {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .progress-icon .icon {
            width: 32px;
            height: 32px;
        }

        .progress-text {
            font-size: 24px;
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
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid var(--border);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border);
        }

        .section-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 8px;
            color: white;
        }

        .section-icon .icon {
            width: 24px;
            height: 24px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin: 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--text);
        }

        .required {
            color: var(--error);
        }

        .text-display {
            padding: 12px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 14px;
            color: var(--text);
            min-height: 42px;
        }

        .text-display-large {
            min-height: 80px;
        }

        .body-parts-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 8px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 6px;
        }

        .body-part-tag {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            background: #3b82f6;
            color: white;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 500;
        }

        .corrective-actions-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .corrective-action-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px;
        }

        .action-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .action-number {
            font-weight: 600;
            color: var(--text);
            font-size: 14px;
        }

        .action-status {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .action-content {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .action-description {
            font-size: 14px;
            color: var(--text);
            line-height: 1.5;
        }

        .action-details {
            display: flex;
            gap: 24px;
            padding-top: 8px;
            border-top: 1px solid var(--border);
        }

        .action-detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .detail-value {
            font-size: 14px;
            color: var(--text);
        }

        .comments-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .comment-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .comment-author {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: var(--text);
            font-size: 14px;
        }

        .comment-author .icon {
            width: 20px;
            height: 20px;
        }

        .comment-date {
            font-size: 12px;
            color: var(--text-muted);
        }

        .comment-content {
            font-size: 14px;
            color: var(--text);
            line-height: 1.5;
        }

        .attachments-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .attachment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 6px;
        }

        .attachment-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .attachment-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--bg-alt);
            border-radius: 6px;
        }

        .attachment-icon .icon {
            width: 24px;
            height: 24px;
        }

        .attachment-details {
            display: flex;
            flex-direction: column;
            gap: 4px;
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
        }

        .attachment-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 6px;
            border: 1px solid var(--border);
            background: var(--bg);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .attachment-btn:hover {
            background: var(--bg-alt);
        }

        .attachment-btn .icon {
            width: 20px;
            height: 20px;
        }

        .view-btn {
            color: var(--primary);
        }

        .download-btn {
            color: var(--error);
        }

        .action-buttons {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 2px solid var(--border);
        }

        .button-group {
            display: flex;
            justify-content: flex-start;
        }

        .return-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--primary);
            color: white;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .return-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .return-btn .icon {
            width: 20px;
            height: 20px;
        }

        .icon {
            width: 20px;
            height: 20px;
        }

        @media (prefers-color-scheme: dark) {
            .body-part-tag {
                background: #1e3a8a;
                color: #bfdbfe;
                border-color: #1e40af;
            }
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

            .header-container {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .header-right {
                flex-direction: column;
                gap: 8px;
            }

            .pdf-download-btn {
                width: 100%;
                justify-content: center;
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

            .action-details {
                flex-direction: column;
                gap: 8px;
            }

            .attachment-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .attachment-actions {
                align-self: flex-end;
            }

            .button-group {
                width: 100%;
            }

            .return-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/sites/29a/4/4aab0e4a5e/public_html/laravel12/resources/views/incidents/reports/investigation.blade.php ENDPATH**/ ?>