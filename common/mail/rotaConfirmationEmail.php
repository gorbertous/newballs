<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<h2>Your Game Details</h2>
<?php if(isset($model->updatedByname)) : ?>
    <h4 style="color: #ffc107!important;">(updated by <?= $model->updatedByname ?> ) </h4>
<?php endif; ?>
<h4>Player : <?= $model->member->name ?> </h4>
<h4>Date : <?= $model->termin->termin_date ?> </h4>
<h4>Location : <?= $model->termin->location->shortAddress ?> </h4>
<h4>Court : <?= $model->court_id ?> Slot : <?= $model->slot_id ?></h4>
<?php if($model->tokens) : ?>
    <h4 style="color: #ffc107!important;">Tokens : You are responsible for supply of tokens and a set of newish tennis balls! </h4>
<?php endif; ?>
    <p>For further details please check the  <?= Html::a(' website', Url::home('http')) ?></p>
