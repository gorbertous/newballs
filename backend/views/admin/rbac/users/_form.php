<?php

use common\rbac\models\Authitem;
use kartik\widgets\Select2;
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use kartik\password\PasswordInput;
use common\helpers\Helpers;

/* @var $this yii\web\View */
/* @var $user \common\models\User */
/* @var $form yii\widgets\ActiveForm */

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
                        ])->passwordInput(['placeholder' => Yii::t('app', 'New pwd ( if you want to change it )')]);
                    ?>
                <?php endif ?>
            </div>
        </div>

        <?php if (Yii::$app->user->can('team_member')) : ?>
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

    <?php
        echo Helpers::getModalFooter($user, null, null, [
            'buttons' => ['create_update', 'cancel']
        ]);
    ?>

    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>
</div>
