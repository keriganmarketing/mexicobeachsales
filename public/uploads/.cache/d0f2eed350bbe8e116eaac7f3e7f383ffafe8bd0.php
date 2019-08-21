<div class="card listing bg-white" >
    <?php if(date('Ymd', strtotime($miniListing->list_date)) >= date('Ymd', strtotime('-10 days'))): ?>
        <span class="status-flag just-listed">Just Listed</span>
    <?php endif; ?>
    <?php if($miniListing->status == 'Sold/Closed'): ?>
        <span class="status-flag sold">Sold on <?php echo date( 'M j, Y', strtotime( $miniListing->sold_on ) ); ?><br>
                    for $<?php echo number_format( $miniListing->sold_for ); ?></span>
    <?php endif; ?>
    <?php if($miniListing->status == 'Contingent'): ?>
        <span class="status-flag contingent">SALE CONTINGENT</span>
    <?php endif; ?>
    <?php if($miniListing->original_list_price > $miniListing->price && $miniListing->status == 'Active' && $miniListing->original_list_price != 0): ?>
        <span class="status-flag reduced bg-danger">REDUCED <span style="text-decoration:line-through">$<?php echo number_format( $miniListing->original_list_price ); ?></span> <strong>$<?php echo number_format( $miniListing->price); ?></strong></span>
    <?php endif; ?>
    
    <listing-photo 
        src="<?php echo e($miniListing->media_objects->data[0]->url); ?>" 
        alt="Photo of MLS# <?php echo e($miniListing->mls_account); ?>"
    ></listing-photo>

    <div class="p-4 text-center text-dark flex-grow-1">
        <p><?php echo e($miniListing->full_address); ?><br>
           <?php echo e($miniListing->city . ', ' . $miniListing->state); ?></p>
        <p class="property-type text-muted"><?php echo e($miniListing->prop_type); ?></p>

        <?php if($miniListing->price !== null): ?>
        <p class="display-4 text-primary font-weight-bold">$<?php echo e(number_format($miniListing->price)); ?></p>
        <?php elseif(isset($miniListing->monthly_rent)): ?>
        <p class="display-4 text-primary font-weight-bold">$<?php echo e(number_format($miniListing->monthly_rent)); ?> <small>/ mo.</small></p>
        <?php endif; ?>
        <div class="row justify-content-center">
        <?php if($miniListing->bedrooms > 0): ?>
            <div class="col">
                <span class="display-4 text-secondary"><?php echo e(number_format($miniListing->bedrooms)); ?></span><br>
                <small class="text-muted">BEDS</small>
            </div>
        <?php endif; ?>
        <?php if($miniListing->total_bathrooms > 0): ?>
            <div class="col">
                <span class="display-4 text-secondary"><?php echo e(number_format($miniListing->total_bathrooms)); ?></span><br>
                <small class="text-muted">BATHS</small>
            </div>
        <?php endif; ?>
        <?php if($miniListing->sqft > 0): ?>
            <div class="col">
                <span class="display-4 text-secondary"><?php echo e(number_format($miniListing->sqft)); ?></span><br>
                <small class="text-muted">SQFT</small>
            </div>
        <?php endif; ?>
        
        <?php if($miniListing->acreage > 0 && $miniListing->bedrooms == 0): ?>
            <div class="col">
                <span class="display-4 text-secondary"><?php echo e($miniListing->acreage); ?></span><br>
                <small class="text-muted">ACRES</small>
            </div>
        <?php endif; ?>
        </div>
    </div>
    <div class="p-2 text-center"><span class="mls-number">MLS# <?php echo e($miniListing->mls_account); ?></span></div>
    <a class="position-absolute listing-link" href="/listing/<?php echo e($miniListing->mls_account); ?>/" >
        <span class="sr-only">View Listing <?php echo e($miniListing->mls_account); ?></a>
    </a>
</div>
<?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/partials/minilisting.blade.php ENDPATH**/ ?>