<div 
    class="main-header-image"
    >
    <kma-slider class="slider-container"></kma-slider>
    <div class="slider-content">
        <div class="container">
            <div 
                class="overlay-content"
            >
            <?php echo apply_filters('the_content', (get_post(get_theme_mod('overlay_content')))->post_content); ?>

            </div>
        </div>
    </div>
    <?php if(get_theme_mod('use_overlay_text')): ?>
        <div 
            class="overlay"
            style="
                background-color: <?php echo e(get_theme_mod('overlay_color')); ?>;
                opacity: <?php echo e(get_theme_mod('overlay_opacity')); ?>;
            "
        ></div>
    <?php endif; ?>
</div><?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/partials/slider.blade.php ENDPATH**/ ?>