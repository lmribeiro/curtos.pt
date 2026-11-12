<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;
use yii\widgets\DetailView;
use app\assets\StatisticsAsset;


/* @var $this yii\web\View */
/* @var $model app\models\Link */
/* @var array $byBrowser */
/* @var array $byCountry */
/* @var array $statistics */


$this->title = Yii::t('app', 'Curto') . " " . $model->short;
$link ="https://curtos.pt/" . $model->short;
YiiAsset::register($this);
if ($statistics['visits'] > 0) {
    StatisticsAsset::register($this);
}
$expiration = $model->expires_after ? strtotime($model->expires_after) : false;
$expired = $expiration !== false && $expiration <= time();
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
                        <div class="col-4">
                            <h4 class="text-black">#<?= $model->short ?></h4>
                        </div>
                        <div class="col-8">
                            <div class="float-right">
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
                                            'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'ADMIN',
                                            'value' => $model->user ? $model->user->username : null,
                                        ],
                                        [
                                            'attribute' => 'target',
                                            'format' => 'raw',
                                            'value' => Html::a(Html::encode($model->target), $model->target, ['target' => 'new']),
                                        ],
                                        [
                                            'label' => Yii::t('app', 'Estado'),
                                            'value' => $expired ? Yii::t('app', 'Expirado') : Yii::t('app', 'Ativo'),
                                        ],
                                        [
                                            'label' => Yii::t('app', 'Tempo até expirar'),
                                            'value' => $expiration === false ? Yii::t('app', 'Sem limite') : ($expired ? Yii::t('app', 'Já expirou') : Yii::$app->formatter->asRelativeTime($expiration)),
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
                                        <p class="card-text text-uppercase"><?= Yii::t('app', 'Visitas históricas'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?= $this->render('_partials/analytics', ['statistics' => $statistics, 'model' => $model]) ?>

        <?= $this->render('_partials/byBrowser', [
            'data' => $byBrowser,
        ]) ?>

        <?= $this->render('_partials/byCountry', [
            'data' => $byCountry,
        ]) ?>

    </div>
</div>

