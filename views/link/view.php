<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model app\models\Link */
/* @var array $byBrowser */
/* @var array $byCountry */


$this->title = Yii::t('app', 'Curto') . " " . $model->short;
$link = Url::base() . "/" . $model->short;
YiiAsset::register($this);
$this->params['modals'][] = "delete";
$this->params['modals'][] = "renew";
?>

<div class="section mt-5">
    <div class="container">
        <div class="page-header">
            <div class="row">
                <div class="col-md-8 mx-auto text-center mb-2">
                    <h1 class="display-3"><?= $this->title ?></h1>
                    <p class="lead">
                        <a href="<?= $link ?>" target="new">
                            <?= $link ?>
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="">

                    <div class="row">
                        <div class="col-8">
                            <h4 class="text-black"><?= Html::a($link, $link, ['target' => 'new']); ?></h4>

                        </div>
                        <div class="col-4">
                            <div class="float-lg-right">
                                <?= Html::tag('span', Html::a('<i class="fas fa-calendar-plus"></i> ' . Yii::t('app', 'Renovar  expiração'), '#', ['class' => 'btn btn-success btn-sm  btn-renew', 'data-id' => $model->id, 'data-toggle' => 'modal', 'data-target' => '#renew_modal'])); ?>
                                <?= Html::tag('span', Html::a('<i class="fas fa-trash"></i> ' . Yii::t('app', 'Apagar'), '#', ['class' => 'btn btn-danger btn-sm btn-delete', 'data-id' => $model->id, 'data-toggle' => 'modal', 'data-target' => '#delete_modal'])); ?>
                            </div>
                        </div>

                    </div>

                    <div class="">
                        <div class="row">
                            <div class="col-lg-8 col-xs-12">
                                <?= DetailView::widget([
                                    'model' => $model,
                                    'attributes' => [
                                        [
                                            'attribute' => 'user_id',
                                            'format' => 'raw',
                                            'visible' => Yii::$app->user->identity->role == 'ADMIN',
                                            'value' => $model->user ? $model->user->username : null,
                                        ],
                                        [
                                            'attribute' => 'target',
                                            'format' => 'raw',
                                            'value' => Html::a($model->target, $model->target, ['target' => 'new']),
                                        ],
                                        'expires_after',
                                        'created_at',
                                        'updated_at',
                                    ],
                                ]) ?>
                            </div>
                            <div class="col-lg-4 col-xs-12">
                                <div class="card bg-light mb-3">
                                    <div class="card-body text-center" style="padding: 1.1rem;">
                                        <i class="fas fa-users fa-5x"></i>
                                        <h4 class="card-title "><?= $model->visit_count ?></h4>
                                        <p class="card-text text-uppercase"><?= Yii::t('app', 'Visitas'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?= $this->render('_partials/byBrowser', [
            'data' => $byBrowser,
        ]) ?>

        <?= $this->render('_partials/byCountry', [
            'data' => $byCountry,
        ]) ?>

    </div>
</div>

