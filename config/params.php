<?php

use yii\helpers\Url;
use yii\helpers\Html;

return [
    'adminEmail' => 'hello@curtos.pt',
    'senderEmail' => 'hello@curtos.pt',
    'senderName' => 'Curtos.pt ',
    'expiresAfter' => 30, // Time in days
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
        'template' => '{view}&nbsp;{update}&nbsp;{renew}&nbsp;{delete}',
        'buttons' => [
            'view' => function ($url) {
                return Html::tag('span', Html::a('<i class="fa fa-eye"></i>', $url, ['class' => 'btn btn-primary btn-sm btn-fab btn-icon btn-round']), ['data-toggle' => 'tooltip', 'data-title' => Yii::t('app', 'Ver')]);
            },
            'update' => function ($url) {
                return Html::tag('span', Html::a('<i class="fas fa-pencil-alt"></i>', $url, ['class' => ' text-warning']), ['data-toggle' => 'tooltip', 'data-title' => Yii::t('app', 'Editar')]);
            },
            'renew' => function ($url, $model) {
                return Html::tag('span', Html::a('<i class="fas fa-calendar-plus"></i>', '#', ['class' => 'btn btn-success btn-sm btn-fab btn-icon btn-round btn-renew', 'data-id' => $model->id, 'data-toggle' => 'modal', 'data-target' => '#renew_modal']), ['data-toggle' => 'tooltip', 'data-title' => Yii::t('app', 'Renovar data de expiração')]);
            },
            'delete' => function ($url, $model) {
                return Html::tag('span', Html::a('<i class="fas fa-trash"></i>', '#', ['class' => 'btn btn-danger btn-sm btn-fab btn-icon btn-round btn-delete', 'data-id' => $model->id, 'data-toggle' => 'modal', 'data-target' => '#delete_modal']), ['data-toggle' => 'tooltip', 'data-title' => Yii::t('app', 'Apagar')]);
            },
        ],
        'urlCreator' => function ($action, $data) {
            return Url::to(["curto/$data->short"]);
        },
    ],
];
