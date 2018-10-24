<?php

use common\dictionaries\MenuTypes;
use yii\helpers\Url;
use yii\helpers\StringHelper;
use dmstr\widgets\Menu;
use yii\helpers\Html;

$route = $this->context->route;

if (!empty(Yii::$app->session->get('member_photo'))) {
    $profileThumb90 = '@uploadsURL/profile-thumbs/90x90-' . Yii::$app->session->get('member_photo');
    $profileThumb25 = '@uploadsURL/profile-thumbs/25x25-' . Yii::$app->session->get('member_photo');
} else {
    $profileThumb90 = '/img/profile-default90x90.png';
    $profileThumb25 = '/img/profile-default25x25.png';
}


if (!Yii::$app->user->isGuest) {
    ?>
    <aside class="main-sidebar">

        <section class="sidebar">
            
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    
                    <?= Html::img($profileThumb90, ['class' => 'img-circle', 'alt' => StringHelper::truncate(Html::encode(Yii::$app->user->identity->username), 2)]); ?>
                </div>
                <div class="pull-left info">
               
                </div>
            </div>
            
            <!-- sidebar menu -->
            <?=
            Menu::widget(
            [
              'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree', 'data-accordion' => 0],
//                                'encodeLabels' => false,
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
                                'label'   => MenuTypes::clubText() . ' Stats',
                                'icon'    => MenuTypes::CLUB_ICON_MENU,
                                'url'     => Url::toRoute(['clubs/stats']),
                                'active'  => ($route == 'clubs/stats')
                            ],
                            [
                                'label'   => MenuTypes::membersText(),
                                'icon'    => MenuTypes::MEMBERS_ICON_MENU,
                                'url'     => Url::toRoute(['members/membership']),
                                'active'  => ($route == 'members/membership'),
                                'visible' => Yii::$app->user->can('reader')
                            ],
                            [
                                'label'   => MenuTypes::newsText(),
                                'icon'    => MenuTypes::NEWS_ICON_MENU,
                                'url'     => Url::toRoute(['news/news']),
                                'active'  => ($route == 'news/news' || $route == 'tags/index'),
                                'visible' => Yii::$app->user->can('reader')
                            ],
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
                                'label'   => MenuTypes::playdatesText(),
                                'icon'    => MenuTypes::PLAYDATES_ICON_MENU,
                                'url'     => Url::toRoute(['playdates/index']),
//                                                'visible' => $perm->isMenuVisible('texts'),
                                'active'  => ($route == 'playdates/index' || $route == 'gamesboard/index' || $route == 'reserves/index' || $route == 'scores/index'),
                                'visible' => Yii::$app->user->can('writer')
                            ],
                            [
                                'label'   => MenuTypes::newsText(),
                                'icon'    => MenuTypes::NEWS_ICON_MENU,
                                'url'     => Url::toRoute(['news/index']),
                                'active'  => ($route == 'news/index' || $route == 'tags/index'),
                                'visible' => Yii::$app->user->can('writer')
                            ],
                            [
                                'label'  => MenuTypes::textblocksText(),
                                'icon'   => MenuTypes::TEXTBLOCKS_ICON_MENU,
                                'url'    => Url::toRoute(['texts/index']),
                                'visible' => Yii::$app->user->can('team_admin'),
                                'active' => ($route == 'texts/index')
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

        </section>

    </aside>

<?php } ?>