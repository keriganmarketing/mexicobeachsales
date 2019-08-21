<div class="card listing-detail">
    <div class="card-header">Construction Details</div>
    <table class="table">
    <?php if($listing->waterfront_feet != '' && $listing->waterfront_feet != '0'): ?>
        <tr><td>WF Feet</td><td><?php echo e($listing->waterfront_feet); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->year_built != ''): ?>
        <tr><td>Year Built</td><td><?php echo e($listing->year_built); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->construction != ''): ?>
        <tr><td>Construction Material</td><td><?php echo e($listing->construction); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->energy != ''): ?>
        <tr><td>Energy, Heat/Cool</td><td><?php echo e($listing->energy); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->exterior != ''): ?>
        <tr><td>Exterior Features</td><td><?php echo e($listing->exterior); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->interior != ''): ?>
        <tr><td>Interior Features</td><td><?php echo e($listing->interior); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->utilities != ''): ?>
        <tr><td>Utilities</td><td><?php echo e($listing->utilities); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->parking != ''): ?>
        <tr><td>Parking</td><td><?php echo e($listing->parking); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->parking_type != ''): ?>
        <tr><td>Parking Type</td><td><?php echo e($listing->parking_type); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->ownership != ''): ?>
        <tr><td>Availability</td><td><?php echo e($listing->ownership); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->parking_spaces != ''): ?>
        <tr><td>Parking Spaces</td><td><?php echo e($listing->parking_spaces); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->ceiling_height != ''): ?>
        <tr><td>Ceiling Height</td><td><?php echo e($listing->ceiling_height); ?></td></tr>
    <?php endif; ?>
    <?php if($listing->front_footage != ''): ?>
        <tr><td>Front Footage</td><td><?php echo e($listing->front_footage); ?></td></tr>
    <?php endif; ?>
    </table>
</div><?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/listing/construction.blade.php ENDPATH**/ ?>