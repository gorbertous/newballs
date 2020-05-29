<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

if (!empty(Yii::$app->session->get('member_photo'))) {
    $profileThumb90 = '@uploadsURL/profile-thumbs/90x90-' . Yii::$app->session->get('member_photo');
    $profileThumb25 = '@uploadsURL/profile-thumbs/25x25-' . Yii::$app->session->get('member_photo');
} else {
    $profileThumb90 = '/img/profile-default90x90.png';
    $profileThumb25 = '/img/profile-default25x25.png';
}
$this->registerCss('.popover-x {display:none}');
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <?php if (!Yii::$app->user->isGuest) { ?>
    
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                
            </li>
            
        </ul>
        
      

        <!-- Right navbar links -->
       <ul class="navbar-nav ml-auto">


           <li class="nav-item dropdown user-menu">
               <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="<?= Yii::t('app', 'Open profile menu'); ?>">
                   <?= Html::img($profileThumb25, ['class' => 'user-image', 'alt' => StringHelper::truncate(Html::encode(Yii::$app->user->identity->username), 2)]); ?>
                   <span class="hidden-xs">
                       <strong><?= StringHelper::truncate(Html::encode(Yii::$app->user->identity->username), 20); ?></strong> 
                       &nbsp;&nbsp;
                       <span class="caret" style="border-width: 5px;"></span>
                   </span>
               </a>

               <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                   <!-- User image -->
                   <li class="user-header bg-primary">

                       <?= Html::img($profileThumb90, ['class' => 'img-circle', 'alt' => StringHelper::truncate(Html::encode(Yii::$app->user->identity->username), 2)]); ?>

                       <p>
                            <?= Yii::$app->session->get('member_name')?>
                           <small><?= Yii::t('app', 'Member Since'); ?> <?= Yii::$app->session->get('member_since')?></small>
                       </p>
                   </li>

                   <li class="user-body">
                       <div class="row">
                           <div class="col-4 text-center">
                               <a data-toggle="tooltip" title="<?= Yii::t('app', 'Light Theme'); ?>" href="#" data-change-theme-color="skin-blue-light"><span style="width: 100%; height: 30px; border: 1px solid #ccc; background-color: #f9fafc; display: block;"></span></a>
                           </div>

                           <div class="col-4 text-center">
                               <a data-toggle="tooltip" title="<?= Yii::t('app', 'Dark Theme'); ?>" href="#" data-change-theme-color="skin-blue"><span style="width: 100%; height: 30px; border: 1px solid #222d32; background-color: #222d32; display: block;"></span></a>
                           </div>
                       </div>

                   </li>

                   <li class="user-footer">
                       <div class="float-left">
                           <button title="<?= Yii::t('app', 'Update'); ?>" value="<?= Url::toRoute(['user/updateacc', 'id' => Yii::$app->user->identity->id]); ?>" class="btn btn-outline-secondary showModalButton">
                               <?= Yii::t('app', 'Account'); ?>
                           </button>
                           <button title="<?= Yii::t('app', 'Profile'); ?>" value="<?= Url::to(['members/update', 'id' => Yii::$app->session->get('member_id')]); ?>" class="btn btn-outline-secondary showModalButton">
                               <?= Yii::t('app', 'Profile'); ?>
                           </button>
                       </div>

                       <div class="float-right">
                           <a href="<?= Url::toRoute(['site/logout']); ?>" class="btn btn-outline-secondary"><i class="fas fa-sign-out-alt"></i> <?= Yii::t('app', 'Logout'); ?></a>
                       </div>
                   </li>
               </ul>
           </li>
         
       </ul>

           
    <?php } ?>
</nav>

