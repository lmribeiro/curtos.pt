<h2 class="bd-title" id="intro"><?= Yii::t('app', 'Visão geral') ?></h2>
<p class="bd-lead"><?= Yii::t('app', 'A API Curtos.pt permite criar, consultar estatísticas e apagar links curtos através de pedidos HTTP com respostas JSON.') ?></p>

<h3 class="bd-title mt-2" id="auth"><?= Yii::t('app', 'Autenticação') ?></h3>
<p><?= Yii::t('app', 'Os pedidos aos endpoints exigem a chave de uma conta ativa no header Authorization. Envia a chave diretamente, sem o prefixo Bearer. Podes consultar ou regenerar a chave na página da tua conta; ao regenerá-la, a chave anterior deixa de funcionar.') ?></p>
<figure class="highlight"><pre><code>Authorization: A_TUA_CHAVE_API
Content-Type: application/json</code></pre></figure>
<p><?= Yii::t('app', 'O registo público encontra-se desativado nesta versão: a página de registo redireciona para o início. Para integrar, precisas de uma conta ativa já existente; numa instalação própria, o operador tem de disponibilizar as contas.') ?></p>
<p><?= Yii::t('app', 'Guarda a chave no servidor. Não a publiques em código de frontend, repositórios ou exemplos partilhados.') ?></p>

<h3 class="bd-title mt-2" id="encode"><?= Yii::t('app', 'Formato dos pedidos') ?></h3>
<p><?= Yii::t('app', 'Usa UTF-8 e JSON nos corpos dos pedidos POST e DELETE. Na consulta de estatísticas, o código do link é enviado na query string. Um corpo JSON e uma query string não são intercambiáveis.') ?></p>

<h3 class="bd-title mt-2" id="version"><?= Yii::t('app', 'Versão e endereço base') ?></h3>
<p><b>API v1</b><br>
<?= Yii::t('app', 'Endereço base') ?>: <a href="https://curtos.pt/" rel="noreferrer">https://curtos.pt</a><br>
<?= Yii::t('app', 'Documentação revista') ?>: 2026-11-13</p>

<h3 class="bd-title mt-2" id="examples"><?= Yii::t('app', 'Preparar os exemplos cURL') ?></h3>
<p><?= Yii::t('app', 'Define o endereço e a tua chave antes de executar os exemplos seguintes. Para desenvolvimento, substitui o endereço pelo do teu servidor local, por exemplo http://127.0.0.1:8181. Usa links descartáveis para testar criação e eliminação.') ?></p>
<figure class="highlight"><pre><code>API_BASE='https://curtos.pt'
API_KEY='A_TUA_CHAVE_API'</code></pre></figure>
<p><?= Yii::t('app', 'Os exemplos mostram também os headers HTTP. Não uses apenas o estado HTTP para decidir se um pedido teve sucesso: consulta os campos status e code do JSON, conforme explicado nos códigos de resposta.') ?></p>

<h3 class="bd-title mt-2" id="validation"><?= Yii::t('app', 'Validação e CORS') ?></h3>
<p><?= Yii::t('app', 'Depois da autenticação, JSON malformado, corpos que não sejam objetos JSON, campos obrigatórios em falta e valores inválidos devolvem status Error e code 400 no envelope habitual, mantendo HTTP 200. As mensagens indicam o campo ou o formato a corrigir.') ?></p>
<p><?= Yii::t('app', 'O preflight OPTIONS é tratado antes da autenticação e não exige chave nem corpo JSON. A resposta usa HTTP 200 e corpo vazio. Os pedidos reais continuam a exigir Authorization. É permitida qualquer origem, sem credenciais de cookies entre origens.') ?></p>
<p><?= Yii::t('app', 'Os métodos anunciados são POST/OPTIONS na criação, DELETE/OPTIONS na eliminação e GET/OPTIONS nas estatísticas. Os headers permitidos são Authorization, Content-Type, X-Requested-With, X-XMLHttpRequest e AuthToken. Métodos e headers não permitidos não são anunciados pelo preflight; confirma também a configuração do proxy ou servidor web onde a aplicação está alojada.') ?></p>
<figure class="highlight"><pre><code>curl --include --request OPTIONS "$API_BASE/api/v1/delete" \
  --header 'Origin: https://client.example' \
  --header 'Access-Control-Request-Method: DELETE' \
  --header 'Access-Control-Request-Headers: authorization, content-type'</code></pre></figure>

<h3 class="bd-title mt-2" id="limits"><?= Yii::t('app', 'Limitações da versão atual') ?></h3>
<ul>
    <li><?= Yii::t('app', 'Não existe um contrato de quota de pedidos ou Retry-After implementado no controlador v1.') ?></li>
    <li><?= Yii::t('app', 'A criação não tem chave de idempotência. Repetir um pedido pode criar outro link; confirma o resultado antes de repetir após uma falha de ligação.') ?></li>
    <li><?= Yii::t('app', 'A data de expiração é guardada e apresentada, mas o redirecionamento atual não a impõe. Não existe uma tarefa de remoção automática de links expirados neste repositório.') ?></li>
</ul>
