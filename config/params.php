<?php

use yii\helpers\Url;
use yii\helpers\Html;

return [
    'adminEmail' => 'hello@curtos.pt',
    'senderEmail' => 'hello@curtos.pt',
    'senderName' => 'Curtos.pt ',
    'passwordResetTokenExpire' => 3600,
//    'bsVersion' => '4.x',
    'actions' => function ($attributes) {
        $col = Yii::$app->params['actionCol'];
        foreach ($attributes as $key => $value) {
            $col[$key] = $value;
        }
        $col['header'] = Yii::t('app', 'Ações');
        return $col;
    },
    'actionCol' => [
        'class' => 'yii\grid\ActionColumn',
        'headerOptions' => ['class' => 'text-center'],
        'template' => '{view}&nbsp;{update}&nbsp;{delete}',
        'buttons' => [
            'view' => function ($url) {
                return Html::tag('span', Html::a('<i class="fa fa-eye"></i>', $url, ['class' => 'btn btn-primary btn-sm btn-fab btn-icon btn-round']), ['data-toggle' => 'tooltip', 'data-title' => Yii::t('app', 'Ver')]);
            },
            'update' => function ($url) {
                return Html::tag('span', Html::a('<i class="fas fa-pencil-alt"></i>', $url, ['class' => ' text-warning']), ['data-toggle' => 'tooltip', 'data-title' => Yii::t('app', 'Editar')]);
            },
            'delete' => function ($url, $model) {
                $action = explode('?', $url);
                return Html::tag('span', Html::a('<i class="fas fa-trash"></i>', '#', ['class' => 'btn btn-danger btn-sm btn-fab btn-icon btn-round btn-delete', 'data-url' => array_shift($action), 'data-id' => $model->id, 'data-toggle' => 'modal', 'data-target' => '#delete_modal']), ['data-toggle' => 'tooltip', 'data-title' => Yii::t('app', 'Apagar')]);
            },
        ],
        'urlCreator' => function ($action, $data) {
            return Url::to(["link/$action", 'id' => $data->id]);
        },
    ],
];
