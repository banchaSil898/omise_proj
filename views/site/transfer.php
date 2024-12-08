<?php

use app\widgets\Page;
?>
<?php $this->beginContent('@app/views/site/layouts/content.php'); ?>
<?php

Page::begin([
    'title' => 'แจ้งการโอนเงิน',
]);
?>

<?php Page::end(); ?>
<?php $this->endContent(); ?>