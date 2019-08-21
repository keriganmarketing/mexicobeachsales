<footer class="sticky-footer bg-dark pb-4">
    <div class="container">
        <h2 class="text-info">Contact Me</h2>
        <div class="row">
            <div class="col-lg-4">
                <p><?php echo e(get_field('email', 'agent_name')); ?></p>
                <p class="m-0"><a class="text-underline" href="tel:<?php echo e(get_field('phone', 'option')); ?>"><i class="fa fa-phone d-inline-block mx-2 text-info" aria-hidden="true"></i> <?php echo e(get_field('phone', 'option')); ?></a></p>
                <p><a class="text-underline" href="mailto:<?php echo e(get_field('email', 'option')); ?>"><i class="fa fa-envelope d-inline-block mx-2 text-info" aria-hidden="true"></i> <?php echo e(get_field('email', 'option')); ?></a></p>
                <p class="d-flex"><i class="fa fa-map-marker d-inline-block mx-2 px-1 text-info" aria-hidden="true"></i><?php echo e(get_field('broker_name', 'option')); ?>, 
                <?php echo nl2br(get_field('address', 'option')); ?></p>
                <social-icons :size="37" :margin=".25" class="d-flex social-icons justify-content-start mb-4 mx-2" ></social-icons>
            </div>
            <div class="col-lg-8">
                <contact-form class="contact-form" :listing='<?php echo e(json_encode((isset($listing) && $listing != '' ? $listing : ''))); ?>' ></contact-form>
            </div>
            <?php if(get_field('broker_logo', 'option')): ?>
            <div class="col-12 text-center">
                <div class="broker-logo" >
                    <?php echo (get_field('broker_link', 'option') ? '<a href="'.get_field('broker_link', 'option').'" target="_blank" rel="noopener" >' : null); ?>

                    <img src="<?php echo e(wp_get_attachment_url(get_field('broker_logo', 'option'),'medium')); ?>" alt="<?php echo e(get_field('broker_name', 'option')); ?>" >
                    <?php echo (get_field('broker_link', 'option') ? '</a>' : null); ?>

                </div>
            </div>
            <?php endif; ?>    
        </div>

    </div>
    <hr>
    <p class="copyright text-center">&copy;<?php echo e(date('Y')); ?> <?php echo e(get_bloginfo()); ?>. All Rights&nbsp;Reserved. 
        <a style="text-decoration:underline;" href="/privacy-policy/" >Privacy&nbsp;Policy</a> 
        <span class="siteby">
            <svg version="1.1" id="kma" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="14" width="20"
                viewBox="0 0 12.5 8.7" style="enable-background:new 0 0 12.5 8.7;"
                xml:space="preserve">
                    <path fill="#b4be35"
                d="M6.4,0.1c0,0,0.1,0.3,0.2,0.9c1,3,3,5.6,5.7,7.2l-0.1,0.5c0,0-0.4-0.2-1-0.4C7.7,7,3.7,7,0.2,8.5L0.1,8.1 c2.8-1.5,4.8-4.2,5.7-7.2C6,0.4,6.1,0.1,6.1,0.1H6.4L6.4,0.1z"></path>
            </svg> &nbsp;<a href="https://keriganmarketing.com">Site&nbsp;by&nbsp;KMA</a>.
        </span></p>
</footer><?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/partials/footer.blade.php ENDPATH**/ ?>