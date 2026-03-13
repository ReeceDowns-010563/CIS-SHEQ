<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">


    <title><?php echo e(config('app.name')); ?></title>

    <!-- Dynamic Favicon -->
    <?php if($branding && $branding->favicon): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset($branding->favicon)); ?>">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset($branding->favicon)); ?>">
    <?php else: ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <?php endif; ?>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Dynamic CSS Variables for Branding Colors -->
    <?php if($branding): ?>
        <style>
            :root {
                --primary-colour: <?php echo e($branding->primary_colour ?? '#a7622c'); ?>;
                --secondary-colour: <?php echo e($branding->secondary_colour ?? '#f97316'); ?>;
            }
        </style>
    <?php endif; ?>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page Heading -->
    <?php if(isset($header)): ?>
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <?php echo e($header); ?>

            </div>
        </header>
    <?php endif; ?>

    <!-- Page Content -->
    <main>
        <?php echo e($slot); ?>

    </main>
</div>
</body>
</html>
<?php /**PATH C:\Users\reece.downs\Downloads\fm-selection-j560679\public_html\laravel12\resources\views/layouts/app.blade.php ENDPATH**/ ?>