<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/** @var array $statistics */
/** @var app\models\Link $model */
?>
<section class="link-analytics mt-5" aria-labelledby="analytics-heading">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h2 id="analytics-heading" class="h4 text-black"><?= Yii::t('app', 'Estatísticas detalhadas') ?></h2>
        <form method="get" action="<?= Html::encode(Url::to(['/link/view', 'id' => $model->short])) ?>" class="form-inline">
            <label for="statistics-period" class="mr-2"><?= Yii::t('app', 'Período') ?></label>
            <?= Html::dropDownList('days', $statistics['days'], [
                'all' => Yii::t('app', 'Todo o histórico'),
                7 => Yii::t('app', 'Últimos 7 dias'),
                30 => Yii::t('app', 'Últimos 30 dias'),
                90 => Yii::t('app', 'Últimos 90 dias'),
            ], ['id' => 'statistics-period', 'class' => 'form-control form-control-sm mr-2']) ?>
            <button type="submit" class="btn btn-primary btn-sm"><?= Yii::t('app', 'Aplicar') ?></button>
        </form>
    </div>
    <p class="text-muted small">
        <?php if ($statistics['days'] === 'all'): ?>
            <?= Yii::t('app', 'Todo o histórico') ?> ·
        <?php endif; ?>
        <?= Html::encode($statistics['start'] . ' - ' . $statistics['end']) ?>.
        <?= Yii::t('app', 'Todos os gráficos usam o período selecionado e apenas visitas com estatísticas detalhadas. O contador histórico pode ser superior.') ?>
    </p>
    <div class="row">
        <?php foreach ([
            Yii::t('app', 'Visitas no período') => Yii::$app->formatter->asInteger($statistics['visits']),
            Yii::t('app', 'IPs distintos no período') => Yii::$app->formatter->asInteger($statistics['uniqueIps']),
            Yii::t('app', 'Última visita registada') => $statistics['lastVisit'] ? Yii::$app->formatter->asDatetime($statistics['lastVisit']) : Yii::t('app', 'Sem visitas'),
        ] as $label => $value): ?>
            <div class="col-md-4 mb-3">
                <div class="card bg-light h-100">
                    <div class="card-body">
                        <p class="small mb-2"><?= Html::encode($label) ?></p>
                        <p class="h4 text-black mb-0"><?= Html::encode($value) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <p class="text-muted small"><?= Yii::t('app', 'IPs distintos são uma aproximação de visitantes únicos: várias pessoas podem partilhar um IP e uma pessoa pode usar vários IPs.') ?></p>

    <h3 class="h5 text-black mt-4"><?= Yii::t('app', 'Visitas por dia') ?></h3>
    <?php if (!$statistics['visits']): ?>
        <p class="text-muted"><?= Yii::t('app', 'Sem visitas com estatísticas detalhadas neste período.') ?></p>
    <?php else: ?>
        <div id="daily-chart" role="img" aria-label="<?= Html::encode(Yii::t('app', 'Visitas por dia no período selecionado')) ?>"></div>
        <details class="mb-4">
            <summary><?= Yii::t('app', 'Consultar os valores diários') ?></summary>
            <div class="table-responsive">
                <table class="table table-sm">
                    <caption class="sr-only"><?= Yii::t('app', 'Visitas por dia') ?></caption>
                    <thead><tr><th scope="col"><?= Yii::t('app', 'Data') ?></th><th scope="col"><?= Yii::t('app', 'Visitas') ?></th></tr></thead>
                    <tbody>
                    <?php foreach ($statistics['daily'] as $day): ?>
                        <tr><th scope="row"><?= Html::encode($day['date']) ?></th><td><?= $day['count'] ?></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </details>
        <script>
            am4core.ready(function () {
                const chart = am4core.create('daily-chart', am4charts.XYChart);
                chart.dateFormatter.inputDateFormat = 'yyyy-MM-dd';
                chart.data = <?= Json::htmlEncode($statistics['daily']) ?>;
                const dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                dateAxis.baseInterval = {timeUnit: 'day', count: 1};
                const valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.min = 0;
                valueAxis.maxPrecision = 0;
                const series = chart.series.push(new am4charts.LineSeries());
                series.dataFields.dateX = 'date';
                series.dataFields.valueY = 'count';
                series.stroke = am4core.color('#3fb7eb');
                series.strokeWidth = 3;
                series.fillOpacity = 0.1;
                series.tooltipText = '{dateX.formatDate("yyyy-MM-dd")}: {valueY}';
                chart.cursor = new am4charts.XYCursor();
                chart.cursor.xAxis = dateAxis;
            });
        </script>
    <?php endif; ?>

    <div class="row">
        <?php foreach ([
            'devices' => Yii::t('app', 'Dispositivos'),
            'systems' => Yii::t('app', 'Sistemas operativos'),
            'hours' => Yii::t('app', 'Visitas por hora'),
            'weekdays' => Yii::t('app', 'Visitas por dia da semana'),
            'cities' => Yii::t('app', 'Cidades - Top 10'),
            'regions' => Yii::t('app', 'Regiões - Top 10'),
            'sources' => Yii::t('app', 'Origens - Top 10'),
        ] as $key => $title): ?>
            <div class="col-lg-6 mb-4">
                <?= $this->render('distribution', ['title' => $title, 'items' => $statistics[$key], 'total' => $statistics['visits']]) ?>
            </div>
        <?php endforeach; ?>
    </div>
    <p class="text-muted small">
        <?= Yii::t('app', 'A localização é aproximada. As origens mostram apenas o domínio e são recolhidas nas novas visitas; acessos sem Referer aparecem como direto / desconhecido. Tablets são identificados nas novas visitas quando o navegador o permite.') ?>
    </p>
</section>
