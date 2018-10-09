<?php
/* @var $model backend\models\Clubs */
//
//
//
//$this->title = '<h3 class="panel-title"><span class="fa fa-desktop"></span> Club&nbsp;<span class="fa fa-balance-scale"></span> Rules </h3>';
//echo $this->title;
//echo $model->rules_page;

?>

<div class="panel panel-default">
    <div class="panel-heading">    
        
        <h3 class="panel-title"><span class="fa fa-desktop"></span> Club&nbsp;<span class="fa fa-balance-scale"></span> Rules </h3>
        <div class="clearfix"></div>
    </div>
    <?= $model->rules_page;?>
</div>