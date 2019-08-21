<?php $__env->startSection('content'); ?>

<?php if(have_posts()): ?>
    <?php while(have_posts()): ?>
        <?php echo e(the_post()); ?>

        <?php echo $__env->make('partials.mast', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <main role="main" class="pb-5">
            <div class="container">
                <article class="support">

                    <div class="row align-items-center">

                        <div class="col-12 col-lg-auto pl-xl-5 order-lg-2">
                            <div class="d-flex flex-lg-column flex-wrap align-items-center align-lg-items-start">
                                <div class="col-md-6 col-lg-auto">
                                <?php echo $__env->make('listing.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <div class="col-md-6 col-lg-auto text-center text-md-left">
                                <?php echo $__env->make('listing.actionbuttons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>   
                            </div>
                        </div>

                        <div class="col-12 col-lg order-lg-1 flex-grow-1">
                            <?php echo $__env->make('listing.mainphoto', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                    </div>
                    <div class="row">
                        
                        <div class="col-12 col-lg pt-lg-2">
                            <p><?php echo e($listing->remarks); ?></p>
                        </div>

                        <div class="col-12 col-lg-auto pb-4">
                            <div class="p-4 d-flex flex-column align-items-center">
                            <h3 class="text-muted">Share this property</h3>
                            <?php echo do_shortcode('[Sassy_Social_Share url="'. $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"] .'"]'); ?>

                            </div>
                        </div>

                    </div>
                    
                    <photo-gallery 
                        mls-account="<?php echo e($listing->mls_account); ?>"
                        @closeviewer="closeGallery"
                        @openviewer="openGallery"
                        :viewer-state="galleryIsOpen"
                        class="d-none d-sm-block mt-4 mb-5"
                        virtual-tour='<?php echo e($listing->virtual_tour); ?>' 
                        :data-photos='<?php echo e(json_encode($listing->media_objects->data)); ?>'
                        item-class="col-sm-6 col-md-4 col-lg-3 col-xl-2" ></photo-gallery>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <?php echo $__env->make('listing.details', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="col-md-6 mb-4">
                            <?php echo $__env->make('listing.construction', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <div class="col-md-5 mb-4">
                            <?php echo $__env->make('listing.location', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="col mb-4">
						    <?php echo $__env->make('listing.map', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>

                    <?php echo $__env->make('partials.disclaimer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    
                </article>
            </div>
        </main>
    <?php endwhile; ?>
<?php else: ?>
    <?php echo $__env->make('pages.404', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
    <portal-target name="modal-<?php echo e($listing->mls_account); ?>"></portal-target>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/pages/listing.blade.php ENDPATH**/ ?>