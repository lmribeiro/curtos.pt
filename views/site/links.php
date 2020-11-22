<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LinkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->name . " | " . Yii::t('app', 'Links');
$this->params['modals'][] = "delete";
$this->params['modals'][] = "renew";

?>
<div class="section mt-5">
    <div class="container">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <h3 class="display-3"><?= Yii::t('app', 'Curtos') ?></h3>
                    <p class="lead"><?= Yii::t('app', 'Os teus links curtos') ?>.</p>

                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="">
            <div class="row pt-4 px-0">
                <div class="table-responsive-sm">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
//                    ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'user',
                                'label' => Yii::t('app', 'Utilizador'),
                                'format' => 'raw',
                                'visible' => Yii::$app->user->identity->role == 'ADMIN',
                                'value' => function ($model) {
                                    return $model->user ? $model->user->username : null;
                                },
                            ],
                            'target:url',
                            [
                                'attribute' => 'short',
                                'label' => Yii::t('app', 'Curto'),
                                'format' => 'html',
                                'value' => function ($model) {
                                    return Html::a($model->short, Url::base(true) . "/" . $model->short, ['target' => 'new']);
                                }
                            ],
                            'visit_count',
                            'expires_after:datetime',
                            //'created_at',
                            //'updated_at',
                            $actionCol = Yii::$app->params['actions'](
                                [
                                    'template' => '{view}&nbsp;{delete}'
                                ]),
                        ],
                    ]);

                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
