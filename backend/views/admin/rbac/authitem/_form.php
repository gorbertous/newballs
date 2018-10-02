<?php

use yii\helpers\Html;
use common\helpers\ViewsHelper;
use backend\widgets\ActiveForm;
use common\dictionaries\RBACTypes;

$indexpage = array_slice(explode('/', Yii::$app->request->referrer), -1)[0];

?>

<div class="auth-item-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'form-authitem'
            ]);
    
    ?>
    <div class="well">
        <div class="row">
            <div class="col-md-12">
                <?= $form->hrwTextInputMax($model, 'name'); ?>
                <?= $form->hrwTextAreaMax($model, 'description');?>
            </div>
        </div>
        <?php if(substr($indexpage, 0, 6) == 'indexp'): ?>
            <?= $form->field($model, 'type')
                            ->hiddenInput(['value' => RBACTypes::RBAC_PERMISSIONS])
                            ->label(false);?>
            
        <?php else : ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    //print parent roles
                    if(!empty($model->parentsR)){
                        $parentroles = [];
                        foreach ($model->parentsR as $parent) {
                            $name = $parent->name;
                            if (!in_array($name, $parentroles)) {
                                array_push($parentroles, $name);
                            }
                        }
                        echo '<b>Parent Roles (which inherits from this role)</b><br>';
                        echo join(', ', $parentroles);
                        echo '<br><br>';
                    }

                    echo $form->hrwSelect2($model, 'auth_children_r_ids', [
                        'data'          => ViewsHelper::getAuthitems(RBACTypes::RBAC_ROLES),
                        'options'       => ['multiple' => true, 'id' => 'authchilditemroles'],
                        'pluginOptions' => ['allowClear' => true]]);

                    //print list of inherited permissions
                    if(!empty($model->childrenR)){
                        $childroles = [];
                        foreach ($model->childrenR as $child) {
                            $name = $child->name;
                            if (!in_array($name, $childroles)) {
                                array_push($childroles, $name);
                            }
                        }
                        //get permissions for all of the child roles passed
                        $permissions = $model->getRolePermissions($childroles);

                        echo '<b>Inherits permissions (this role inherits the following permissions)</b><br>';
                        echo join(', ', $permissions);
                        echo '<br><br>';
                    }

                    echo $form->hrwSelect2($model, 'auth_children_p_ids', [
                        'data'          => ViewsHelper::getAuthitems(RBACTypes::RBAC_PERMISSIONS),
                        'options'       => ['multiple' => true, 'id' => 'authchilditemsperm'],
                        'pluginOptions' => ['allowClear' => true]]);

                    echo $form->field($model, 'type')
                            ->hiddenInput(['value' => RBACTypes::RBAC_ROLES])
                            ->label(false);
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="row">

        <div class="col-md-12">
            <div class="form-group pull-right">
                <?=
                Html::submitButton('<span class="fa fa-check"></span>&nbsp;' .
                        ($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update')), ['class' => $model->isNewRecord ?
                            'btn btn-success' : 'btn btn-success'])
                ?>

                <?=
                Html::Button('<span class="fa fa-times"></span>&nbsp;' .
                        Yii::t('app', 'Cancel'), ['class' => 'btn btn-danger', 'data-dismiss' => 'modal'])
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
