<!DOCTYPE html>
<html <?php echo e(language_attributes()); ?>>
<head>
  <meta charset="<?php echo e(bloginfo('charset')); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="theme-color" content="#6d9aea">
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,800" rel="stylesheet">
  <?php echo e(wp_head()); ?>

</head>
<body <?php echo e(body_class()); ?>>
    <div id="app">
        <div 
            class="site-wrapper" 
            v-bind:class="{
                'full-height': footerStuck, 
                'scrolling': isScrolling,
                'mobile-menu-open': mobileMenuOpen 
            }">
            <?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php echo $__env->yieldContent('content'); ?>

            <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <?php echo $__env->yieldContent('modals'); ?>
    </div>

    <?php echo e(wp_footer()); ?>

    <?php echo $__env->yieldContent('footer-scripts'); ?>
</body>
</html><?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/layouts/main.blade.php ENDPATH**/ ?>