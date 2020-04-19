<?php

return [
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'targets' => [
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning'],
        ],
        [
            'class' => 'yii\log\EmailTarget',
            'levels' => ['error'],
//                    'categories' => ['application'],
            'message' => [
                'from' => ['curtos.ot'],
                'to' => ['luis.mribeiro@sapo.pt'],
                'subject' => 'Errors at Curtos.pt',
            ],
        ],
    ],
];
