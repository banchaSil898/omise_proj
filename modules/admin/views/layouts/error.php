<?php
$this->beginContent('@module/views/layouts/html.php', [
    'bodyClass' => 'error',
]);
?>
<div class="container">
    <?= $content; ?>    
</div>
<?php $this->endContent(); ?>