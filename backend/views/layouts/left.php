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


            <?=
            Menu::widget(
                    [
//                                'encodeLabels' => false,
                        "items" => [
                            ["label" => "Home", "url" => "/admin/index", "icon" => "home"],
                            // MENU => Admin
                            [
                                'label'   => MenuTypes::adminText(),
                                'icon'    => MenuTypes::ADMIN_ICON_MENU,
                                'url'     => '#',
                                'visible' => Yii::$app->user->can('team_admin'),
                                'items' => [
                                    [
                                        'label'   => MenuTypes::clubsText(),
                                        'icon'    => MenuTypes::CLUBS_ICON_MENU,
                                        'url'     => Url::toRoute(['clubs/index']),
                                        'active'  => ($route == 'clubs/index' || $route == 'location/index' || $route == 'fees/index' || $route == 'membershiptype/index'),
                                        'visible' => Yii::$app->user->can('team_admin')
                                    ],
                                    [
                                        'label'   => MenuTypes::adminusersText(),
                                        'icon'    => MenuTypes::ADMINRBAC_ICON_MENU,
                                        'url'     => Url::toRoute(['adminusers/index']),
                                        'active'  => ($route == 'authitem/index') || $route == 'adminusers/index' || $route == 'authitem/indexp',
                                        'visible' => Yii::$app->user->can('team_admin')
                                    ],
                                    [
                                        'label'   => MenuTypes::membersText(),
                                        'icon'    => MenuTypes::MEMBERS_ICON_MENU,
                                        'url'     => Url::toRoute(['members/index']),
                                        'active'  => ($route == 'members/index'),
                                        'visible' => Yii::$app->user->can('team_admin')
                                    ],
                                    [
                                        'label'   => MenuTypes::utilitiesText(),
                                        'icon'    => MenuTypes::IMPORT_ICON_MENU,
                                        'url'     => Url::toRoute(['import/index']),
                                        'active'  => ($route == 'import/index'),
                                        'visible' => Yii::$app->user->can('team_admin')
                                    ],
                                ] // items
                            ],
                            // MENU => CONTENT
                            [
                                'label'   => Yii::t('appMenuTypes', 'Content'),
                                'icon'    => MenuTypes::ADMIN_ICON_MENU,
                                'url'     => '#',
                                'visible' => Yii::$app->user->can('team_admin'),
                                'items' => [
                                    [
                                        'label'   => MenuTypes::newsText(),
                                        'icon'    => MenuTypes::NEWS_ICON_MENU,
                                        'url'     => Url::toRoute(['news/index']),
                                        'active'  => ($route == 'news/index' || $route == 'tags/index'),
                                        'visible' => Yii::$app->user->can('team_admin')
                                    ],
                                    [
                                        'label'  => MenuTypes::playdatesText(),
                                        'icon'   => MenuTypes::PLAYDATES_ICON_MENU,
                                        'url'    => Url::toRoute(['playdates/index']),
//                                                'visible' => $perm->isMenuTypesVisible('texts'),
                                        'active' => ($route == 'playdates/index' || $route == 'gamesboard/index' || $route == 'reserves/index' || $route == 'scores/index')
                                    ],
                                    [
                                        'label'   => MenuTypes::translationsText(),
                                        'icon'    => MenuTypes::TRANSLATIONS_ICON_MENU,
                                        'url'     => Url::toRoute(['message/index']),
                                        'active'  => ($route == 'message/index'),
                                        'visible' => Yii::$app->user->can('team_admin')
                                    ],
                                    [
                                        'label'  => MenuTypes::textblocksText(),
                                        'icon'   => MenuTypes::TEXTBLOCKS_ICON_MENU,
                                        'url'    => Url::toRoute(['texts/index']),
//                                                'visible' => $perm->isMenuTypesVisible('texts'),
                                        'active' => ($route == 'texts/index')
                                    ],
                                    [
                                        'label'   => MenuTypes::logText(),
                                        'icon'    => MenuTypes::LOGS_ICON_MENU,
                                        'url'     => Url::toRoute(['log/index']),
                                        'active'  => ($route == 'log/index'),
                                        'visible' => Yii::$app->user->can('team_admin')
                                    ],
                                ] // CONTENT
                            ],
                        ],
                    ]
            )
            ?>
        </section>

    </aside>

        <?php } ?>