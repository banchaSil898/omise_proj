<?php

use app\widgets\Page;
?>
<?php $this->beginContent('@app/views/site/layouts/content.php'); ?>
<?php

Page::begin([
    'title' => 'ตรวจสอบสถานะ',
]);
?>

<?php Page::end(); ?>
<?php $this->endContent(); ?>