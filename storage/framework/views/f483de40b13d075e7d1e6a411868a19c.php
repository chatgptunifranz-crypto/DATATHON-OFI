<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>

    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    
    <?php echo $__env->yieldContent('meta_tags'); ?>

    
    <title>
        <?php echo $__env->yieldContent('title_prefix', config('adminlte.title_prefix', '')); ?>
        <?php echo $__env->yieldContent('title', config('adminlte.title', 'AdminLTE 3')); ?>
        <?php echo $__env->yieldContent('title_postfix', config('adminlte.title_postfix', '')); ?>
    </title>

    
    <?php echo $__env->yieldContent('adminlte_css_pre'); ?>

    
    <?php if(config('adminlte.enabled_laravel_mix', false)): ?>
        <link rel="stylesheet" href="<?php echo e(mix(config('adminlte.laravel_mix_css_path', 'css/app.css'))); ?>">
    <?php else: ?>
        <?php switch(config('adminlte.laravel_asset_bundling', false)):
            case ('mix'): ?>
                <link rel="stylesheet" href="<?php echo e(mix(config('adminlte.laravel_css_path', 'css/app.css'))); ?>">
            <?php break; ?>

            <?php case ('vite'): ?>
                <?php echo app('Illuminate\Foundation\Vite')([config('adminlte.laravel_css_path', 'resources/css/app.css'), config('adminlte.laravel_js_path', 'resources/js/app.js')]); ?>
            <?php break; ?>

            <?php case ('vite_js_only'): ?>
                <?php echo app('Illuminate\Foundation\Vite')(config('adminlte.laravel_js_path', 'resources/js/app.js')); ?>
            <?php break; ?>

            <?php default: ?>
                <link rel="stylesheet" href="<?php echo e(asset('vendor/fontawesome-free/css/all.min.css')); ?>">
                <link rel="stylesheet" href="<?php echo e(asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css')); ?>">
                <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/dist/css/adminlte.min.css')); ?>">

                <?php if(config('adminlte.google_fonts.allowed', true)): ?>
                    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
                <?php endif; ?>
        <?php endswitch; ?>
    <?php endif; ?>

    
    <?php echo $__env->make('adminlte::plugins', ['type' => 'css'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php if(config('adminlte.livewire')): ?>
        <?php if(intval(app()->version()) >= 7): ?>
            @livewireStyles
        <?php else: ?>
            <livewire:styles />
        <?php endif; ?>
    <?php endif; ?>

    
    <?php echo $__env->yieldContent('adminlte_css'); ?>

    
    <?php if(config('adminlte.use_ico_only')): ?>
        <link rel="shortcut icon" href="<?php echo e(asset('favicons/favicon.ico')); ?>" />
    <?php elseif(config('adminlte.use_full_favicon')): ?>
        <link rel="shortcut icon" href="<?php echo e(asset('favicons/favicon.ico')); ?>" />
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(asset('favicons/apple-icon-57x57.png')); ?>">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo e(asset('favicons/apple-icon-60x60.png')); ?>">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo e(asset('favicons/apple-icon-72x72.png')); ?>">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(asset('favicons/apple-icon-76x76.png')); ?>">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo e(asset('favicons/apple-icon-114x114.png')); ?>">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo e(asset('favicons/apple-icon-120x120.png')); ?>">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo e(asset('favicons/apple-icon-144x144.png')); ?>">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo e(asset('favicons/apple-icon-152x152.png')); ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('favicons/apple-icon-180x180.png')); ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('favicons/favicon-16x16.png')); ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('favicons/favicon-32x32.png')); ?>">
        <link rel="icon" type="image/png" sizes="96x96" href="<?php echo e(asset('favicons/favicon-96x96.png')); ?>">
        <link rel="icon" type="image/png" sizes="192x192" href="<?php echo e(asset('favicons/android-icon-192x192.png')); ?>">
        <link rel="manifest" crossorigin="use-credentials" href="<?php echo e(asset('favicons/manifest.json')); ?>">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?php echo e(asset('favicon/ms-icon-144x144.png')); ?>">
    <?php endif; ?>

</head>

<body class="<?php echo $__env->yieldContent('classes_body'); ?>" <?php echo $__env->yieldContent('body_data'); ?>>

    
    <?php echo $__env->yieldContent('body'); ?>

    
    <?php if(config('adminlte.enabled_laravel_mix', false)): ?>
        <script src="<?php echo e(mix(config('adminlte.laravel_mix_js_path', 'js/app.js'))); ?>"></script>
    <?php else: ?>
        <?php switch(config('adminlte.laravel_asset_bundling', false)):
            case ('mix'): ?>
                <script src="<?php echo e(mix(config('adminlte.laravel_js_path', 'js/app.js'))); ?>"></script>
            <?php break; ?>

            <?php case ('vite'): ?>
            <?php case ('vite_js_only'): ?>
            <?php break; ?>

            <?php default: ?>
                <script src="<?php echo e(asset('vendor/jquery/jquery.min.js')); ?>"></script>
                <script src="<?php echo e(asset('vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
                <script src="<?php echo e(asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js')); ?>"></script>
                <script src="<?php echo e(asset('vendor/adminlte/dist/js/adminlte.min.js')); ?>"></script>
        <?php endswitch; ?>
    <?php endif; ?>

    
    <?php echo $__env->make('adminlte::plugins', ['type' => 'js'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php if(config('adminlte.livewire')): ?>
        <?php if(intval(app()->version()) >= 7): ?>
            @livewireScripts
        <?php else: ?>
            <livewire:scripts />
        <?php endif; ?>
    <?php endif; ?>

    
    <?php echo $__env->yieldContent('adminlte_js'); ?>

</body>

</html>
<?php /**PATH C:\laragon\www\integrador\vendor\jeroennoten\laravel-adminlte\src/../resources/views/master.blade.php ENDPATH**/ ?>