<?php

use common\dictionaries\MenuTypes;
use yii\helpers\Url;
use yii\helpers\StringHelper;
use dmstr\adminlte\widgets\Menu;
use yii\helpers\Html;

$route = $this->context->route;

if (!empty(Yii::$app->session->get('member_photo'))) {
    $profileThumb90 = '@uploadsURL/profile-thumbs/90x90-' . Yii::$app->session->get('member_photo');
    $profileThumb25 = '@uploadsURL/profile-thumbs/25x25-' . Yii::$app->session->get('member_photo');
} else {
    $profileThumb90 = '/img/profile-default90x90.png';
    $profileThumb25 = '/img/profile-default25x25.png';
}

$logo = '/img/tennis-ball.png';


if (!Yii::$app->user->isGuest) {
    ?>
    <aside class="main-sidebar sidebar-dark-secondary elevation-4">
        
        <!-- Brand Logo -->
        <a href="<?= Yii::$app->homeUrl ?>" class="brand-link">
            <img src="<?= $logo ?>" alt="<?= Yii::$app->session->get('club_name') ?>" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light"><?= Yii::$app->session->get('club_name') ?></span>
        </a>
        <div class="sidebar">
            
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
               
              <div class="image">
                <?= Html::img($profileThumb90, ['class' => 'img-circle elevation-2', 'alt' => StringHelper::truncate(Html::encode(Yii::$app->user->identity->username), 2)]); ?>
              </div>
               
              <div class="info">
                  <?= Html::a( Yii::$app->session->get('member_name'), Yii::$app->homeUrl, ['class' => 'd-block']) ?>
                  <div class="nav-header font-weight-light"><small><?= Yii::t('app', 'Member Since'); ?> <?= Yii::$app->session->get('member_since')?></small></div>
               
              </div>
            </div>
          
            <nav class="mt-2">
            <!-- sidebar menu -->
            <?=
            Menu::widget(
            [
                'options' => ['class' => 'nav nav-pills nav-sidebar flex-column', 'data-widget'=> 'treeview', 'role'=> 'menu','data-accordion' => false],
                'encodeLabels' => false,
                "items" => [
//                                    ["label" => "Home", "url" => "/dashboard/index", "icon" => "home"],
                    // MENU => MEMBERS
                    [
                        'label'   => Yii::t('appMenu', 'Members Area'),
                        'icon'    => MenuTypes::ADMIN_ICON_MENU,
                        'url'     => '#',
                        'visible' => (Yii::$app->user->can('reader') && Yii::$app->session->get('member_is_active')),
                        'items' => [
                            [
                                'label'   => MenuTypes::rotaText(),
                                'icon'    => MenuTypes::ROTA_ICON_MENU,
                                'url'     => Url::toRoute(['rota/index']),
                                'active'  => ($route == 'rota/index'),
                                'visible' => Yii::$app->user->can('reader')
                            ],
                            [
                                'label'   => '<span class="left-menu-text">' . MenuTypes::yourGamesText()  . '</span>' . ' <span class="badge badge-info right">' . Yii::$app->session->get('member_pending_count') . '</span>',
                                'icon'    => MenuTypes::Y_GAMES_ICON_MENU,
                                'url'     => Url::toRoute(['yourgames/index']),
                                'active'  => ($route == 'yourgames/index'),
                                'visible' => Yii::$app->user->can('reader')
                            ],
                            [
                                'label'   => Yii::t('appMenu', 'Stats'),
                                'icon'    => MenuTypes::CLUB_ICON_MENU,
                                'url'     => Url::toRoute(['clubs/stats']),
                                'active'  => ($route == 'clubs/stats'),
                                'visible' => Yii::$app->user->can('reader')
                            ],
                            [
                                'label'   => MenuTypes::membersText(),
                                'icon'    => MenuTypes::MEMBERS_ICON_MENU,
                                'url'     => Url::toRoute(['members/membership']),
                                'active'  => ($route == 'members/membership'),
                                'visible' => Yii::$app->user->can('reader')
                            ],
                            [
                                'label'   => MenuTypes::photosText(),
                                'icon'    => MenuTypes::PHOTOS_ICON_MENU,
                                'url'     => Url::toRoute(['clubs/photos']),
//                                                'visible' => $perm->isMenuVisible('texts'),
                                'active'  => ($route == 'clubs/photos'),
                                'visible' => Yii::$app->user->can('reader')
                            ],
                            [
                                'label'   => MenuTypes::newsText(),
                                'icon'    => MenuTypes::NEWS_ICON_MENU,
                                'url'     => Url::toRoute(['news/index']),
                                'active'  => ($route == 'news/index' || $route == 'tags/index'),
                                'visible' => Yii::$app->user->can('reader')
                            ],
//                            [
//                                'label'   => MenuTypes::newsText(),
//                                'icon'    => MenuTypes::NEWS_ICON_MENU,
//                                'url'     => Url::toRoute(['news/news']),
//                                'active'  => ($route == 'news/news' || $route == 'tags/index'),
//                                'visible' => Yii::$app->user->can('reader')
//                            ],
                            [
                                'label'   => MenuTypes::rulesText(),
                                'icon'    => MenuTypes::RULES_ICON_MENU,
                                'url'     => Url::toRoute(['rules/index']),
                                'active'  => ($route == 'rules/index'),
                                'visible' => Yii::$app->user->can('reader')
                            ],
                        ] // MEMBERS
                    ],
                    // MENU => Admin
                    [
                        'label'   => MenuTypes::adminText(),
                        'icon'    => MenuTypes::UTILITIES_ICON_MENU,
                        'url'     => '#',
                        'visible' => (Yii::$app->user->can('writer') && Yii::$app->session->get('member_is_active')),
                        'items' => [
                            [
                                'label'   => MenuTypes::clubText(),
                                'icon'    => MenuTypes::CLUB_ICON_MENU,
                                'url'     => Url::toRoute(['clubs/index']),
                                'active'  => ($route == 'clubs/index' || $route == 'location/index' || $route == 'fees/index' || $route == 'membershiptype/index'),
                                'visible' => Yii::$app->user->can('writer')
                            ],
                            [
                                'label'   => MenuTypes::membersText(),
                                'icon'    => MenuTypes::MEMBERS_ICON_MENU,
                                'url'     => Url::toRoute(['members/index']),
                                'active'  => ($route == 'members/index'),
                                'visible' => Yii::$app->user->can('writer')
                            ],
                            [
                                'label'   => MenuTypes::adminusersText(),
                                'icon'    => MenuTypes::ADMINRBAC_ICON_MENU,
                                'url'     => Url::toRoute(['user/index']),
                                'active'  => ($route == 'user/index'),
                                'visible' => Yii::$app->user->can('team_admin')
                            ],
                            [
                                'label'   => MenuTypes::playdatesText(),
                                'icon'    => MenuTypes::PLAYDATES_ICON_MENU,
                                'url'     => Url::toRoute(['playdates/index']),
//                                                'visible' => $perm->isMenuVisible('texts'),
                                'active'  => ($route == 'playdates/index' || $route == 'gamesboard/index' || $route == 'reserves/index' || $route == 'scores/index'),
                                'visible' => Yii::$app->user->can('writer')
                            ],
                            [
                                'label'   => MenuTypes::photosText(),
                                'icon'    => MenuTypes::PHOTOS_ICON_MENU,
                                'url'     => Url::toRoute(['clubs/upload']),
//                                                'visible' => $perm->isMenuVisible('texts'),
                                'active'  => ($route == 'clubs/upload'),
                                'visible' => Yii::$app->user->can('writer')
                            ],
                           
                            [
                                'label'  => MenuTypes::textblocksText(),
                                'icon'   => MenuTypes::TEXTBLOCKS_ICON_MENU,
                                'url'    => Url::toRoute(['texts/index']),
                                'active' => ($route == 'texts/index'),
                                'visible' => Yii::$app->user->can('team_admin')
                            ],
                            [
                                'label'   => MenuTypes::logText(),
                                'icon'    => MenuTypes::LOGS_ICON_MENU,
                                'url'     => Url::toRoute(['log/index']),
                                'active'  => ($route == 'log/index' || $route == 'log/users'),
                                'visible' => Yii::$app->user->can('team_admin')
                            ],
                        ] // items
                    ],
                ],
            ]
            )
            ?>
            </nav>
        </div>

    </aside>

<?php } ?>