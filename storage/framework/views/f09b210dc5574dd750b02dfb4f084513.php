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
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            Report an Accident/Incident
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <?php if(session('success')): ?>
                <div class="mb-4 p-4 bg-green-500/10 border border-green-500 text-green-700 rounded">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-4 p-4 bg-red-500/10 border border-red-500 text-red-700 rounded">
                    <strong class="block mb-2">Form Error:</strong>
                    <ul class="list-disc pl-5 text-sm">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('incidents.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-10">
                <?php echo csrf_field(); ?>

                <!-- 1. Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600 pb-2 mb-4">1. Basic Information</h3>

                    <div>
                        <label for="brief_description" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'brief_description','name' => 'brief_description','class' => 'mt-1 block w-full','value' => ''.e(old('brief_description')).'','required' => true,'placeholder' => 'e.g., Slip on wet floor in reception area.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'brief_description','name' => 'brief_description','class' => 'mt-1 block w-full','value' => ''.e(old('brief_description')).'','required' => true,'placeholder' => 'e.g., Slip on wet floor in reception area.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                        <?php $__errorArgs = ['brief_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="incident_type_id" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Type of Accident<span class="text-red-500">*</span>
                        </label>
                        <div class="custom-select-wrapper">
                            <select id="incident_type_id" name="incident_type_id" required
                                    class="custom-select mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select an option...</option>
                                <?php $__currentLoopData = $incidentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->id); ?>" <?php echo e(old('incident_type_id') == $type->id ? 'selected' : ''); ?>>
                                        <?php echo e($type->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <option value="other" <?php echo e(old('incident_type_id') == 'other' ? 'selected' : ''); ?>>Other</option>
                            </select>
                            <div class="search-overlay">
                                <input type="text" class="search-input" placeholder="Type to search options...">
                                <div class="search-results"></div>
                            </div>
                        </div>
                        <?php $__errorArgs = ['incident_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div id="incident_type_other_container" style="<?php echo e(old('incident_type_id') === 'other' ? '' : 'display: none;'); ?>">
                        <label for="incident_type_other_description" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Specify Other Type of Accident <span class="text-red-500">*</span>
                        </label>
                        <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'incident_type_other_description','name' => 'incident_type_other_description','class' => 'mt-1 block w-full','value' => ''.e(old('incident_type_other_description')).'','placeholder' => 'Please specify the type of accident']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'incident_type_other_description','name' => 'incident_type_other_description','class' => 'mt-1 block w-full','value' => ''.e(old('incident_type_other_description')).'','placeholder' => 'Please specify the type of accident']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                        <?php $__errorArgs = ['incident_type_other_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="location" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Location / Place of Work <span class="text-red-500">*</span>
                        </label>
                        <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'location','name' => 'location','class' => 'mt-1 block w-full','value' => ''.e(old('location')).'','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'location','name' => 'location','class' => 'mt-1 block w-full','value' => ''.e(old('location')).'','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                        <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="additional_information" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Additional Information
                        </label>
                        <textarea id="additional_information" name="additional_information" rows="3"
                                  class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"><?php echo e(old('additional_information')); ?></textarea>
                        <?php $__errorArgs = ['additional_information'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="attachments" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Attachments
                        </label>
                        <div class="file-upload-container">
                            <input id="attachments" name="attachments[]" type="file" multiple
                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                   class="file-input">
                            <div class="file-upload-display">
                                <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span class="upload-text">Click to upload files</span>
                                <span class="upload-subtext">Images, documents, etc. (Multiple files allowed)</span>
                            </div>
                        </div>
                        <div id="file-list" class="file-list"></div>
                        <?php $__errorArgs = ['attachments'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php $__errorArgs = ['attachments.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- 2. Affected Person -->
                <div class="space-y-4 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600 pb-2 mb-4">2. Affected Person</h3>

                    <!-- Person Type Selection -->
                    <div>
                        <label for="affected_person_type" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Person Involved <span class="text-red-500">*</span>
                        </label>
                        <div class="custom-select-wrapper">
                            <select id="affected_person_type" name="affected_person_source" required
                                    class="custom-select mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select type...</option>
                                <option value="Employee" <?php echo e(old('affected_person_source') === 'Employee' ? 'selected' : ''); ?>>Employee</option>
                                <option value="Other" <?php echo e(old('affected_person_source') === 'Other' ? 'selected' : ''); ?>>Other</option>
                            </select>
                            <div class="search-overlay">
                                <input type="text" class="search-input" placeholder="Type to search...">
                                <div class="search-results"></div>
                            </div>
                        </div>
                        <?php $__errorArgs = ['affected_person_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Employee Selection (conditional) -->
                    <div id="affected_employee_container" class="person-detail-contain">
                        <label for="affected_employee_id" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Select Employee <span class="text-red-500">*</span>
                        </label>
                        <div class="custom-select-wrapper">
                            <select id="affected_employee_id" name="affected_employee_id"
                                    class="custom-select mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select employee...</option>
                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($employee->id); ?>"
                                            data-site-id="<?php echo e($employee->site_id); ?>"
                                            data-site-name="<?php echo e($employee->site->name ?? ''); ?>"
                                            data-branch-code="<?php echo e($employee->site->branch_code ?? ''); ?>"
                                        <?php echo e(old('affected_employee_id') == $employee->id ? 'selected' : ''); ?>>
                                        <?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="search-overlay">
                                <input type="text" class="search-input" placeholder="Type to search employees...">
                                <div class="search-results" id="search-result-for-affected-employee"></div>
                            </div>
                        </div>
                        <?php $__errorArgs = ['affected_employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Other Person Details (conditional) -->
                    <div id="affected_person_other_container" class="person-detail-container">
                        <label for="affected_person_name" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Person's Name <span class="text-red-500">*</span>
                        </label>
                        <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'affected_person_name','name' => 'affected_person_other','class' => 'mt-1 block w-full person-name-input','value' => ''.e(old('affected_person_other')).'','placeholder' => 'Enter full name of affected person']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'affected_person_name','name' => 'affected_person_other','class' => 'mt-1 block w-full person-name-input','value' => ''.e(old('affected_person_other')).'','placeholder' => 'Enter full name of affected person']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                        <?php $__errorArgs = ['affected_person_other'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- 3. Incident Details -->
                <div class="space-y-4 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600 pb-2 mb-4">3. Incident Details</h3>

                    <!-- Reporter Type Selection -->
                    <div>
                        <label for="reported_by_type" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Reported By <span class="text-red-500">*</span>
                        </label>
                        <div class="custom-select-wrapper">
                            <select id="reported_by_type" name="reported_by_source" required
                                    class="custom-select mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select type...</option>
                                <option value="Employee" <?php echo e(old('reported_by_type', 'Employee') === 'Employee' ? 'selected' : ''); ?>>Employee</option>
                                <option value="Other" <?php echo e(old('reported_by_type') === 'Other' ? 'selected' : ''); ?>>Other</option>
                            </select>
                            <div class="search-overlay">
                                <input type="text" class="search-input" placeholder="Type to search...">
                                <div class="search-results"></div>
                            </div>
                        </div>
                        <?php $__errorArgs = ['reported_by_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Employee Reporter Selection (conditional) -->
                    <div id="reported_employee_container" class="person-detail-contain">
                        <label for="reported_employee_id" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Select Employee <span class="text-red-500">*</span>
                        </label>
                        <div class="custom-select-wrapper">
                            <select id="reported_employee_id" name="reported_employee_id"
                                    class="custom-select mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select employee...</option>
                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($employee->id); ?>"
                                            data-site-id="<?php echo e($employee->site_id); ?>"
                                            data-site-name="<?php echo e($employee->site->name ?? ''); ?>"
                                            data-branch-code="<?php echo e($employee->site->branch_code ?? ''); ?>"
                                        <?php echo e(old('reported_employee_id') == $employee->id ? 'selected' : ''); ?>>
                                        <?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="search-overlay">
                                <input type="text" class="search-input" placeholder="Type to search employees...">
                                <div class="search-results"></div>
                            </div>
                        </div>
                        <?php $__errorArgs = ['reported_employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Other Reporter Details (conditional) -->
                    <div id="reported_by_other_container" class="person-detail-container">
                        <label for="reported_by_name" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Reporter's Name <span class="text-red-500">*</span>
                        </label>
                        <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'reported_by_name','name' => 'reported_by_other','class' => 'mt-1 block w-full person-name-input','value' => ''.e(old('reported_by_other')).'','placeholder' => 'Enter full name of person reporting this incident']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'reported_by_name','name' => 'reported_by_other','class' => 'mt-1 block w-full person-name-input','value' => ''.e(old('reported_by_other')).'','placeholder' => 'Enter full name of person reporting this incident']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                        <?php $__errorArgs = ['reported_by_other'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="treatment_type_id" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Treatment Given <span class="text-red-500">*</span>
                        </label>
                        <div class="custom-select-wrapper">
                            <select id="treatment_type_id" name="treatment_type_id" required
                                    class="custom-select mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select treatment...</option>
                                <?php $__currentLoopData = $treatmentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $treatment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($treatment->id); ?>" <?php echo e(old('treatment_type_id') == $treatment->id ? 'selected' : ''); ?>>
                                        <?php echo e($treatment->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="search-overlay">
                                <input type="text" class="search-input" placeholder="Type to search treatments...">
                                <div class="search-results"></div>
                            </div>
                        </div>
                        <?php $__errorArgs = ['treatment_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="physician_details" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Physician / Health Care Provider
                        </label>
                        <textarea id="physician_details" name="physician_details" rows="3"
                                  placeholder="e.g., St. Thomas' Hospital, Dr. Jane Doe"
                                  class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"><?php echo e(old('physician_details')); ?></textarea>
                        <?php $__errorArgs = ['physician_details'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="date_of_occurrence" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                                Date of Occurrence <span class="text-red-500">*</span>
                            </label>
                            <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'date_of_occurrence','type' => 'date','name' => 'date_of_occurrence','value' => ''.e(old('date_of_occurrence')).'','required' => true,'class' => 'mt-1 block w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'date_of_occurrence','type' => 'date','name' => 'date_of_occurrence','value' => ''.e(old('date_of_occurrence')).'','required' => true,'class' => 'mt-1 block w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                            <?php $__errorArgs = ['date_of_occurrence'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <label for="time_of_occurrence" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                                Time of Occurrence <span class="text-red-500">*</span>
                            </label>
                            <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'time_of_occurrence','type' => 'time','name' => 'time_of_occurrence','value' => ''.e(old('time_of_occurrence')).'','required' => true,'class' => 'mt-1 block w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'time_of_occurrence','type' => 'time','name' => 'time_of_occurrence','value' => ''.e(old('time_of_occurrence')).'','required' => true,'class' => 'mt-1 block w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                            <?php $__errorArgs = ['time_of_occurrence'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- 4. Work Details -->
                <div class="space-y-4 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600 pb-2 mb-4">4. Work Details</h3>

                    <div>
                        <label for="branch_id" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Branch <span class="text-red-500">*</span>
                        </label>
                        <div class="custom-select-wrapper">
                            <select id="branch_id" name="branch_id" required
                                    class="custom-select mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select branch...</option>
                                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($branch->id); ?>" data-branch-code="<?php echo e($branch->branch_code); ?>" <?php echo e(old('branch_id') == $branch->id ? 'selected' : ''); ?>>
                                        <?php echo e($branch->display_name ?? $branch->branch_name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="site_id" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Site
                        </label>
                        <div class="custom-select-wrapper">
                            <select id="site_id" name="site_id"
                                    class="custom-select mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select site...</option>
                                <?php $__currentLoopData = $sites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $site): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($site->id); ?>" data-branch-code="<?php echo e($site->branch_code); ?>" <?php echo e(old('site_id') == $site->id ? 'selected' : ''); ?>>
                                        <?php echo e($site->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="search-overlay">
                                <input type="text" class="search-input" placeholder="Type to search sites...">
                                <div class="search-results"></div>
                            </div>
                        </div>
                        <?php $__errorArgs = ['site_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- 5. Medical & Incident Specifics -->
                <div class="space-y-4 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600 pb-2 mb-4">5. Medical & Incident Specifics</h3>

                    <!-- Multi-Select Body Parts -->
                    <div>
                        <label for="body_parts_selector" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Affected Body Parts
                        </label>
                        <div class="body-parts-container">
                            <div class="body-parts-wrapper">
                                <div class="body-parts-selected" id="body-parts-selected">
                                    <span class="placeholder-text" id="body-parts-placeholder">Select affected body parts...</span>
                                </div>
                                <div class="body-parts-dropdown" id="body-parts-dropdown">
                                    <div class="body-parts-search-wrapper">
                                        <input type="text" id="body-parts-search" class="body-parts-search-input" placeholder="Search body parts...">
                                    </div>
                                    <div class="body-parts-options" id="body-parts-options">
                                        <?php
                                            $selectedBodyParts = old('body_part_id', []);
                                            if (!is_array($selectedBodyParts)) {
                                                $selectedBodyParts = [];
                                            }
                                        ?>

                                        <?php $__currentLoopData = $bodyParts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bodyPart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="body-part-option" data-body-part-id="<?php echo e($bodyPart->id); ?>" data-body-part-name="<?php echo e($bodyPart->name); ?>">
                                                <div class="body-part-option-checkbox">
                                                    <input type="checkbox" name="body_part_id[]" id="body-part-<?php echo e($bodyPart->id); ?>" value="<?php echo e($bodyPart->id); ?>"
                                                        <?php echo e(in_array($bodyPart->id, $selectedBodyParts) ? 'checked' : ''); ?>>
                                                </div>
                                                <div class="body-part-option-details">
                                                    <div class="body-part-option-name"><?php echo e($bodyPart->name); ?></div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <!-- Other option -->
                                        <div class="body-part-option" data-body-part-id="other" data-body-part-name="Other">
                                            <div class="body-part-option-checkbox">
                                                <input type="checkbox" name="body_part_id[]" id="body-part-other" value="other"
                                                       <?php echo e(in_array('other', $selectedBodyParts) ? 'checked' : ''); ?>

                                                       onchange="toggleBodyPartOther(this)">
                                            </div>
                                            <div class="body-part-option-details">
                                                <div class="body-part-option-name">Other</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $__errorArgs = ['body_part_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Other Body Part Input -->
                    <div id="body_part_other_container" style="<?php echo e(in_array('other', $selectedBodyParts) ? '' : 'display: none;'); ?>">
                        <label for="body_part_other" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Specify Other Body Part
                        </label>
                        <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'body_part_other','name' => 'body_part_other','class' => 'mt-1 block w-full','value' => ''.e(old('body_part_other')).'','placeholder' => 'Please specify the affected body part']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'body_part_other','name' => 'body_part_other','class' => 'mt-1 block w-full','value' => ''.e(old('body_part_other')).'','placeholder' => 'Please specify the affected body part']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                        <?php $__errorArgs = ['body_part_other'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="injury_type_id" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Nature of Injury/Illness
                        </label>
                        <div class="custom-select-wrapper">
                            <select id="injury_type_id" name="injury_type_id"
                                    class="custom-select mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select injury type...</option>
                                <?php $__currentLoopData = $injuryTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $injuryType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($injuryType->id); ?>" <?php echo e(old('injury_type_id') == $injuryType->id ? 'selected' : ''); ?>>
                                        <?php echo e($injuryType->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <option value="other" <?php echo e(old('injury_type_id') == 'other' ? 'selected' : ''); ?>>Other</option>
                            </select>
                            <div class="search-overlay">
                                <input type="text" class="search-input" placeholder="Type to search injury types...">
                                <div class="search-results"></div>
                            </div>
                        </div>
                        <?php $__errorArgs = ['injury_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div id="injury_type_other_container" style="<?php echo e(old('injury_type_id') === 'other' ? '' : 'display: none;'); ?>">
                        <label for="injury_type_other" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Specify Other Nature of Injury/Illness <span class="text-red-500">*</span>
                        </label>
                        <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'injury_type_other','name' => 'injury_type_other','class' => 'mt-1 block w-full','value' => ''.e(old('injury_type_other')).'','placeholder' => 'Please specify the nature of injury/illness']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'injury_type_other','name' => 'injury_type_other','class' => 'mt-1 block w-full','value' => ''.e(old('injury_type_other')).'','placeholder' => 'Please specify the nature of injury/illness']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                        <?php $__errorArgs = ['injury_type_other'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="what_happened" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            What Happened <span class="text-red-500">*</span>
                        </label>
                        <textarea id="what_happened" name="what_happened" rows="4" required
                                  placeholder="Narrative description of how the incident occurred..."
                                  class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"><?php echo e(old('what_happened')); ?></textarea>
                        <?php $__errorArgs = ['what_happened'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- 6. Administrative Review -->
                <div class="space-y-4 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-600 pb-2 mb-4">6. Administrative Review</h3>

                    <div>
                        <label for="coordinator_id" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Coordinator
                        </label>
                        <div class="custom-select-wrapper">
                            <select id="coordinator_id" name="coordinator_id"
                                    class="custom-select mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select coordinator...</option>
                                <?php $__currentLoopData = $sheqUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" <?php echo e(old('coordinator_id') == $user->id ? 'selected' : ''); ?>>
                                        <?php echo e($user->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="search-overlay">
                                <input type="text" class="search-input" placeholder="Type to search coordinators...">
                                <div class="search-results"></div>
                            </div>
                        </div>
                        <?php $__errorArgs = ['coordinator_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="bcc_emails" class="block font-medium text-sm text-gray-800 dark:text-gray-200 mb-1">
                            Send BCC Email To
                        </label>
                        <div class="bcc-email-container">
                            <div class="bcc-dropdown-wrapper">
                                <div class="bcc-selected-users" id="bcc-selected-users">
                                    <span class="placeholder-text" id="bcc-placeholder">Select users to email...</span>
                                </div>
                                <div class="bcc-dropdown" id="bcc-dropdown">
                                    <div class="bcc-search-wrapper">
                                        <input type="text" id="bcc-search" class="bcc-search-input" placeholder="Search users...">
                                    </div>
                                    <div class="bcc-options" id="bcc-options">
                                        <?php $__currentLoopData = $allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="bcc-option" data-user-id="<?php echo e($user->id); ?>" data-user-name="<?php echo e($user->name); ?>" data-user-email="<?php echo e($user->email); ?>">
                                                <div class="bcc-option-checkbox">
                                                    <input type="checkbox" id="bcc-user-<?php echo e($user->id); ?>" value="<?php echo e($user->id); ?>">
                                                </div>
                                                <div class="bcc-option-details">
                                                    <div class="bcc-option-name"><?php echo e($user->name); ?></div>
                                                    <div class="bcc-option-email"><?php echo e($user->email); ?></div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="bcc_emails" id="bcc-emails-input" value="<?php echo e(old('bcc_emails')); ?>">
                        </div>
                        <?php $__errorArgs = ['bcc_emails'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="flex justify-center pt-2">
                    <button type="submit"
                            style="background-color: var(--primary-colour); border: 1px solid var(--primary-colour); color: white; padding: 0.5rem 1rem; border-radius: 0.375rem;">
                        <strong>Submit</strong>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .custom-select-wrapper {
            position: relative;
        }
        .custom-select {
            position: relative;
            z-index: 2;
            padding-right: 2.25rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: none !important;
            background-repeat: no-repeat !important;
        }
        .custom-select::-ms-expand {
            display: none;
        }
        .custom-select-wrapper::after {
            content: '▾';
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            pointer-events: none;
            font-size: 0.9rem;
            transition: transform 0.15s ease-in-out;
        }
        .custom-select-wrapper.overlay-open::after {
            transform: translateY(-50%) rotate(180deg);
        }

        .search-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 3;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }
        .search-overlay.active {
            opacity: 1;
            pointer-events: all;
        }
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background: white;
            font-size: 1rem;
            outline: none;
        }
        .search-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            max-height: 200px;
            overflow-y: auto;
            z-index: 10;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .search-result-item {
            padding: 0.75rem;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .search-result-item:hover {
            background: #f9fafb;
        }
        .search-result-item:last-child {
            border-bottom: none;
        }
        .search-result-item.dimmed {
            opacity: 0.5;
        }
        .employee-site-badge {
            font-size: 0.75rem;
            color: #6b7280;
            background: #f3f4f6;
            padding: 0.125rem 0.5rem;
            border-radius: 0.25rem;
            margin-left: 0.5rem;
        }

        .person-detail-container {
            transition: all 0.3s ease-in-out;
            overflow: hidden;
            opacity: 0;
            max-height: 0;
            padding-top: 0;
            margin-top: 0;
        }
        .person-detail-container.show {
            opacity: 1;
            max-height: 200px;
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .person-detail-contain {
            opacity: 0;
            max-height: 0;
        }

        .person-detail-contain.show {
            opacity: 1;
            max-height: 200px;
        }

        .person-name-input {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 2px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .person-name-input:focus {
            background: white;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            transform: translateY(-1px);
        }

        .body-parts-container {
            position: relative;
        }
        .body-parts-wrapper {
            position: relative;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background: white;
            min-height: 2.5rem;
            cursor: pointer;
        }
        .body-parts-selected {
            padding: 0.5rem;
            min-height: 2.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: flex-start;
        }
        .body-part-tag {
            background: #3b82f6;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .body-part-remove {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            line-height: 1;
            width: 1rem;
            height: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            opacity: 0.8;
        }
        .body-part-remove:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.2);
        }
        .body-parts-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 50;
            max-height: 20rem;
            overflow: hidden;
            display: none;
        }
        .body-parts-dropdown.active {
            display: block;
        }
        .body-parts-search-wrapper {
            padding: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .body-parts-search-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
            outline: none;
            font-size: 0.875rem;
        }
        .body-parts-search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .body-parts-options {
            max-height: 16rem;
            overflow-y: auto;
        }
        .body-part-option {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            gap: 0.75rem;
        }
        .body-part-option:hover {
            background: #f9fafb;
        }
        .body-part-option:last-child {
            border-bottom: none;
        }
        .body-part-option-checkbox input {
            width: 1rem;
            height: 1rem;
        }
        .body-part-option-details {
            flex: 1;
        }
        .body-part-option-name {
            font-weight: 500;
            font-size: 0.875rem;
            color: #1f2937;
        }

        .bcc-email-container {
            position: relative;
        }
        .bcc-dropdown-wrapper {
            position: relative;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background: white;
            min-height: 2.5rem;
            cursor: pointer;
        }
        .bcc-selected-users {
            padding: 0.5rem;
            min-height: 2.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: flex-start;
        }
        .placeholder-text {
            color: #9ca3af;
            font-size: 0.875rem;
            line-height: 1.5rem;
            align-self: center;
        }
        .bcc-user-tag {
            background: #3b82f6;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .bcc-user-remove {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            line-height: 1;
            width: 1rem;
            height: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            opacity: 0.8;
        }
        .bcc-user-remove:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.2);
        }
        .bcc-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 50;
            max-height: 20rem;
            overflow: hidden;
            display: none;
        }
        .bcc-dropdown.active {
            display: block;
        }
        .bcc-search-wrapper {
            padding: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .bcc-search-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
            outline: none;
            font-size: 0.875rem;
        }
        .bcc-search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .bcc-options {
            max-height: 16rem;
            overflow-y: auto;
        }
        .bcc-option {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            gap: 0.75rem;
        }
        .bcc-option:hover {
            background: #f9fafb;
        }
        .bcc-option:last-child {
            border-bottom: none;
        }
        .bcc-option-checkbox input {
            width: 1rem;
            height: 1rem;
        }
        .bcc-option-details {
            flex: 1;
        }
        .bcc-option-name {
            font-weight: 500;
            font-size: 0.875rem;
            color: #1f2937;
        }
        .bcc-option-email {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .file-upload-container {
            position: relative;
            border: 2px dashed #d1d5db;
            border-radius: 0.375rem;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .file-upload-container:hover {
            border-color: #9ca3af;
            background: #f9fafb;
        }
        .file-input {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .file-upload-display { pointer-events: none; }
        .upload-icon { width: 2rem; height: 2rem; color: #6b7280; margin: 0 auto 0.5rem; }
        .upload-text { display: block; font-weight: 500; color: #374151; margin-bottom: 0.25rem; }
        .upload-subtext { font-size: 0.875rem; color: #6b7280; }
        .file-list { margin-top: 1rem; }
        .file-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0.5rem 0.75rem; background: #f3f4f6; border-radius: 0.375rem;
            margin-bottom: 0.5rem; font-size: 0.875rem;
        }
        .file-remove {
            background: #ef4444; color: white; border: none; border-radius: 50%;
            width: 1.5rem; height: 1.5rem; cursor: pointer; font-size: 0.75rem;
        }
        .file-remove:hover { background: #dc2626; }

        @media (prefers-color-scheme: dark) {
            .custom-select-wrapper::after { color: #9ca3af; }
            .search-input { background: #374151; border-color: #4b5563; color: #e5e7eb; }
            .search-results { background: #374151; border-color: #4b5563; }
            .search-result-item { border-bottom-color: #4b5563; color: #e5e7eb; }
            .search-result-item:hover { background: #1f2937; }
            .employee-site-badge { background: #1f2937; color: #9ca3af; }
            .person-name-input { background: linear-gradient(135deg, #374151 0%, #1f2937 100%); border-color: #4b5563; color: #e5e7eb; }
            .person-name-input:focus { background: #374151; border-color: #4f46e5; }
            .body-parts-wrapper { background: #374151; border-color: #4b5563; }
            .body-parts-dropdown { background: #374151; border-color: #4b5563; }
            .body-part-option { border-bottom-color: #4b5563; }
            .body-part-option:hover { background: #1f2937; }
            .body-part-option-name { color: #e5e7eb; }
            .body-parts-search-input { background: #1f2937; border-color: #4b5563; color: #e5e7eb; }
            .bcc-dropdown-wrapper { background: #374151; border-color: #4b5563; }
            .bcc-dropdown { background: #374151; border-color: #4b5563; }
            .bcc-option { border-bottom-color: #4b5563; }
            .bcc-option:hover { background: #1f2937; }
            .bcc-option-name { color: #e5e7eb; }
            .bcc-option-email { color: #9ca3af; }
            .bcc-search-input { background: #1f2937; border-color: #4b5563; color: #e5e7eb; }
            .placeholder-text { color: #6b7280; }
            .file-upload-container:hover { background: #374151; }
            .upload-text { color: #e5e7eb; }
            .file-item { background: #4b5563; color: #e5e7eb; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const affectedPersonTypeSelect = document.getElementById('affected_person_type');
            const affectedEmployeeContainer = document.getElementById('affected_employee_container');
            const affectedPersonOtherContainer = document.getElementById('affected_person_other_container');
            const affectedEmployeeSelect = document.getElementById('affected_employee_id');
            const affectedPersonNameInput = document.getElementById('affected_person_name');

            const reportedByTypeSelect = document.getElementById('reported_by_type');
            const reportedEmployeeContainer = document.getElementById('reported_employee_container');
            const reportedByOtherContainer = document.getElementById('reported_by_other_container');
            const reportedEmployeeSelect = document.getElementById('reported_employee_id');
            const reportedByNameInput = document.getElementById('reported_by_name');

            function updateAffectedPersonDisplay() {
                const selectedValue = affectedPersonTypeSelect.value;
                affectedEmployeeContainer.classList.remove('show');
                affectedPersonOtherContainer.classList.remove('show');
                affectedEmployeeSelect.removeAttribute('required');
                affectedPersonNameInput.removeAttribute('required');

                if (selectedValue === 'Employee') {
                    setTimeout(() => {
                        affectedEmployeeContainer.classList.add('show');
                    }, 100);
                    affectedEmployeeSelect.setAttribute('required', 'required');
                    affectedPersonNameInput.value = '';
                } else if (selectedValue && selectedValue !== 'Employee') {
                    setTimeout(() => {
                        affectedPersonOtherContainer.classList.add('show');
                    }, 100);
                    affectedPersonNameInput.setAttribute('required', 'required');
                    affectedEmployeeSelect.value = '';
                }
            }

            function updateReportedByDisplay() {
                const selectedValue = reportedByTypeSelect.value;
                reportedEmployeeContainer.classList.remove('show');
                reportedByOtherContainer.classList.remove('show');
                reportedEmployeeSelect.removeAttribute('required');
                reportedByNameInput.removeAttribute('required');

                if (selectedValue === 'Employee') {
                    setTimeout(() => {
                        reportedEmployeeContainer.classList.add('show');
                    }, 100);
                    reportedEmployeeSelect.setAttribute('required', 'required');
                    reportedByNameInput.value = '';
                } else if (selectedValue && selectedValue !== 'Employee') {
                    setTimeout(() => {
                        reportedByOtherContainer.classList.add('show');
                    }, 100);
                    reportedByNameInput.setAttribute('required', 'required');
                    reportedEmployeeSelect.value = '';
                }
            }

            updateAffectedPersonDisplay();
            updateReportedByDisplay();

            affectedPersonTypeSelect.addEventListener('change', updateAffectedPersonDisplay);
            reportedByTypeSelect.addEventListener('change', updateReportedByDisplay);

            // NEW EMPLOYEE FILTERING - Shows all employees but dims those from other sites
            function updateEmployeeDisplay() {
                const branchSelect = document.getElementById('branch_id');
                const siteSelect = document.getElementById('site_id');
                const selectedBranchOption = branchSelect.options[branchSelect.selectedIndex];
                const selectedBranchCode = selectedBranchOption?.getAttribute('data-branch-code');
                const selectedSiteId = siteSelect.value;

                [affectedEmployeeSelect, reportedEmployeeSelect].forEach(employeeSelect => {
                    const options = Array.from(employeeSelect.querySelectorAll('option[data-site-id]'));
                    
                    // Separate employees into matching and non-matching
                    const matchingEmployees = [];
                    const otherEmployees = [];

                    options.forEach(opt => {
                        const employeeSiteId = opt.getAttribute('data-site-id');
                        const employeeBranchCode = opt.getAttribute('data-branch-code');
                        
                        let isMatch = true;
                        if (selectedSiteId || selectedBranchCode) {
                            if (selectedSiteId && employeeSiteId !== selectedSiteId) {
                                isMatch = false;
                            }
                            else if (selectedBranchCode && !selectedSiteId && employeeBranchCode !== selectedBranchCode) {
                                isMatch = false;
                            }
                        }

                        if (isMatch) {
                            matchingEmployees.push(opt);
                        } else {
                            otherEmployees.push(opt);
                        }
                    });

                    // Sort: matching employees first, then others
                    const placeholder = employeeSelect.querySelector('option[value=""]');
                    const sortedOptions = [placeholder, ...matchingEmployees, ...otherEmployees];

                    // Clear and rebuild
                    employeeSelect.innerHTML = '';
                    sortedOptions.forEach(opt => {
                        if (opt) employeeSelect.appendChild(opt);
                    });

                    // Make all options visible but mark non-matching as dimmed
                    options.forEach(opt => {
                        opt.style.display = '';
                        opt.classList.remove('hidden');
                        
                        const employeeSiteId = opt.getAttribute('data-site-id');
                        const employeeBranchCode = opt.getAttribute('data-branch-code');
                        let isMatch = true;
                        
                        if (selectedSiteId && employeeSiteId !== selectedSiteId) {
                            isMatch = false;
                        }
                        else if (selectedBranchCode && !selectedSiteId && employeeBranchCode !== selectedBranchCode) {
                            isMatch = false;
                        }

                        if (isMatch) {
                            opt.classList.remove('dimmed');
                        } else {
                            opt.classList.add('dimmed');
                        }
                    });
                });
            }

            // Handle Incident Type "Other" option
            const incidentTypeSelect = document.getElementById('incident_type_id');
            const incidentTypeOtherContainer = document.getElementById('incident_type_other_container');
            const incidentTypeOtherInput = document.getElementById('incident_type_other_description');

            function toggleIncidentTypeOther() {
                const isOther = incidentTypeSelect.value === 'other';
                if (isOther) {
                    incidentTypeOtherContainer.style.display = 'block';
                    incidentTypeOtherInput.setAttribute('required', 'required');
                } else {
                    incidentTypeOtherContainer.style.display = 'none';
                    incidentTypeOtherInput.removeAttribute('required');
                    incidentTypeOtherInput.value = '';
                }
            }

            toggleIncidentTypeOther();
            incidentTypeSelect.addEventListener('change', toggleIncidentTypeOther);
            incidentTypeSelect.addEventListener('input', toggleIncidentTypeOther);

            // Handle Injury Type "Other" option
            const injuryTypeSelect = document.getElementById('injury_type_id');
            const injuryTypeOtherContainer = document.getElementById('injury_type_other_container');
            const injuryTypeOtherInput = document.getElementById('injury_type_other');

            function toggleInjuryTypeOther() {
                const isOther = injuryTypeSelect.value === 'other';
                if (isOther) {
                    injuryTypeOtherContainer.style.display = 'block';
                    injuryTypeOtherInput.setAttribute('required', 'required');
                } else {
                    injuryTypeOtherContainer.style.display = 'none';
                    injuryTypeOtherInput.removeAttribute('required');
                    injuryTypeOtherInput.value = '';
                }
            }

            toggleInjuryTypeOther();
            injuryTypeSelect.addEventListener('change', toggleInjuryTypeOther);
            injuryTypeSelect.addEventListener('input', toggleInjuryTypeOther);

            // Body Parts Multi-Select Functionality
            const bodyPartsContainer = document.querySelector('.body-parts-wrapper');
            const bodyPartsSelected = document.getElementById('body-parts-selected');
            const bodyPartsDropdown = document.getElementById('body-parts-dropdown');
            const bodyPartsSearch = document.getElementById('body-parts-search');
            const bodyPartsOptions = document.getElementById('body-parts-options');
            const bodyPartsPlaceholder = document.getElementById('body-parts-placeholder');

            let selectedBodyParts = [];

            function renderSelectedBodyParts() {
                bodyPartsSelected.innerHTML = '';

                if (selectedBodyParts.length === 0) {
                    bodyPartsSelected.appendChild(bodyPartsPlaceholder);
                } else {
                    selectedBodyParts.forEach(bodyPart => {
                        const tag = document.createElement('div');
                        tag.className = 'body-part-tag';
                        tag.innerHTML = `
                            <span>${bodyPart.name}</span>
                            <button type="button" class="body-part-remove" onclick="removeBodyPart('${bodyPart.id}')">×</button>
                        `;
                        bodyPartsSelected.appendChild(tag);
                    });
                }

                document.querySelectorAll('.body-part-option input[type="checkbox"]').forEach(checkbox => {
                    const bodyPartId = checkbox.value;
                    checkbox.checked = selectedBodyParts.some(bodyPart => bodyPart.id === bodyPartId);
                });

                const hasOther = selectedBodyParts.some(bodyPart => bodyPart.id === 'other');
                toggleBodyPartOther({ checked: hasOther });
            }

            function toggleBodyPart(bodyPartId) {
                const option = document.querySelector(`[data-body-part-id="${bodyPartId}"]`);
                const bodyPartData = {
                    id: bodyPartId,
                    name: option.dataset.bodyPartName
                };

                const existingIndex = selectedBodyParts.findIndex(bodyPart => bodyPart.id === bodyPartId);

                if (existingIndex >= 0) {
                    selectedBodyParts.splice(existingIndex, 1);
                } else {
                    selectedBodyParts.push(bodyPartData);
                }

                renderSelectedBodyParts();
            }

            window.removeBodyPart = function(bodyPartId) {
                const index = selectedBodyParts.findIndex(bodyPart => bodyPart.id === bodyPartId);
                if (index >= 0) {
                    selectedBodyParts.splice(index, 1);
                    renderSelectedBodyParts();
                }
            };

            window.toggleBodyPartOther = function(checkbox) {
                const otherContainer = document.getElementById('body_part_other_container');
                const otherInput = document.getElementById('body_part_other');

                if (checkbox.checked) {
                    otherContainer.style.display = 'block';
                } else {
                    otherContainer.style.display = 'none';
                    otherInput.value = '';
                }
            };

            bodyPartsContainer.addEventListener('click', function(e) {
                e.stopPropagation();
                bodyPartsDropdown.classList.toggle('active');
                if (bodyPartsDropdown.classList.contains('active')) {
                    bodyPartsSearch.focus();
                }
            });

            bodyPartsOptions.addEventListener('click', function(e) {
                const option = e.target.closest('.body-part-option');
                if (option) {
                    const bodyPartId = option.dataset.bodyPartId;
                    const checkbox = option.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                    toggleBodyPart(bodyPartId);

                    if (bodyPartId === 'other') {
                        toggleBodyPartOther(checkbox);
                    }
                }
            });

            bodyPartsSearch.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                document.querySelectorAll('.body-part-option').forEach(option => {
                    const name = option.dataset.bodyPartName.toLowerCase();
                    if (name.includes(searchTerm)) {
                        option.style.display = 'flex';
                    } else {
                        option.style.display = 'none';
                    }
                });
            });

            document.addEventListener('click', function(e) {
                if (!bodyPartsContainer.contains(e.target)) {
                    bodyPartsDropdown.classList.remove('active');
                }
            });

            bodyPartsDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            document.querySelectorAll('input[name="body_part_id[]"]:checked').forEach(checkbox => {
                const option = document.querySelector(`[data-body-part-id="${checkbox.value}"]`);
                if (option) {
                    selectedBodyParts.push({
                        id: checkbox.value,
                        name: option.dataset.bodyPartName
                    });
                }
            });
            renderSelectedBodyParts();

            // Custom select search overlays - UPDATED to show employee site badges
            document.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
                const select = wrapper.querySelector('.custom-select');
                const overlay = wrapper.querySelector('.search-overlay');
                const input = wrapper.querySelector('.search-input');
                const results = wrapper.querySelector('.search-results');

                if (!select || !overlay || !input || !results) return;

                function openOverlay() {
                    overlay.classList.add('active');
                    wrapper.classList.add('overlay-open');
                    input.value = '';
                    buildResults('');
                    setTimeout(() => input.focus(), 0);
                }

                function closeOverlay() {
                    overlay.classList.remove('active');
                    wrapper.classList.remove('overlay-open');
                }

                function buildResults(term) {
                    const search = term.toLowerCase();
                    const options = Array.from(select.querySelectorAll('option'));

                    results.innerHTML = '';
                    options.forEach(opt => {
                        if (opt.value === '') return;
                        if (opt.classList.contains('hidden') || opt.style.display === 'none') return;

                        const txt = (opt.textContent || '').toLowerCase();

                        if (txt.includes(search)) {
                            const item = document.createElement('div');
                            item.className = 'search-result-item';
                            
                            // Add dimmed class if option has it
                            if (opt.classList.contains('dimmed')) {
                                item.classList.add('dimmed');
                            }

                            // Show employee site badge if available
                            const siteName = opt.getAttribute('data-site-name');
                            if (siteName) {
                                item.innerHTML = `
                                    <span>${opt.textContent}</span>
                                    <span class="employee-site-badge">${siteName}</span>
                                `;
                            } else {
                                item.textContent = opt.textContent;
                            }
                            
                            item.addEventListener('click', () => {
                                select.value = opt.value;
                                select.dispatchEvent(new Event('change', { bubbles: true }));
                                select.dispatchEvent(new Event('input', { bubbles: true }));

                                if (select.id === 'affected_person_type') {
                                    updateAffectedPersonDisplay();
                                } else if (select.id === 'reported_by_type') {
                                    updateReportedByDisplay();
                                } else if (select.id === 'injury_type_id') {
                                    toggleInjuryTypeOther();
                                } else if (select.id === 'incident_type_id') {
                                    toggleIncidentTypeOther();
                                }

                                closeOverlay();
                            });
                            results.appendChild(item);
                        }
                    });
                }

                const downHandler = (e) => {
                    e.preventDefault();
                    openOverlay();
                };
                select.addEventListener('pointerdown', downHandler, { passive: false });
                select.addEventListener('mousedown', downHandler, { passive: false });

                select.addEventListener('keydown', (e) => {
                    const keys = ['Enter', ' ', 'Spacebar', 'ArrowDown'];
                    if (keys.includes(e.key)) {
                        e.preventDefault();
                        openOverlay();
                    }
                });

                input.addEventListener('input', (e) => buildResults(e.target.value));

                document.addEventListener('pointerdown', (e) => {
                    if (!wrapper.contains(e.target)) closeOverlay();
                });

                input.addEventListener('blur', () => setTimeout(() => closeOverlay(), 150));
            });

            // BCC Email Functionality
            const bccContainer = document.querySelector('.bcc-dropdown-wrapper');
            const bccSelectedUsers = document.getElementById('bcc-selected-users');
            const bccDropdown = document.getElementById('bcc-dropdown');
            const bccSearch = document.getElementById('bcc-search');
            const bccOptions = document.getElementById('bcc-options');
            const bccPlaceholder = document.getElementById('bcc-placeholder');
            const bccInput = document.getElementById('bcc-emails-input');

            let selectedUsers = [];

            if (bccInput.value) {
                try {
                    selectedUsers = JSON.parse(bccInput.value);
                    renderSelectedUsers();
                } catch (e) {
                    selectedUsers = [];
                }
            }

            function renderSelectedUsers() {
                bccSelectedUsers.innerHTML = '';

                if (selectedUsers.length === 0) {
                    bccSelectedUsers.appendChild(bccPlaceholder);
                } else {
                    selectedUsers.forEach(user => {
                        const tag = document.createElement('div');
                        tag.className = 'bcc-user-tag';
                        tag.innerHTML = `
                            <span>${user.name}</span>
                            <button type="button" class="bcc-user-remove" onclick="removeUser(${user.id})">×</button>
                        `;
                        bccSelectedUsers.appendChild(tag);
                    });
                }

                bccInput.value = JSON.stringify(selectedUsers.map(user => user.id));

                document.querySelectorAll('.bcc-option input[type="checkbox"]').forEach(checkbox => {
                    const userId = parseInt(checkbox.value);
                    checkbox.checked = selectedUsers.some(user => user.id === userId);
                });
            }

            function toggleUser(userId) {
                const option = document.querySelector(`[data-user-id="${userId}"]`);
                const userData = {
                    id: parseInt(userId),
                    name: option.dataset.userName,
                    email: option.dataset.userEmail
                };

                const existingIndex = selectedUsers.findIndex(user => user.id === userId);

                if (existingIndex >= 0) {
                    selectedUsers.splice(existingIndex, 1);
                } else {
                    selectedUsers.push(userData);
                }

                renderSelectedUsers();
            }

            window.removeUser = function(userId) {
                const index = selectedUsers.findIndex(user => user.id === userId);
                if (index >= 0) {
                    selectedUsers.splice(index, 1);
                    renderSelectedUsers();
                }
            };

            bccContainer.addEventListener('click', function(e) {
                e.stopPropagation();
                bccDropdown.classList.toggle('active');
                if (bccDropdown.classList.contains('active')) {
                    bccSearch.focus();
                }
            });

            bccOptions.addEventListener('click', function(e) {
                const option = e.target.closest('.bcc-option');
                if (option) {
                    const userId = option.dataset.userId;
                    const checkbox = option.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                    toggleUser(userId);
                }
            });

            bccSearch.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                document.querySelectorAll('.bcc-option').forEach(option => {
                    const name = option.dataset.userName.toLowerCase();
                    const email = option.dataset.userEmail.toLowerCase();
                    if (name.includes(searchTerm) || email.includes(searchTerm)) {
                        option.style.display = 'flex';
                    } else {
                        option.style.display = 'none';
                    }
                });
            });

            document.addEventListener('click', function(e) {
                if (!bccContainer.contains(e.target)) {
                    bccDropdown.classList.remove('active');
                }
            });

            bccDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // File upload list
            const fileInput = document.getElementById('attachments');
            const fileList = document.getElementById('file-list');
            if (fileInput && fileList) {
                fileInput.addEventListener('change', function() {
                    fileList.innerHTML = '';
                    Array.from(this.files).forEach((file, index) => {
                        const row = document.createElement('div');
                        row.className = 'file-item';
                        row.innerHTML = `
                            <span>${file.name} (${(file.size/1024/1024).toFixed(2)} MB)</span>
                            <button type="button" class="file-remove" onclick="removeFile(${index})">×</button>
                        `;
                        fileList.appendChild(row);
                    });
                });
            }

            // Filter sites by branch
            document.getElementById('branch_id').addEventListener('change', function() {
                const selectedBranchOption = this.options[this.selectedIndex];
                const selectedBranchCode = selectedBranchOption ? selectedBranchOption.getAttribute('data-branch-code') : '';
                const siteSelect = document.getElementById('site_id');
                const options = siteSelect.querySelectorAll('option[data-branch-code]');

                siteSelect.value = '';

                options.forEach(opt => {
                    const siteBranchCode = opt.getAttribute('data-branch-code');
                    const matches = !selectedBranchCode || siteBranchCode === selectedBranchCode;
                    if (matches) {
                        opt.style.display = '';
                        opt.classList.remove('hidden');
                    } else {
                        opt.style.display = 'none';
                        opt.classList.add('hidden');
                    }
                });

                updateEmployeeDisplay();
            });

            document.getElementById('site_id').addEventListener('change', updateEmployeeDisplay);

            renderSelectedUsers();
        });

        function removeFile(index) {
            const input = document.getElementById('attachments');
            if (!input || !input.files?.length) return;
            const dt = new DataTransfer();
            Array.from(input.files).forEach((file, i) => {
                if (i !== index) dt.items.add(file);
            });
            input.files = dt.files;
            input.dispatchEvent(new Event('change', { bubbles: true }));
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
<?php endif; ?><?php /**PATH /home/sites/29a/4/4aab0e4a5e/public_html/laravel12/resources/views/incidents/create.blade.php ENDPATH**/ ?>