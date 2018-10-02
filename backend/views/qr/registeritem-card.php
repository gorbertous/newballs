<?php
/**
 * Render the pdf card with the QR Code
 *
 * @var $hash string
 */
?>

<div id="card-print-view">
    <barcode code="<?= $hash; ?>" type="QR" class="barcode" size="0.7" error="M" disableborder="1"> <br />
    <?= $model->registerplus->titleSuffix ?> <br />
    <?= 'SN ' . $model->Serial_number ?>
</div>