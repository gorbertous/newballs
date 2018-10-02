<?php
use common\helpers\Helpers;
use yii\widgets\DetailView;
use common\helpers\Language as Yii;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

?>
<div class="user-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => 
        [
//            [
//                'attribute' => 'first_name',
//                'label' => Yii::t('modelattr', 'First Name'),
//            ],
//            [
//                'attribute' => 'last_name',
//                'label' => Yii::t('modelattr', 'Last Name'),
//            ],
            [
                'attribute' => 'username',
                'label' => Yii::t('modelattr', 'Username'),
            ],
           
            'email:email',
            //'password_hash',
            [
                'attribute'=>'status',
                'label' => Yii::t('modelattr', 'Status'),
                'value' => $model->getStatusName(),
            ],
            [
                'attribute'=>'item_name',
                'label' => Yii::t('modelattr', 'Role'),
                'value' => $model->getRoleName(),
            ],
            //'auth_key',
            //'password_reset_token',
            //'account_activation_token',
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>
   
    <div class="clearfix"></div>
    <?php echo Helpers::getModalFooter($model, $model->id, 'one', [
        'buttons' => ['cancel']
    ]); ?>

</div>
