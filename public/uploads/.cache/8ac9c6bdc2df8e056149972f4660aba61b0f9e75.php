<div class="card listing-detail">
    <div class="card-header">Area Information</div>
    <table class="table">
    <?php if($listing->area != ''): ?>
        <tr><td>Area</td><td><?php echo e($listing->area); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->sub_area != ''): ?>
        <tr><td>Sub Area</td><td><?php echo e($listing->sub_area); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->subdivision != ''): ?>
        <tr><td>Subdivision</td><td><?php echo e($listing->subdivision); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->hoa_included != ''): ?>
        <tr><td>HOA Includes</td><td><?php echo e($listing->hoa_included); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->hoa_fee != '' && $listing->hoa_fee != '0'): ?>
        <tr><td>HOA Fee</td><td>$<?php echo e(number_format($listing->hoa_fee)); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->hoa_terms != '' && $listing->hoa_terms != '0'): ?>
        <tr><td>HOA Term</td><td><?php echo e($listing->hoa_terms); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->proj_name != ''): ?>
        <tr><td>Community</td><td><?php echo e($listing->proj_name); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->projfacilities != ''): ?>
        <tr><td>Community Facilities</td><td><?php echo e($listing->projfacilities); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->num_units != ''): ?>
        <tr><td>Number of Units</td><td><?php echo e($listing->num_units); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->zoning != ''): ?>
        <tr><td>Zoning</td><td><?php echo e($listing->zoning); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->lot_access != ''): ?>
        <tr><td>Lot Access</td><td><?php echo e($listing->lot_access); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->lot_descriptions != ''): ?>
        <tr><td>Lot Description</td><td><?php echo e($listing->lot_descriptions); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->legals != ''): ?>
        <tr><td>Legal Info</td><td><?php echo e($listing->legals); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->site_description != ''): ?>
        <tr><td>Site Description</td><td><?php echo e($listing->site_description); ?></td></tr>
    <?php endif; ?>
    </table>
</div><?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/listing/location.blade.php ENDPATH**/ ?>