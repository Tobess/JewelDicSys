<?php if ($paginator->total() > 1): ?>
    <ul class="pagination pagination-sm m-t-none m-b-none">
        <?php echo str_replace('<ul class="pagination">', '', str_replace('</ul>', '', $paginator->render())); ?>
    </ul>
<?php endif; ?>
