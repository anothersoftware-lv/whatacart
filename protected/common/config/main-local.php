<?php
$config = [
    'components' => [
        'db' => [
            'dsn'           => $dsn,
            'username'      => $username,
            'password'      => $password,
            'emulatePrepare'=> true,
            'tablePrefix'   => 'tbl_',
            'schemaCache'   => 'cache',
            'attributes'    => [
                PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        ]
    ]
];

if (YII_ENV_DEV && YII_DEBUG)
{
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
                                    'class' => 'yii\gii\Module'
                                ];
}

return $config;