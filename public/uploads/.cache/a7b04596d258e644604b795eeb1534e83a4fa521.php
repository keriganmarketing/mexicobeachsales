<?php $__env->startSection('content'); ?>
<?php if(have_posts()): ?>
    <?php while(have_posts()): ?>
        <?php echo e(the_post()); ?>

                
        <?php if(get_theme_mod('header_feature') == 'slider'): ?>
            <?php echo $__env->make('partials.slider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        <?php if(get_theme_mod('header_feature') == 'main-image'): ?>
            <?php echo $__env->make('partials.headerimage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        <?php if(get_theme_mod('header_feature') == 'background-video'): ?>
            <?php echo $__env->make('partials.video', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <a class="down-arrow" v-scroll-to="'#main'" ><i class="fa fa-chevron-down" aria-hidden="true"></i></a>
        <main role="main" id="main" class="py-5">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <?php if($headshot != ''): ?>
                        <div class="col-6 col-sm-4 col-lg-3 mb-4" >
                            <img src="<?php echo e($headshot); ?>" class="img-fluid" alt="<?php echo e(get_field('agent_name','option')); ?>">
                        </div>
                    <?php endif; ?>
                    <div class="col-sm-8 col-lg-9">
                        <article class="front text-center text-sm-left">
                            <h1><?php echo e(the_title()); ?></h1>
                            
                            <?php echo e(the_content()); ?>


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
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/pages/front.blade.php ENDPATH**/ ?>