<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LinkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->name." | ".Yii::t('app', 'Links');
$this->params['modals'][] = "delete";

?>
<div class="section">
    <div class="container">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <!--<span class="badge badge-primary badge-pill mb-3">&nbsp;</span>-->
                    <h3 class="display-3"><?= Yii::t('app', 'Os teus Links') ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="px-lg-3">
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
                                    return Html::a($model->short, Url::base(true)."/s?i=".$model->short, ['target' => 'new']);
                                }
                            ],
                            'visit_count',
                            'expires_after:datetime',
                            //'created_at',
                            //'updated_at',
                            $actionCol = Yii::$app->params['actions'](
                            [
                                'template' => '{delete}'
                            ]),
                        ],
                    ]);

                    ?>

                </div>
            </div>
        </div>
    </div>
</section>
