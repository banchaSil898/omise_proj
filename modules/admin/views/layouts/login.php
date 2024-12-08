<?php

$this->beginContent('@module/views/layouts/html.php', [
    'bodyClass' => 'login',
]);
?>
<?= $content; ?>
<?php $this->endContent(); ?>