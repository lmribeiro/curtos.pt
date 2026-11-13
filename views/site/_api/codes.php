<h2 class="bd-title mt-5" id="codes"><?= Yii::t('app', 'Códigos de resposta e erros') ?></h2>
<p><?= Yii::t('app', 'Os helpers de resposta da API enviam atualmente HTTP 200, mesmo quando o campo code do JSON indica criação ou erro. Verifica status e code no corpo, além do estado HTTP da ligação.') ?></p>
<div class="table-responsive">
<table class="table table-striped">
    <thead><tr><th scope="col">code</th><th scope="col">status</th><th scope="col"><?= Yii::t('app', 'Significado') ?></th></tr></thead>
    <tbody>
        <tr><td>200</td><td>OK</td><td><?= Yii::t('app', 'Consulta ou eliminação concluída.') ?></td></tr>
        <tr><td>201</td><td>OK</td><td><?= Yii::t('app', 'Link criado. O estado HTTP continua a ser 200.') ?></td></tr>
        <tr><td>400</td><td>Error</td><td><?= Yii::t('app', 'JSON inválido, campo obrigatório em falta, tipo ou valor inválido, ou falha tratada na eliminação.') ?></td></tr>
        <tr><td>401</td><td>Error</td><td><?= Yii::t('app', 'Chave ausente, inválida ou sem conta ativa correspondente.') ?></td></tr>
        <tr><td>404</td><td>Error</td><td><?= Yii::t('app', 'Código de link não encontrado na consulta ou eliminação.') ?></td></tr>
    </tbody>
</table>
</div>
<p><?= Yii::t('app', 'Erros da framework, do servidor web ou de um proxy podem usar outros estados HTTP, como 403, 405 ou 500, e outro formato de resposta. Não pressuponhas que todas as falhas têm o envelope JSON abaixo.') ?></p>

<h3 class="bd-title mt-4"><?= Yii::t('app', 'Exemplo: autenticação em falta') ?></h3>
<figure class="highlight"><pre><code>curl --include "$API_BASE/api/v1/stats?code=a1b2c3"</code></pre></figure>
<p><?= Yii::t('app', 'Resposta do helper com HTTP 200 e code 401:') ?></p>
<figure class="highlight"><pre><code>{
    "name": "Unauthorized",
    "message": "You must be authorized to performe this request.",
    "code": 401,
    "status": "Error",
    "data": []
}</code></pre></figure>

<h3 class="bd-title mt-4"><?= Yii::t('app', 'Exemplo: campo obrigatório em falta') ?></h3>
<figure class="highlight"><pre><code>curl --include --request POST "$API_BASE/api/v1/create" \
  --header "Authorization: $API_KEY" \
  --header 'Content-Type: application/json' \
  --data '{}'</code></pre></figure>
<p><?= Yii::t('app', 'Com uma chave válida, a resposta do helper usa HTTP 200 e code 400:') ?></p>
<figure class="highlight"><pre><code>{
    "name": "Bad Request",
    "message": "Missing required field 'target'",
    "code": 400,
    "status": "Error",
    "data": []
}</code></pre></figure>
<p><?= Yii::t('app', 'O campo message serve de descrição. Nas integrações, usa status e code para decidir o resultado, em vez de comparar a mensagem de texto.') ?></p>
