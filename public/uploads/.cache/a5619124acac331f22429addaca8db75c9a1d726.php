<p class="pt-lg-4">
    <?php if($listing->virtual_tour != ''): ?>
    <a class="btn btn-secondary mb-1" target="_blank" href="//<?php echo e($listing->virtual_tour); ?>" >Virtual Tour</a> 
    <?php endif; ?>
    <?php if(count($listing->media_objects->data ) > 1): ?>
    <a @click="openGallery([<?php echo e($listing->mls_account); ?>])" class="btn btn-secondary mb-1 pointer text-white" >Photos (<?php echo e(count($listing->media_objects->data )); ?>)</a>
    <?php endif; ?>
</p><?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/listing/actionbuttons.blade.php ENDPATH**/ ?>