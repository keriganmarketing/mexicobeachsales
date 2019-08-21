<?php echo e(the_post()); ?>


<?php if(get_post_format() == '' || get_post_format() == 'standard'): ?>
<div class="card shadow">
    <?php if(has_post_thumbnail()): ?>
        <a href="<?php echo e(the_permalink()); ?>" title="<?php echo e(the_title_attribute()); ?>">
        <?php echo e(the_post_thumbnail('post-thumbnail', ['class' => 'card-img-top'])); ?>

        </a>
    <?php endif; ?>
    <div class="card-body">
        <h2 class="card-title"><?php echo e(the_title()); ?></h2>
            <small class="text-muted"><?php echo e(get_the_date()); ?></small>

            <?php echo e(the_excerpt()); ?>


            <a href="<?php echo e(the_permalink()); ?>" >Read more</a>
    </div>
</div>
<?php endif; ?>

<?php if(get_post_format() == 'status'): ?>
<div class="card status-update shadow">
    <div class="card-body text-dark">
        <small class="text-muted"><?php echo e(get_the_date()); ?></small>
        <p class="h3"><?php echo e(the_title()); ?></p>
        <?php echo e(the_content()); ?>

    </div>
</div>
<?php endif; ?>

<?php if(get_post_format() == 'quote'): ?>
<div class="card bg-secondary shadow">
    <div class="card-body text-white">
        <?php echo e(the_excerpt()); ?>

        <small class="text-white">&mdash; <?php echo e(the_title() . ', ' . get_the_date()); ?></small>
    </div>
</div>
<?php endif; ?>

<?php if(get_post_format() == 'image'): ?>
<div class="card shadow">
    <div class="position-absolute p-3 px-4">
        <p class="text-white m-0"><?php echo e(the_title()); ?><br>
        <small class="text-white"><?php echo e(get_the_date()); ?></small></p>
    </div>
    <?php if(has_post_thumbnail()): ?>
        <a href="<?php echo e(the_permalink()); ?>" title="<?php echo e(the_title_attribute()); ?>">
        <?php echo e(the_post_thumbnail('post-thumbnail', ['class' => 'card-img-top'])); ?>

        </a>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if(get_post_format() == 'video'): ?>
<div class="card bg-dark video shadow">
    <?php echo the_content(); ?>


    <div class="pb-3 px-4" >
        <p class="text-white m-0"><?php echo e(the_title()); ?><br>
        <small class="text-white"><?php echo e(get_the_date()); ?></small></p>
    </div>
</div>
<?php endif; ?><?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/partials/article.blade.php ENDPATH**/ ?>