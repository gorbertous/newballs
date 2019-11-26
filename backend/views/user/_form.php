<?php

use common\rbac\models\Authitem;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\password\PasswordInput;

/* @var $this yii\web\View */
/* @var $user \common\models\User */
/* @var $form yii\widgets\ActiveForm */

if (Yii::$app->user->can('writer')) {
    $readonly = false;
} else {
    $readonly = true;
}
?>

<div class="user-form">

    <?php
    $form = ActiveForm::begin([
            'id' => 'form-user'
    ]);
    ?>

    <div class="well">

        <div class="row">
            <div class="col-md-6">
                
                <?= $form->field($user, 'email') ?>

            </div>

            <div class="col-md-6">
                <?= $form->field($user, 'username') ?>

                <?php if ($user->scenario === 'create'): ?>
                    <?= $form->field($user, 'password')->widget(PasswordInput::class, [
                        'pluginOptions' => [
                            'showMeter' => true,
                            'toggleMask' => false
                            ]
                        ]);
                    ?>
                <?php else: ?>
                    <?= $form->field($user, 'password')->widget(PasswordInput::class, [
                        'pluginOptions' => [
                            'showMeter' => true,
                            'toggleMask' => false
                            ]
                        ])->passwordInput(['placeholder' => Yii::t('app', 'New pwd ( if you want to change it ....)')]);
                    ?>
                <?php endif ?>
            </div>
        </div>

        <?php if (Yii::$app->user->can('writer')) : ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($user, 'status')->widget(Select2::class, [
                        'data' => $user->statusList,
                        'pluginOptions' => ['allowClear' => false]]); ?>

                    <?php foreach (Authitem::getChildroles() as $item_name): ?>
                        <?php $roles[$item_name->name] = $item_name->name ?>
                    <?php endforeach ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($role, 'item_name')->widget(Select2::class, [
                        'data' => $roles,
                        'pluginOptions' => ['allowClear' => false]]); ?>
                </div>
            </div>
        <?php endif ?>

        <div class="clearfix"></div>
    </div>

    <div class="form-group pull-right">
        <?=
        Html::submitButton('<span class="fa fa-check"></span>&nbsp;' .
            ($user->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update')), ['class' => $user->isNewRecord ? 'btn btn-success' : 'btn btn-success'])
        ?>

        <?=
        Html::Button('<span class="fa fa-times"></span>&nbsp;' .
            Yii::t('app', 'Cancel'), ['class' => 'btn btn-danger', 'data-dismiss' => 'modal'])
        ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>
</div>
