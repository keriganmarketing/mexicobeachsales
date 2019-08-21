<?php $__env->startSection('content'); ?>

<?php if(have_posts()): ?>
    <?php while(have_posts()): ?>
        <?php echo e(the_post()); ?>

        <?php echo $__env->make('partials.mast', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <main role="main" class="pb-5">
            <div class="container-wide">
                <article class="support">
                    <header class="pt-0 pt-xl-5 text-center text-md-left">
                        <h1><?php echo e(the_title()); ?></h1>
                    </header>
                    <?php echo e(the_content()); ?>

                </article>

                <?php if(count($results) > 0): ?>
                    <hr>
                    <div class="row justify-content-between mb-4">
                        <div class="col-auto">
                            <small class="text-muted">
                                Showing <?php echo e($numResults); ?> properties
                            </small>
                        </div>
                        <div class="col-auto text-md-right">
                            <sort-form field-value="<?php echo e($currentSort); ?>" :search-terms='<?php echo e($currentRequest); ?>' class="sort-form" ></sort-form>
                        </div>
                    </div>
                    <div class="row">
                        <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $miniListing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 col-lg-4 col-xl-3 mb-4 mt-1" >
                            <?php echo $__env->make('partials.minilisting', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="pager d-flex justify-content-center my-2">
                        
                    </div>

                    <?php echo $__env->make('partials.disclaimer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php else: ?>

                    <p>There were no properties found using your search criteria. Please broaden your search and try again.</p>

                <?php endif; ?>

            </div>

        </main>
    <?php endwhile; ?>
<?php else: ?>
    <?php echo $__env->make('pages.404', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/pages/mylistings.blade.php ENDPATH**/ ?>