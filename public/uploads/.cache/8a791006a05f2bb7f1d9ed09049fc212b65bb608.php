<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <article class="support">
                <header>
                    <h1><?php echo e($headline != '' ? $headline : the_title()); ?></h1>
                </header>

                

                <?php echo e(the_content()); ?>

            </article>
        </div>
    </div>
</div>
<?php /**PATH /home/forge/mexicobeachsales.com/public/themes/wordplate/views/formats/standard.blade.php ENDPATH**/ ?>