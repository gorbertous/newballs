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
            <li class="nav-item"><a href="#en" <?= ($language == 'en' ? 'class="nav-link active"':'class="nav-link"') ?> data-toggle="tab"><?= Yii::t('app', 'English') ?></a></li>
            <li class="nav-item"><a href="#fr" <?= ($language == 'fr' ? 'class="nav-link active"':'class="nav-link"') ?> data-toggle="tab"><?= Yii::t('app', 'French') ?></a></li>
            <li class="nav-item"><a href="#de" <?= ($language == 'de' ? 'class="nav-link active"':'class="nav-link"') ?> data-toggle="tab"><?= Yii::t('app', 'German') ?></a></li>
        </ul>

        <?php if($htmlmode) { ?>

            <div class="tab-content card card-body bg-light">

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

            <div class="tab-content card card-body bg-light">

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

        <div class="card card-body bg-light">
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
                        <?php if (Lx::isMaster()) { ?>
                            <?= '<strong>Master:</strong> ' . Lx::getMaster() ?>
                        <?php } else { ?>
                            <?= '<strong>Local:</strong> ' . Lx::getLocal() ?><br>
                            <?= '<strong>Master:</strong> ' . Lx::getMaster() ?>
                        <?php } ?>
                    </p>

                </div>
            </div>
        </div>

        <div class="modal-footer">
            <?= Html::Button('<span class="fas fa-sync"></span>&nbsp;' .
                '<span id="syncmessagespan">'.($pendinguploads == 0 ? '' : $pendinguploads . ' Up') .'</span>',
                ['class' => 'btn btn-primary', 'id' => 'syncmessage']); ?>

            <?= Html::submitButton('<span class="fas fa-check"></span>&nbsp;' .
                            ($source->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update')), ['class' => $source->isNewRecord ?
                                'btn btn-success' : 'btn btn-success']); ?>

            <?= Html::Button('<span class="fas fa-times"></span>&nbsp;' .
                    Yii::t('app', 'Cancel'), ['class' => 'btn btn-danger', 'data-izimodal-close' => 'modal']); ?>
        </div>

    <?php ActiveForm::end(); ?>

    <div class="clearfix"></div>
</div>