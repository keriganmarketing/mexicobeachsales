<div class="card listing-detail">
    <div class="card-header">Property Details</div>
    <table class="table">
    <tr><td>MLS Number</td><td><?php echo e($listing->mls_account); ?></td></tr>
    <tr><td>Status</td><td><?php echo e($listing->status); ?></td></tr>
    <?php if($listing->list_date != ''): ?>
        <tr><td>List Date</td><td><?php echo e(date('M d, Y', strtotime($listing->list_date))); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->date_modified != '' && date('Ymd', strtotime($listing->date_modified)) != date('Ymd', strtotime($listing->list_date))): ?>
        <tr><td>Listing Updated</td><td><?php echo e(date('M d, Y', strtotime($listing->date_modified))); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->bedrooms != '' && $listing->bedrooms != '0'): ?>
        <tr><td>Bedrooms</td><td><?php echo e(number_format($listing->bedrooms)); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->full_baths != '' && $listing->full_baths != '0'): ?>    
        <tr><td>Full Bathrooms</td><td><?php echo e(number_format($listing->full_baths)); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->half_baths != '' && $listing->half_baths != '0'): ?>    
        <tr><td>Half Bathrooms</td><td><?php echo e(number_format($listing->half_baths)); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->stories != '' && $listing->stories != '0'): ?>
        <tr><td>Stories</td><td><?php echo e($listing->stories); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->acreage != '' && $listing->acreage != '0'): ?>
        <tr><td>Acreage</td><td><?php echo e($listing->acreage); ?> Acres</td></tr>
    <?php endif; ?>
    <?php if($listing->total_hc_sqft != '' && $listing->total_hc_sqft != '0'): ?>
        <tr><td>H/C SqFt</td><td><?php echo e(number_format($listing->total_hc_sqft)); ?> SqFt</td></tr>
    <?php endif; ?>
    <?php if($listing->sqft != '' && $listing->sqft != '0'): ?>
        <tr><td>Total SqFt</td><td><?php echo e(number_format($listing->sqft)); ?> SqFt</td></tr>
    <?php endif; ?>
    <?php if($listing->lot_dimensions != '' && ($listing->lot_dimensions != '0' || $listing->lot_dimensions != '')): ?>
        <tr><td>Lot Size</td><td><?php echo e($listing->lot_dimensions); ?></td></tr>
    <?php endif; ?>
    </table>
</div><?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/listing/details.blade.php ENDPATH**/ ?>