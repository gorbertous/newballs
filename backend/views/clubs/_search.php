<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\models\ClubsSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="clubs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'c_id') ?>

    <?= $form->field($model, 'season_id') ?>

    <?= $form->field($model, 'session_id') ?>

    <?= $form->field($model, 'css_id') ?>

    <?= $form->field($model, 'type_id') ?>

    <?php // echo $form->field($model, 'lang') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'logo_orig') ?>

    <?php // echo $form->field($model, 'home_page') ?>

    <?php // echo $form->field($model, 'rules_page') ?>

    <?php // echo $form->field($model, 'members_page') ?>

    <?php // echo $form->field($model, 'rota_page') ?>

    <?php // echo $form->field($model, 'tournament_page') ?>

    <?php // echo $form->field($model, 'subscription_page') ?>

    <?php // echo $form->field($model, 'summary_page') ?>

    <?php // echo $form->field($model, 'email_header') ?>

    <?php // echo $form->field($model, 'site_url') ?>

    <?php // echo $form->field($model, 'site_currency') ?>

    <?php // echo $form->field($model, 'coach_stats') ?>

    <?php // echo $form->field($model, 'token_stats') ?>

    <?php // echo $form->field($model, 'play_stats') ?>

    <?php // echo $form->field($model, 'scores') ?>

    <?php // echo $form->field($model, 'match_instigation') ?>

    <?php // echo $form->field($model, 'court_booking') ?>

    <?php // echo $form->field($model, 'money_stats') ?>

    <?php // echo $form->field($model, 'activation_date') ?>

    <?php // echo $form->field($model, 'admin_id') ?>

    <?php // echo $form->field($model, 'chair_id') ?>

    <?php // echo $form->field($model, 'sport_id') ?>

    <?php // echo $form->field($model, 'location_id') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <?php // echo $form->field($model, 'subscription_id') ?>

    <?php // echo $form->field($model, 'payment') ?>

    <?php // echo $form->field($model, 'with_customheader') ?>

    <?php // echo $form->field($model, 'rota_removal') ?>

    <?php // echo $form->field($model, 'rota_block') ?>

    <?php // echo $form->field($model, 'photo_one') ?>

    <?php // echo $form->field($model, 'photo_two') ?>

    <?php // echo $form->field($model, 'photo_three') ?>

    <?php // echo $form->field($model, 'photo_four') ?>

    <?php // echo $form->field($model, 'custom_header') ?>

    <?php // echo $form->field($model, 'custom_footer') ?>

    <?php // echo $form->field($model, 'rota_style') ?>

    <?php // echo $form->field($model, 'client_url') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('modelattr', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('modelattr', 'Reset'), ['class' => 'btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
