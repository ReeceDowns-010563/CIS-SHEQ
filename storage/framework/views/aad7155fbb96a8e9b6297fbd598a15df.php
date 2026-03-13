<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <!-- Dynamic Favicon -->
    <?php if($branding && $branding->favicon): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset($branding->favicon)); ?>">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset($branding->favicon)); ?>">
    <?php else: ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <?php endif; ?>

    <title><?php echo e(config('app.name')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans text-gray-900 antialiased">
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <!-- Logo -->
    <div class="shrink-0 flex items-center">
        <a href="<?php echo e(route('dashboard')); ?>">
            <?php if($branding && $branding->light_logo): ?>
                <img src="<?php echo e(asset($branding->light_logo)); ?>" alt="Company Logo" class="h-9 w-auto block dark:hidden">
            <?php else: ?>
                <img src="<?php echo e(asset('images/logo-light.png')); ?>" alt="Company Logo" class="h-9 w-auto block dark:hidden">
            <?php endif; ?>

            <?php if($branding && $branding->dark_logo): ?>
                <img src="<?php echo e(asset($branding->dark_logo)); ?>" alt="Company Logo" class="h-9 w-auto hidden dark:block">
            <?php else: ?>
                <img src="<?php echo e(asset('images/logo-dark.png')); ?>" alt="Company Logo" class="h-9 w-auto hidden dark:block">
            <?php endif; ?>
        </a>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        <?php echo e($slot); ?>

    </div>
</div>
</body>
</html>
<?php /**PATH /home/sites/29a/4/4aab0e4a5e/public_html/laravel12/resources/views/layouts/guest.blade.php ENDPATH**/ ?>