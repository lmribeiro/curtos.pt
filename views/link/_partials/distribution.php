<?php

use yii\helpers\Html;

/** @var string $title */
/** @var array $items */
/** @var int $total */
?>
<div class="card bg-light h-100">
    <div class="card-body">
        <h3 class="h5 text-black"><?= Html::encode($title) ?></h3>
        <?php if (!$total || !$items): ?>
            <p class="text-muted mb-0"><?= Yii::t('app', 'Sem dados neste período.') ?></p>
        <?php else: ?>
            <table class="table table-sm statistics-distribution mb-0">
                <caption class="sr-only"><?= Html::encode($title) ?></caption>
                <thead class="sr-only"><tr><th scope="col"><?= Yii::t('app', 'Categoria') ?></th><th scope="col"><?= Yii::t('app', 'Visitas e percentagem do período') ?></th></tr></thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <th scope="row"><?= Html::encode($item['name']) ?></th>
                        <td>
                            <span class="statistics-count"><?= $item['count'] ?> · <?= Html::encode(Yii::$app->formatter->asPercent($item['count'] / $total, 1)) ?></span>
                            <meter min="0" max="<?= $total ?>" value="<?= $item['count'] ?>" aria-label="<?= Html::encode($item['name']) ?>"><?= $item['count'] ?> / <?= $total ?></meter>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
