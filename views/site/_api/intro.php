<h2 class="bd-title" id="intro"><?= Yii::t('app', 'Visão Geral') ?></h2>
<p class="bd-lead">
    <?= Yii::t('app', 'A API Curtos.pt permite chamar métodos pelo protocolo HTTP e envia a respostas no estilo REST JSON.') ?>                            
</p>

<h3 class="bd-title mt-2" id="auth"><?= Yii::t('app', 'Autenticação') ?></h3>
<p class="bd-lead">
    <?= Yii::t('app', 'Para autenticação é necessário enviar a chave da API no Header Authorization de cada pedido.') ?>
    <?= Yii::t('app', 'A chave da API pode ser configurada na tua conta depois de te registares.') ?>
</p>
<p class="bd-lead">
    <?= Yii::t('app', 'Para alguns endpoints a autenticação não é necessária.') ?>
</p>

<h3 class="bd-title mt-2" id="encode"><?= Yii::t('app', 'Encoding') ?></h3>
<p class="bd-lead">
    <?= Yii::t('app', 'Usa a codificação UTF-8 em todos os pedidos à API.') ?>
</p>

<h3 class="bd-title mt-2" id="version"><?= Yii::t('app', 'Versão') ?></h3>
<p class="bd-lead">    
    <b>1.0.0 (Draft)</b><br/>
    <?= Yii::t('app', 'Data criação') ?>: 2020/05/03<br/>
    <?= Yii::t('app', 'Última atualização') ?>: 2020/05/04<br/>
    <?= Yii::t('app', 'Root URL') ?>: <a href="https://curtos.pt/" rel="noreferrer">https://curtos.pt/</a><br/>
</p>
