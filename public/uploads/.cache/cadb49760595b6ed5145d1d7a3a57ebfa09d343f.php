<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.mast', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<main role="main" class="pb-5">
    <div class="container">
        <article class="support">
            <header>
                <h1><?php echo e($headline != '' ? $headline : 'My Blog'); ?></h1>
            </header>
        </article>
        <?php if(have_posts()): ?>
            <div class="card-columns">
            <?php while(have_posts()): ?>
                <?php echo $__env->make('partials.article', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endwhile; ?>
            </div>
        <?php else: ?>
            <?php echo $__env->make('pages.404', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/pages/home.blade.php ENDPATH**/ ?>