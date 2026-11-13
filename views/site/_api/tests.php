<h2 class="bd-title mt-5" id="tests"><?= Yii::t('app', 'Testar a integração') ?></h2>
<p><?= Yii::t('app', 'Usa uma conta ativa e começa pelos exemplos cURL com o teu servidor local. O registo público está desativado nesta versão; uma instalação nova precisa de contas disponibilizadas pelo operador.') ?></p>
<ol>
    <li><?= Yii::t('app', 'Obtém a chave na página da conta e configura API_BASE e API_KEY nos exemplos.') ?></li>
    <li><?= Yii::t('app', 'Cria um link descartável e guarda o code devolvido. A criação deve ter status OK e code 201 no JSON, embora o estado HTTP seja 200.') ?></li>
    <li><?= Yii::t('app', 'Consulta as estatísticas desse código. Visitar o URL curto regista uma visita; consultar o endpoint de estatísticas ou a página de detalhes não a regista.') ?></li>
    <li><?= Yii::t('app', 'Confirma os casos de erro com chave em falta, campo obrigatório em falta e código inexistente. Verifica o JSON, não apenas o HTTP.') ?></li>
    <li><?= Yii::t('app', 'Apaga o link descartável e confirma que uma consulta posterior devolve code 404. A eliminação remove também as estatísticas associadas.') ?></li>
</ol>
<?php if (Yii::$app->user->isGuest): ?>
    <p><a class="btn btn-neutral" href="<?= \yii\helpers\Url::to(['/login']) ?>"><?= Yii::t('app', 'Entrar numa conta existente') ?></a></p>
<?php else: ?>
    <p><a href="<?= \yii\helpers\Url::to(['/account']) ?>"><?= Yii::t('app', 'Abrir a conta para consultar a chave') ?></a></p>
<?php endif; ?>
<p><?= Yii::t('app', 'Os testes automatizados da aplicação e a preparação do ambiente Codeception estão descritos no README do repositório. Os exemplos desta página não comprovam uma execução contra o serviço de produção.') ?></p>
