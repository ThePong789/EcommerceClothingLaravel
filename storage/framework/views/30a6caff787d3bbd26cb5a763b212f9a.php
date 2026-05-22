<?php if($paginator->hasPages()): ?>
<nav style="display:flex;justify-content:center;margin-top:1.5rem;">
    <ul style="display:flex;gap:.3rem;list-style:none;padding:0;margin:0;flex-wrap:wrap;">
        
        <?php if($paginator->onFirstPage()): ?>
            <li><span style="padding:.45rem .8rem;border-radius:6px;font-size:.85rem;color:#aaa;border:1px solid #e5e7eb;cursor:default;">&laquo;</span></li>
        <?php else: ?>
            <li><a href="<?php echo e($paginator->previousPageUrl()); ?>" style="padding:.45rem .8rem;border-radius:6px;font-size:.85rem;text-decoration:none;color:#2d2d2d;border:1px solid #e5e7eb;">&laquo;</a></li>
        <?php endif; ?>

        
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(is_string($element)): ?>
                <li><span style="padding:.45rem .8rem;border-radius:6px;font-size:.85rem;border:1px solid #e5e7eb;"><?php echo e($element); ?></span></li>
            <?php endif; ?>
            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        <li><span style="padding:.45rem .8rem;border-radius:6px;font-size:.85rem;background:#0a0a0a;color:#fff;border:1px solid #0a0a0a;"><?php echo e($page); ?></span></li>
                    <?php else: ?>
                        <li><a href="<?php echo e($url); ?>" style="padding:.45rem .8rem;border-radius:6px;font-size:.85rem;text-decoration:none;color:#2d2d2d;border:1px solid #e5e7eb;"><?php echo e($page); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <li><a href="<?php echo e($paginator->nextPageUrl()); ?>" style="padding:.45rem .8rem;border-radius:6px;font-size:.85rem;text-decoration:none;color:#2d2d2d;border:1px solid #e5e7eb;">&raquo;</a></li>
        <?php else: ?>
            <li><span style="padding:.45rem .8rem;border-radius:6px;font-size:.85rem;color:#aaa;border:1px solid #e5e7eb;cursor:default;">&raquo;</span></li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>
<?php /**PATH C:\Users\vesot\Downloads\Telegram Desktop\LRV\Laravel\resources\views/vendor/pagination/bootstrap-5.blade.php ENDPATH**/ ?>