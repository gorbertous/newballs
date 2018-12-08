<?php

use common\dictionaries\MenuTypes;
use yii\helpers\Url;
use dmstr\widgets\Menu;

$route = $this->context->route;



if (!Yii::$app->user->isGuest) {
    ?>
    <aside class="main-sidebar">

        <section class="sidebar">

            <?= Menu::widget(
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
                        'items'   => [
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
                                'active'  => ($route == 'message/index' || $route == 'message/indexdupes' || $route == 'message/scanrun' || $route == 'message/scannew' || $route == 'message/indexunused' || $route == 'message/scanblacklisted'),
                                'visible' => Yii::$app->user->can('team_admin')
                            ],
                            [
                                'label'   => MenuTypes::textblocksText(),
                                'icon'    => MenuTypes::TEXTBLOCKS_ICON_MENU,
                                'url'     => Url::toRoute(['texts/index']),
                                'visible' => Yii::$app->user->can('team_admin'),
                                'active'  => ($route == 'texts/index')
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