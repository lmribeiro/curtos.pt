<?php

use yii\helpers\Html;

/** @var string $label */
/** @var array $items */
?>
<nav id="docs-nav" class="docs-navigation" aria-label="<?= Html::encode(Yii::t('app', $label)) ?>" data-docs-nav>
    <details open>
        <summary><?= Yii::t('app', 'Nesta página') ?></summary>
        <p class="docs-nav-title" aria-hidden="true"><?= Yii::t('app', 'Nesta página') ?></p>
        <ul class="nav flex-column docs-nav-links">
            <?php foreach ($items as $item): ?>
                <li class="nav-item">
                    <?= Html::a(Yii::t('app', $item[1]), '#' . $item[0], ['class' => 'nav-link']) ?>
                    <?php if (!empty($item[2])): ?>
                        <ul class="nav flex-column docs-nav-children">
                            <?php foreach ($item[2] as $child): ?>
                                <li class="nav-item"><?= Html::a(Yii::t('app', $child[1]), '#' . $child[0], ['class' => 'nav-link']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </details>
</nav>
