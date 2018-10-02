<?php

use yii\ {
    helpers\Html, helpers\ArrayHelper, helpers\Url, widgets\Pjax
};
use kartik\ {
    widgets\ActiveForm, checkbox\CheckboxX, widgets\FileInput, widgets\Select2
};
use backend\models\Mandants;
use common\ {
    helpers\Language as Lx, dictionaries\MenuTypes as Menu
};

$this->title = Menu::adminText() . '-' . Yii::t('appMenu', 'Utilities');
$header = '<span class="fa fa-lock"></span> ' . Menu::adminText() . '&nbsp;';
$header .= '<span class="fa fa-pencil"></span> ' . Yii::t('appMenu', 'Utilities');

/* @var $pendinguploads string */
/* @var $sqldumpfiles array */
/* @var $migrationstatus array */
/* @var $modelsmissingaddlang array */
/* @var $lastConnectedUsers string */
/* @var $modelsmissingaddlang array */
/* @var $title string */
?>

<div class="import-form">

    <div class="panel-heading">
        <h3 class="panel-title"><?= $header ?></h3>
    </div>

    <?php if (!empty($ilog)) { ?>

        <div class="row">

            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <?= \yii\helpers\Html::a('<span class="fa fa-angle-left"></span> Back', ['import/index'], ['class' => 'btn btn-sm btn-default btn-flat']); ?>
                    </div>

                    <div class="box-body">
                        <h3 class="box-title"><?= $title; ?></h3>
                        <hr/>

                        <p><?= join('<br>', $ilog); ?></p>

                        <p>
                            <?php if (!empty($zipUrl)) {
                                echo '<br>' . Html::a('<i class="fa fa-cloud-download"></i> ' . basename($zipUrl), $zipUrl, ['class' => 'btn btn-default', 'download' => basename($zipUrl)]);
                            }
                            /*
                              if (trim($model->PDF_File)!==''){
                              header('Content-Description: File Transfer');
                              header('Content-Type: application/zip');
                              header('Content-Disposition: attachment; filename='.basename($zipPath));
                              header('Content-Transfer-Encoding: binary');
                              header('Expires: 0');
                              header('Cache-Control: must-revalidate');
                              header('Pragma: public');
                              header('Content-Length: ' . filesize($zipPath));
                              ob_clean();
                              flush();
                              readfile($zipname);
                              exit;
                              }
                            */
                            ?>
                        </p>
                    </div>

                    <div class="box-footer clearfix">
                        <?= \yii\helpers\Html::a('<span class="fa fa-angle-left"></span> Back', ['import/index'], ['class' => 'btn btn-sm btn-info btn-flat']); ?>
                    </div>
                </div>
            </div>

        </div> <!-- // end .row -->

    <?php } else { ?>

        <?php $form = ActiveForm::begin([
            'id'      => 'import-form',
            'options' => [
                'enctype' => 'multipart/form-data'
            ]
        ]); ?>

        

        <?php if ((!Lx::IsMaster() || Yii::$app->request->hostName == 'beta.esst.lu') && Yii::$app->user->can('admin')) { ?>
            <div class="row">

                <!-- Backup master data -->
                <div class="col-md-4">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span
                                        class="fa fa-database"></span> <?= Yii::t('appMenu', 'Backup master data') ?>
                            </h3>
                        </div>

                        <div class="box-body">
                            <p>Import the database from production and save it to your local as a <strong>.zip</strong>
                                file.</p>
                            <p><?= Html::submitButton('<span class="fa fa-cloud-download"></span>&nbsp; ' .
                                    (Yii::t('app', 'Backup Master')), ['name' => 'SubmitButton', 'value' => 'BackupMaster', 'class' => 'btn btn-default btn-block']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Import master data -->
                <div class="col-md-4">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span
                                        class="fa fa-database"></span> <?= Yii::t('appMenu', 'Import master data') ?>
                            </h3>
                        </div>

                        <div class="box-body">
                            <p><?= $form->field($model, 'sqldumpfile')->widget(Select2::class, [
                                    'data'          => array_combine($sqldumpfiles, $sqldumpfiles),
                                    'options'       => [],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ]
                                ])->label(false); ?></p>

                            <p><?= Html::submitButton('<span class="fa fa-cloud-upload"></span>&nbsp; ' .
                                    (Yii::t('app', 'Import database')), ['name' => 'SubmitButton', 'value' => 'ImportMaster', 'class' => 'btn btn-default btn-block']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Import files -->
                <div class="col-md-4">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span
                                        class="fa fa-file"></span> <?= Yii::t('appMenu', 'Download master files') ?>
                            </h3>
                        </div>

                        <div class="box-body">
                            <p>Download the master files (uploads folder) and import them to your local.</p>

                            <p><?= Html::submitButton('<span class="fa fa-cloud-download"></span>&nbsp; ' .
                                    (Yii::t('app', 'Download and Import')), ['name' => 'SubmitButton', 'value' => 'ImportMasterFiles', 'class' => 'btn btn-default btn-block']); ?>
                            </p>
                        </div>
                    </div>
                </div>

            </div> <!-- // end second .row -->
        <?php } ?>


        <?php if (false) { ?>
            <div class="row">

                <div class="col-md-6">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span class="fa fa-file"></span> <?= Yii::t('appMenu', 'Export DB') ?>
                            </h3>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <?=
                                Html::submitButton('<span class="fa fa-check"></span>&nbsp;' .
                                    (Yii::t('app', 'Export')), ['name' => 'SubmitButton', 'value' => 'ExportDB', 'class' => 'btn btn-success pull-right'])
                                ?>
                                <div class="clearfix"></div>
                                <?php
                                if (isset($zipUrl)) {
                                    echo '<br>' . Html::a('<i class="fa fa-cloud-download"></i> ' . basename($zipUrl), $zipUrl, ['class' => 'btn btn-default pull-right', 'download' => basename($zipUrl)]);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span class="fa fa-file"></span> <?= Yii::t('appMenu', 'Import DB') ?>
                            </h3>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?= FileInput::widget([
                                        'name'          => 'ImportDBFile',
                                        'pluginOptions' => [
                                            'previewFileType'       => 'any',
                                            'allowedFileExtensions' => ['zip'],
                                            'uploadUrl'             => Url::to(['upload'])]
                                    ]); ?>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <?=
                                Html::submitButton('<span class="fa fa-check"></span>&nbsp;' .
                                    (Yii::t('app', 'Import')), ['name' => 'SubmitButton', 'value' => 'ImportDB', 'class' => 'btn btn-success pull-right'])
                                ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="row">

            <div class="col-md-4">
                <!-- Migrate database box -->
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><span
                                    class="fa fa-database"></span> <?= Yii::t('appMenu', 'Migrate database'); ?></h3>
                    </div>

                    <div class="box-body">
                        <p><?= implode('<br>', $migrationstatus); ?></p>

                        <p><?= Html::submitButton('<span class="fa fa-cloud-upload"></span>&nbsp; ' .
                                (Yii::t('app', 'Migrate up')), ['name' => 'SubmitButton', 'value' => 'MigrateUp', 'class' => 'btn btn-warning btn-block']); ?></p>
                    </div>
                </div>
            </div>

            <!-- // start new column // -->

            <div class="col-md-4">
                <!-- Missing ContLang fields box -->
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><span
                                    class="fa fa-database"></span> <?= Yii::t('appMenu', 'Missing ContLang fields'); ?>
                        </h3>
                    </div>

                    <div class="box-body">
                        <p><?= implode('<br>', $modelsmissingaddlang); ?></p>

                        <p><?= Html::submitButton('<span class="fa fa-cloud-upload"></span>&nbsp; ' .
                                (Yii::t('app', 'Add ContLang fields')), ['name' => 'SubmitButton', 'value' => 'AddMissAddLang', 'class' => 'btn btn-default btn-block']); ?></p>
                    </div>
                </div>
            </div>

            <!-- // start new column // -->

            <div class="col-md-4">
                <!-- New migration box -->
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><span
                                    class="fa fa-database"></span> <?= Yii::t('appMenu', 'New migration'); ?></h3>
                    </div>

                    <div class="box-body">
                        <p><?= $form->field($model, 'NewMigrationLabel')->textinput() ?></p>

                        <p><?= Html::submitButton('<span class="fa fa-dot-circle-o"></span>&nbsp;' .
                                (Yii::t('app', 'Create new step')), ['name' => 'SubmitButton', 'value' => 'MigrateCreate', 'class' => 'btn btn-success btn-block']); ?></p>
                    </div>
                </div>
            </div>

        </div> <!-- // end .row -->



        <?php if (false && Yii::$app->user->can('team_member')) { ?>
            <div class="row">

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><span
                                        class="fa fa-upload"></span><?= Yii::t('appMenu', 'Export Mandant') ?></h3>
                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?=
                                    $form->field($model, 'export_mandant_id')->widget(Select2::class, [
                                        'data'          => ArrayHelper::map(Mandants::find()->orderBy('ID_Mandant')->asArray()->all(), 'ID_Mandant', 'Name'),
                                        'options'       => [],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ]
                                    ])->label(false)
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?=
                                Html::submitButton('<span class="fa fa-check"></span>&nbsp;' .
                                    (Yii::t('app', 'Export')), ['name' => 'SubmitButton', 'value' => 'ExportMandant', 'class' => 'btn btn-success pull-right'])
                                ?>
                                <div class="clearfix"></div>
                                <?php if (isset($zipUrl)) {
                                    echo '<br>' . Html::a('<i class="fa fa-cloud-download"></i> ' . basename($zipUrl), $zipUrl, ['class' => 'btn btn-default pull-right', 'download' => basename($zipUrl)]);
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><span
                                        class="fa fa-download"></span><?= Yii::t('appMenu', 'Import Mandant') ?></h3>
                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?=
                                    FileInput::widget([
                                        'name'          => 'ImportMandantFile',
                                        'pluginOptions' => [
                                            'previewFileType'       => 'any',
                                            'allowedFileExtensions' => ['zip'],
                                            'uploadUrl'             => Url::to(['upload'])
                                        ]
                                    ])
                                    ?>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <?=
                                    $form->field($model, 'import_mandant_id')->widget(Select2::class, [
                                        'data'          => array_merge(array('-1' => '(new)'), ArrayHelper::map(Mandants::find()->orderBy('ID_Mandant')->asArray()->all(), 'ID_Mandant', 'Name')),
                                        'options'       => ['placeholder' => Yii::t('app', 'Choose Mandants')],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ])->label(false)
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?=
                                Html::submitButton('<span class="fa fa-check"></span>&nbsp;' .
                                    (Yii::t('app', 'Import')), ['name' => 'SubmitButton', 'value' => 'ImportMandant', 'class' => 'btn btn-success pull-right'])
                                ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

            </div>
        <?php } ?>


        <?php if (Yii::$app->user->can('team_member')) { ?>
            <div class="row">

                <div class="col-md-9">
                    <!-- Last connected users -->
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span
                                        class="fa fa-users"></span> <?= Yii::t('appMenu', 'Login history') ?>
                            </h3>
                        </div>

                        <div class="box-body">
                            <div class="table-responsive">
                                <?php
                                Pjax::begin();
                                echo $lastConnectedUsers;
                                Pjax::end();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- / start new column / -->

                <div class="col-md-3">
                    <!-- Flush cache box -->
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span
                                        class="fa fa-trash"></span> <?= Yii::t('appMenu', 'Flush cache') ?></h3>
                        </div>

                        <div class="box-body">
                            <p><?= Html::submitButton('<span class="fa fa-database"></span>&nbsp; ' .
                                    (Yii::t('app', 'Database cache')), ['name' => 'SubmitButton', 'value' => 'FlushCache', 'class' => 'btn btn-success btn-block']); ?></p>

                            <p><?= Html::submitButton('<span class="fa fa-files-o"></span>&nbsp; ' .
                                    (Yii::t('app', 'Assets cache')), ['name' => 'SubmitButton', 'value' => 'FlushAssetsCache', 'class' => 'btn btn-success btn-block']); ?></p>
                        </div>
                    </div>

                    <!-- Cleanup local files box -->
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span
                                        class="fa fa-file"></span> <?= Yii::t('appMenu', 'Cleanup Local Files') ?>
                            </h3>
                        </div>

                        <div class="box-body">
                            <p><?= (Yii::$app->user->can('team_member')) ? $form->field($model, 'dryrun')->widget(CheckboxX::class, ['pluginOptions' => ['threeState' => false], 'autoLabel' => true])->label(false) : '<br>' ?></p>

                            <p><?=
                                Html::submitButton('<span class="fa fa-trash"></span>&nbsp;' .
                                    (Yii::t('app', 'Cleanup local')), ['name' => 'SubmitButton', 'value' => 'CleanupFiles', 'class' => 'btn btn-default btn-block'])
                                ?></p>
                        </div>
                    </div>

                    <?php if (!Lx::IsMaster()) { ?>
                        <!-- Sync translations box -->
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title"><span
                                            class="fa fa-globe"></span> <?= Yii::t('appMenu', 'Sync translations') ?>
                                </h3>
                            </div>

                            <div class="box-body text-center">
                                <p><?= ('<strong>' . $pendinguploads . '</strong> messages pending.'); ?></p>

                                <p><?= Html::submitButton('<span class="fa fa-refresh"></span>&nbsp;' .
                                        (Yii::t('app', 'Sync translations')), ['name' => 'SubmitButton', 'value' => 'SyncTranslations', 'class' => 'btn btn-default btn-block']); ?></p>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Maintenance mode box -->
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><span
                                        class="fa fa-exclamation-triangle"></span> <?= Yii::t('appMenu', 'Maintenance mode') ?>
                            </h3>
                        </div>

                        <div class="box-body">
                            <?= $form->field($model, 'maintenancemode')->radioList(
                                [0 => Yii::t('app', 'No'), 1 => Yii::t('app', 'Yes')],
                                ['inline' => true])->label(false); ?>

                            <?= Html::submitButton('<span class="fa fa-wrench"></span>&nbsp; ' .
                                (Yii::t('app', 'Set mode')), ['name' => 'SubmitButton', 'value' => 'Maintenancemode', 'class' => 'btn btn-success btn-block']); ?>
                        </div>
                    </div>
                </div>

            </div> <!-- // end .row -->
            
            <div class="row">

            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><span
                                    class="fa fa-download"></span> <?= Yii::t('appMenu', 'Import Users'); ?>
                        </h3>
                    </div>

                    <div class="box-body">
                        <p><?= FileInput::widget([
                                'name'          => 'ImportJsonFile',
                                'pluginOptions' => [
                                    'showRemove'            => false,
                                    'showUpload'            => false,
                                    'uploadAsync'           => true,
                                    'fileActionSettings'    => ['showZoom' => false, 'showDrag' => false, 'showUpload' => true, 'showRemove' => true],
                                    'previewFileType'       => 'any',
                                    'allowedFileExtensions' => ['json', 'xls', 'xlsx', 'dta'],
                                    'uploadUrl'             => Url::to(['upload'])],
                                'pluginEvents'  => [
                                    // trigger upload method immediately after files are selected
                                    'filebatchselected' => 'function(event) { $(event.target).fileinput("upload"); }']
                            ]); ?></p>

                        <p><?= (Yii::$app->user->can('team_member')) ? $form->field($model, 'faker')->widget(CheckboxX::class, ['pluginOptions' => ['threeState' => false], 'autoLabel' => true])->label(false) : '' ?></p>

                        <p><?= Html::submitButton('<span class="fa fa-check"></span>&nbsp; ' .
                                (Yii::t('app', 'Import')), ['name' => 'SubmitButton', 'value' => 'ImportJson', 'class' => 'btn btn-default btn-block']); ?></p>
                    </div>
                </div>
            </div>

        </div> <!-- // end first .row -->
        <?php } ?>

        <?php ActiveForm::end(); ?>

    <?php } ?>

</div> <!-- // end .import-form -->