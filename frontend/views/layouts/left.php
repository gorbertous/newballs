<?php

use common\dictionaries\MenuTypes as Menu;

use yii\helpers\Url;
use yii\helpers\StringHelper;
use yii\helpers\Html;

$route = $this->context->route;

if (!empty(Yii::$app->session->get('member_photo'))) {
    $profileThumb90 = '@uploadsURL/profile-thumbs/90x90-' . Yii::$app->session->get('member_photo');
    $profileThumb25 = '@uploadsURL/profile-thumbs/25x25-' . Yii::$app->session->get('member_photo');
} else {
    $profileThumb90 = '/static/images/profile-default90x90.png';
    $profileThumb25 = '/static/images/profile-default25x25.png';
}


if (!Yii::$app->user->isGuest ) { ?>
     <div class="col-md-3 left_col">
            <div class="left_col scroll-view">

                <div class="navbar nav_title" style="border: 0;">
                    <a href="/admin" class="site_title"><i class="fa fa-paw"></i> <span><?= Html::encode(Yii::$app->user->member->club->name); ?></span></a>
                    
                </div>
                <div class="clearfix"></div>

                <!-- menu profile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                        <?= Html::img($profileThumb90, ['class' => 'img-circle profile_img', 'alt' => Html::encode(Yii::$app->user->identity->username)]); ?>
                        
                    </div>
                    <div class="profile_info">
                        <span>Welcome,</span>
                       
                         <h2><?= StringHelper::truncate(Html::encode(Yii::$app->user->member->name), 20); ?></h2>
                    </div>
                </div>
                <!-- /menu prile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="clear"></div>
                    <div class="menu_section">
                       
                        <?=
                        \yiister\gentelella\widgets\Menu::widget(
                            [
//                                'encodeLabels' => false,
                                "items" => [
                                    ["label" => "Home", "url" => "/dashboard/index", "icon" => "home"],
                                    
                                    
                                     // MENU => Admin

                                    [
                                        'label'   =>  Menu::adminText(),
                                        'icon'    => Menu::ADMIN_ICON_MENU,
                                        'url'     => '#',
                                        'visible' => Yii::$app->user->can('team_admin'),

                                        'items' => [

                                            [
                                                'label'   => Menu::clubsText(),
                                                'icon'    => Menu::CLUBS_ICON_MENU,
                                                'url'     => Url::toRoute(['clubs/index']),
                                                'active'  => ($route == 'clubs/index' || $route == 'location/index' || $route == 'fees/index' || $route == 'membershiptype/index'),
                                                'visible' => Yii::$app->user->can('team_admin')
                                            ],
                                           
                                            
                                            [
                                                'label'   => Menu::membersText(),
                                                'icon'    => Menu::MEMBERS_ICON_MENU,
                                                'url'     => Url::toRoute(['members/index']),
                                                'active'  => ($route == 'members/index'),
                                                'visible' => Yii::$app->user->can('team_admin')
                                            ],
                                          
                                           
                     
                                        ] // items
                                    ],
                                    
                                     // MENU => CONTENT

                                    [
                                        'label'   =>  Yii::t('appMenu', 'Content'),
                                        'icon'    => Menu::ADMIN_ICON_MENU,
                                        'url'     => '#',
                                        'visible' => Yii::$app->user->can('team_admin'),

                                        'items' => [
                                            [
                                                'label'   => Menu::newsText(),
                                                'icon'    => Menu::NEWS_ICON_MENU,
                                                'url'     => Url::toRoute(['news/index']),
                                                'active'  => ($route == 'news/index' || $route == 'tags/index'),
                                                'visible' => Yii::$app->user->can('team_admin')
                                            ],
                                            [
                                                'label'   => Menu::playdatesText(),
                                                'icon'    => Menu::PLAYDATES_ICON_MENU,
                                                'url'     => Url::toRoute(['playdates/index']),
//                                                'visible' => $perm->isMenuVisible('texts'),
                                                'active'  => ($route == 'playdates/index' || $route == 'gamesboard/index' || $route == 'reserves/index' || $route == 'scores/index')
                                            ],
                                          
                                            [
                                                'label'   => Menu::textblocksText(),
                                                'icon'    => Menu::TEXTBLOCKS_ICON_MENU,
                                                'url'     => Url::toRoute(['texts/index']),
//                                                'visible' => $perm->isMenuVisible('texts'),
                                                'active'  => ($route == 'texts/index')
                                            ],
                                          
                     
                                        ] // CONTENT
                                    ],
                                   
                                    
                                ],
                            ]
                        )
                        ?>
                    </div>

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

<?php } ?>