<?php
/**
 * Render the pdf card with the QR Code
 * @var $hash string
 */
?>
<div id="card-auth-print-view" class="card-front">
</div>
<pagebreak />
<div id="card-auth-print-view" class="card-rear">
    <barcode code="<?= $hash; ?>" type="QR" class="barcode" size="0.66" error="M" disableborder="1">
</div>
&nbsp;