<div class="mic-background">
    <table border="0" width="660" style="border-collapse: collapse;margin:5px auto;table-layout: fixed;background:#ffffff;">
        <tr>
            <td style="padding:10px 30px;border:1px solid #dcdcdc;">
                <?= $content ?>
            </td>
        </tr>
    </table>
</div>
<?php
$this->registerCss(<<<CSS
    .mic-background {
        padding:10px;
        background-color:#eeeeee;
    }
CSS
);
?>