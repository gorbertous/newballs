<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Typeahead;
use dosamigos\tinymce\TinyMce;
use common\helpers\Helpers;
use common\helpers\Language as Lx;

$htmlmode = $source->translationEN != strip_tags($source->translationEN) ||
        $source->translationFR != strip_tags($source->translationFR) ||
        $source->translationDE != strip_tags($source->translationDE) ||
        $source->message != strip_tags($source->message);

?>

<div class="message-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-message',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

        <?= $form->field($source, 'id')->hiddenInput(['value'=> $source->id])->label(false) ?>

        <ul class="nav nav-pills" id="tabContent">
            <li <?= ($language == 'en' ? 'class="active"':'') ?>><a href="#en" data-toggle="tab"><?= Yii::t('app', 'English') ?></a></li>
            <li <?= ($language == 'fr' ? 'class="active"':'') ?>><a href="#fr" data-toggle="tab"><?= Yii::t('app', 'French') ?></a></li>
            <li <?= ($language == 'de' ? 'class="active"':'') ?>><a href="#de" data-toggle="tab"><?= Yii::t('app', 'German') ?></a></li>
        </ul>

        <?php if($htmlmode) { ?>

            <div class="tab-content well">

                <div class="tab-pane <?= ($language == 'en' ? 'active':'') ?>" id="en">
                    <?= $form->field($source, 'translationEN')->widget(TinyMce::class, [
                        'language' => Helpers::getTinyMceLanguage(),
                        'clientOptions' => Helpers::getTinyMceClientOptionsHTML()
                    ])->label(false); ?>
                </div>

                <div class="tab-pane <?= ($language == 'fr' ? 'active':'') ?>" id="fr">
                    <?= $form->field($source, 'translationFR')->widget(TinyMce::class, [
                        'language' => Helpers::getTinyMceLanguage(),
                        'clientOptions' => Helpers::getTinyMceClientOptionsHTML()
                    ])->label(false) ?>
                </div>

                <div class="tab-pane <?= ($language == 'de' ? 'active':'') ?>" id="de">
                    <?= $form->field($source, 'translationDE')->widget(TinyMce::class, [
                        'language' => Helpers::getTinyMceLanguage(),
                        'clientOptions' => Helpers::getTinyMceClientOptionsHTML()
                    ])->label(false) ?>
                </div>

                <div class="clearfix"></div>
            </div>

        <?php } else { ?>

            <div class="tab-content well">

                <div class="tab-pane <?= ($language == 'en' ? 'active':'') ?>" id="en">
                    <?= $form->field($source, 'translationEN')->textarea(['rows' => 3])
                            ->label(false) ?>
                </div>

                <div class="tab-pane <?= ($language == 'fr' ? 'active':'') ?>" id="fr">
                    <?= $form->field($source, 'translationFR')->textarea(['rows' => 3])
                            ->label(false) ?>
                </div>

                <div class="tab-pane <?= ($language == 'de' ? 'active':'') ?>" id="de">
                    <?= $form->field($source, 'translationDE')->textarea(['rows' => 3])->label(false) ?>
                </div>

                <div class="clearfix"></div>
            </div>

        <?php } ?>

        <div class="well">
            <div class="row">
                <div class="col-md-12">

                    <?= $form->field($source, 'category')->widget(Typeahead::class, [
                        'name' => 'risk',
                        'options' => ['placeholder' => ''],
                        'scrollable' => true,
                        'pluginOptions' => ['highlight'=>true],
                        'dataset' => [
                            ['local' => $categories,
                             'limit' => 10]
                        ]]) ?>

                    <?php if($htmlmode) { ?>
                        <?= $form->field($source, 'message')->widget(TinyMce::class, [
                            'language' => Helpers::getTinyMceLanguage(),
                            'clientOptions' => Helpers::getTinyMceClientOptionsHTML()
                        ]) ?>
                    <?php } else { ?>
                        <?= $form->field($source, 'message')->textarea(['rows' => 3])  ?>
                    <?php } ?>

                    <p style="text-align:center;">
                        <?php if (Lx::IsMaster()) { ?>
                            <?= '<strong>Master:</strong> ' . Lx::MasterName() ?>
                        <?php } else { ?>
                            <?= '<strong>Local:</strong> ' . Lx::LocalName() ?><br>
                            <?= '<strong>Master:</strong> ' . Lx::MasterName() ?>
                        <?php } ?>
                    </p>

                </div>
            </div>
        </div>

        <div class="form-group pull-right">
            <?= Html::Button('<span class="fa fa-refresh"></span>&nbsp;' .
                '<span id="syncmessagespan">'.($pendinguploads == 0 ? '' : $pendinguploads . ' Up') .'</span>',
                ['class' => 'btn btn-info', 'id' => 'syncmessage']); ?>

            <?= Html::submitButton('<span class="fa fa-check"></span>&nbsp;' .
                            ($source->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update')), ['class' => $source->isNewRecord ?
                                'btn btn-success' : 'btn btn-success']); ?>

            <?= Html::Button('<span class="fa fa-times"></span>&nbsp;' .
                    Yii::t('app', 'Cancel'), ['class' => 'btn btn-danger', 'data-dismiss' => 'modal']); ?>
        </div>

    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>
</div>