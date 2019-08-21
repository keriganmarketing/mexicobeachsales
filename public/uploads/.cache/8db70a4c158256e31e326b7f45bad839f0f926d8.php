<?php $__env->startSection('content'); ?>

<?php if(have_posts()): ?>
    <?php while(have_posts()): ?>
        <?php echo e(the_post()); ?>

        <?php echo $__env->make('partials.mast', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <main role="main" class="pb-5">
            <div class="container">
                <article class="support">
                    <header class="pt-0 pt-xl-5 text-center text-md-left">
                        <h1><?php echo e(the_title()); ?></h1>
                    </header>
                    <?php echo e(the_content()); ?>

                </article>

                <?php if(count($listings) > 0): ?>

                    <?php $__currentLoopData = $listings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $miniListing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <hr>
                    <h2 class="display-3 pt-4">
                    #<?php echo e($miniListing->menu_order); ?>: <?php echo e($miniListing->post_title); ?> - <?php echo e($miniListing->sub_area); ?></h2>
                    <?php if(date('Ymd', strtotime($miniListing->list_date)) >= date('Ymd', strtotime('-10 days'))): ?>
                        <h3 style="font-size:16px;" class="badge badge-info d-inline-block mr-2">Just Listed</h3>
                    <?php endif; ?>
                    <?php if($miniListing->status == 'Sold/Closed'): ?>
                        <h3 style="font-size:16px;" class="badge badge-info d-inline-block mr-2">Sold on <?php echo date( 'M j, Y', strtotime( $miniListing->sold_on ) ); ?> 
                                    for $<?php echo number_format( $miniListing->sold_for ); ?></h3>
                    <?php endif; ?>
                    <?php if($miniListing->status == 'Contingent'): ?>
                        <h3 style="font-size:16px;" class="badge badge-info d-inline-block mr-2">SALE CONTINGENT</h3>
                    <?php endif; ?>
                    <?php if($miniListing->original_list_price > $miniListing->price && $miniListing->status == 'Active' && $miniListing->original_list_price != 0): ?>
                        <h3 style="font-size:16px;" class="badge badge-danger d-inline-block mr-2">REDUCED <span style="text-decoration:line-through">$<?php echo number_format( $miniListing->original_list_price ); ?></span> <strong>$<?php echo number_format( $miniListing->price); ?></strong></h3>
                    <?php endif; ?>

                    <h3 style="font-size:16px;" class="badge badge-secondary d-inline-block mr-2">MLS# <?php echo e($miniListing->mls_account); ?></h3>

                    <div class="row mb-5 pt-4">
                        <div class="col-md-4">
                            <img src="<?php echo e($miniListing->media_objects->data[0]->url); ?>" class="img-fluid shadow" >
                            <photo-gallery 
                            :mls-account="<?php echo e($miniListing->mls_account); ?>"
                            @closeviewer="closeGallery"
                            @openviewer="openGallery"
                            class="d-none d-sm-block"
                            :viewer-state="galleryIsOpen"
                            virtual-tour='<?php echo e($miniListing->virtual_tour); ?>' 
                            :data-photos='<?php echo e(json_encode($miniListing->media_objects->data)); ?>'
                            item-class="col-sm-6"
                            :limit="5" ></photo-gallery>
                        </div>
                        <div class="col-md-8">

                            <div class="pt-4 row">
                                <div class="col-12 col-lg-auto">
                                    <a class="btn btn-block d-sm-inline-block btn-primary mb-4" href="/listing/<?php echo e($miniListing->mls_account); ?>" >View Listing Details</a> 
                                </div>
                                <?php if($miniListing->virtual_tour != ''): ?>
                                <div class="col-6 col-lg-auto">
                                    <a class="btn btn-block btn-secondary mb-4" target="_blank" href="//<?php echo e($miniListing->virtual_tour); ?>" >Virtual Tour</a> 
                                </div>
                                <?php endif; ?>
                                <?php if(count($miniListing->media_objects->data ) > 1): ?>
                                <div class="col-6 col-lg-auto">
                                    <a @click="openGallery([<?php echo e($miniListing->mls_account); ?>])" class="btn btn-block btn-secondary mb-4 pointer text-white" >Photos (<?php echo e(count($miniListing->media_objects->data )); ?>)</a>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="card">
                                <table class="table table-striped m-0">
                                <tr><td>Price</td><td>$<?php echo e(number_format($miniListing->price)); ?></td></tr>
                                
                                <?php if($miniListing->full_address != ''): ?>
                                    <tr><td>Address</td><td><?php echo e($miniListing->full_address); ?></td></tr>
                                <?php endif; ?>
                                <?php if($miniListing->bedrooms != '' && $miniListing->bedrooms != '0'): ?>
                                    <tr><td>Bedrooms</td><td><?php echo e(number_format($miniListing->bedrooms)); ?></td></tr>
                                <?php endif; ?>
                                <?php if($miniListing->full_baths != '' && $miniListing->full_baths != '0'): ?>    
                                    <tr><td>Full Bathrooms</td><td><?php echo e(number_format($miniListing->full_baths)); ?></td></tr>
                                <?php endif; ?>
                                <?php if($miniListing->half_baths != '' && $miniListing->half_baths != '0'): ?>    
                                    <tr><td>Half Bathrooms</td><td><?php echo e(number_format($miniListing->half_baths)); ?></td></tr>
                                <?php endif; ?>
                                <?php if($miniListing->acreage != '' && $miniListing->acreage != '0'): ?>
                                    <tr><td>Acreage</td><td><?php echo e($miniListing->acreage); ?> Acres</td></tr>
                                <?php endif; ?>
                                <?php if($miniListing->total_hc_sqft != '' && $miniListing->total_hc_sqft != '0'): ?>
                                    <tr><td>H/C SqFt</td><td><?php echo e(number_format($miniListing->total_hc_sqft)); ?> SqFt</td></tr>
                                <?php endif; ?>
                                <?php if($miniListing->sqft != '' && $miniListing->sqft != '0'): ?>
                                    <tr><td>Total SqFt</td><td><?php echo e(number_format($miniListing->sqft)); ?> SqFt</td></tr>
                                <?php endif; ?>
                                <?php if($miniListing->lot_dimensions != '' && ($miniListing->lot_dimensions != '0' || $miniListing->lot_dimensions != '')): ?>
                                    <tr><td>Lot Size</td><td><?php echo e($miniListing->lot_dimensions); ?></td></tr>
                                <?php endif; ?>
                                <?php if($miniListing->list_date != ''): ?>
                                    <tr><td>List Date</td><td><?php echo e(date('M d, Y', strtotime($miniListing->list_date))); ?></td></tr>
                                <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php echo $__env->make('partials.disclaimer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php endif; ?>

            </div>

        </main>
    <?php endwhile; ?>
<?php else: ?>
    <?php echo $__env->make('pages.404', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
    <?php $__currentLoopData = $listings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $miniListing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <portal-target name="modal-<?php echo e($miniListing->mls_account); ?>"></portal-target>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/pages/list.blade.php ENDPATH**/ ?>