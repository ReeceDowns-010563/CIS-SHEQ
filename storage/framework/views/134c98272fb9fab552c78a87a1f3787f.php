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
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Edit Feature</h2>
     <?php $__env->endSlot(); ?>

    <style>
        /* Professional form styling (copied and renamed where needed from your user edit styles) */
        .form-container {
            max-width: 32rem;
            margin: 0 auto;
            margin-top: 1.5rem;
            background-color: white;
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            color: #374151;
        }
        @media (prefers-color-scheme: dark) {
            .form-container {
                background-color: #1f2937;
                color: #e5e7eb;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.25);
            }
        }
        .error-container {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 0.5rem;
        }
        @media (prefers-color-scheme: dark) {
            .error-container {
                background-color: rgba(185, 28, 28, 0.1);
                border-color: #dc2626;
                color: #f87171;
            }
        }
        .error-list {
            list-style-type: disc;
            padding-left: 1.25rem;
            margin-top: 0.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #374151;
        }
        @media (prefers-color-scheme: dark) {
            .form-label {
                color: #d1d5db;
            }
        }
        .form-input, .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: white;
            color: #111827;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-colour);
            box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.1);
        }
        .form-input:hover, .form-select:hover {
            border-color: #9ca3af;
        }
        @media (prefers-color-scheme: dark) {
            .form-input, .form-select {
                background-color: #374151;
                border-color: #4b5563;
                color: #e5e7eb;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
            }
            .form-input:focus, .form-select:focus {
                border-color: var(--primary-colour);
                box-shadow: 0 0 0 3px rgba(167, 98, 44, 0.2);
            }
            .form-input:hover, .form-select:hover {
                border-color: #6b7280;
            }
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            gap: 1rem;
        }
        .cancel-btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            color: white;
            background-color: #6b7280;
            border: 2px solid #6b7280;
            border-radius: 0.5rem;
            transition: all 0.15s ease-in-out;
            cursor: pointer;
        }
        .cancel-btn:hover {
            background-color: #4b5563;
            border-color: #4b5563;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        }
        .update-btn {
            padding: 0.75rem 2rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: white;
            background-color: var(--primary-colour);
            border: 2px solid var(--primary-colour);
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .update-btn:hover {
            background-color: #924f25;
            border-color: #924f25;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        }
        .update-btn:active {
            transform: translateY(0);
        }
        .helper-text {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        @media (prefers-color-scheme: dark) {
            .helper-text {
                color: #9ca3af;
            }
        }
        .form-title {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
        }
        @media (prefers-color-scheme: dark) {
            .form-title {
                border-bottom-color: #374151;
            }
        }
        @media (max-width: 640px) {
            .form-container {
                margin: 1rem;
                padding: 1.5rem;
            }
            .button-container {
                flex-direction: column;
                width: 100%;
            }
            .cancel-btn, .update-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>

    <div class="py-10 px-4">
        <div class="form-container">
            <div class="form-title">
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Edit Feature</h3>
            </div>

            <?php if($errors->any()): ?>
                <div class="error-container">
                    <strong>Please fix these errors:</strong>
                    <ul class="error-list">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('settings.features.update', $feature)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <!-- Feature Key -->
                <div class="form-group">
                    <label for="key" class="form-label">Feature Key <span style="font-weight:400;">(unique, e.g. "settings")</span></label>
                    <input
                        id="key"
                        name="key"
                        type="text"
                        required
                        value="<?php echo e(old('key', $feature->key)); ?>"
                        class="form-input"
                        placeholder="E.g. settings, complaints"
                    />
                </div>

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Feature Name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        required
                        value="<?php echo e(old('name', $feature->name)); ?>"
                        class="form-input"
                        placeholder="Enter feature name"
                    />
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <input
                        id="description"
                        name="description"
                        type="text"
                        value="<?php echo e(old('description', $feature->description)); ?>"
                        class="form-input"
                        placeholder="Short description (optional)"
                    />
                </div>

                <!-- Allowed Roles -->
                <div class="form-group">
                    <label for="roles" class="form-label">Allowed Roles</label>
                    <select
                        id="roles"
                        name="roles[]"
                        class="form-select"
                        multiple
                        required
                        style="min-height: 3rem"
                    >
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($role->id); ?>"
                                <?php echo e(in_array($role->id, old('roles', $feature->roles->pluck('id')->toArray())) ? 'selected' : ''); ?>>
                                <?php echo e($role->display_name ?? ucfirst($role->name)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="helper-text">Hold Ctrl (Windows) or Cmd (Mac) to select multiple roles.</div>
                </div>

                <div class="button-container">
                    <a href="<?php echo e(route('settings.features.index')); ?>" class="cancel-btn">
                        Cancel
                    </a>
                    <button type="submit" class="update-btn">
                        Update Feature
                    </button>
                </div>
            </form>
        </div>
    </div>
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
<?php /**PATH /home/sites/29a/4/4aab0e4a5e/public_html/laravel12/resources/views/settings/features/edit.blade.php ENDPATH**/ ?>