<?php $__env->startSection('content'); ?>

<?php if(have_posts()): ?>
    <?php while(have_posts()): ?>
        <?php echo e(the_post()); ?>

        <?php echo $__env->make('partials.mast', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <main role="main" class="pb-5">
            
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <article class="support">
                            <header>
                            </header>

                            <?php echo e(the_content()); ?>

                            <p>&mdash; <?php echo e(get_field('byline')); ?></p>
                            <p class="mt-5"><a class="btn btn-primary" href="/about-zach/testimonials/#review-<?php echo e(get_the_ID()); ?>">Back to testimonials</a></p>
                        </article>
                    </div>

                </div>
            </div>

        </main>
    <?php endwhile; ?>
<?php else: ?>
    <?php echo $__env->make('pages.404', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/pages/testimonial.blade.php ENDPATH**/ ?>