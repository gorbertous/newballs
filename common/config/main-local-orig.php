<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=balls-tennis',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,

            'transport' => [
                'class'    => 'Swift_SmtpTransport',
                'host'     => 'smtp.gmail.com',
                'username' => 'gorbertous',
                'password' => 'gb111111',
                'port' => '587',
                'encryption' => 'tls',
                'streamOptions' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],

                'plugins' => [
                    /*[
                        // specify 30 emails per-minute limit
                        'class' => 'Swift_Plugins_ThrottlerPlugin',
                        'constructArgs' => ['30'],
                    ],*/
                    [
                        // specify to reconnect after 8 mails and pause
                        // for 10 secs before reconnecting
                        'class' => 'Swift_Plugins_AntiFloodPlugin',
                        'constructArgs' => [8, 10]
                    ],
                ],
            ],
        ],
    ],
];
