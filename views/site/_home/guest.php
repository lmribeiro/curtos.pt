<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
?>
<div class="guest-home">
    <div class="container">
        <header class="guest-hero text-center">
            <p class="guest-eyebrow"><?= Yii::t('app', 'Gratuito · Código aberto') ?></p>
            <h1><?= Yii::t('app', 'Links curtos.') ?><br><span><?= Yii::t('app', 'Prontos a partilhar.') ?></span></h1>
            <div class="guest-hero-content">
                <div class="guest-hero-description">
                    <p class="lead"><?= Yii::t('app', 'Transforma endereços compridos em links simples. Gere os teus links e acompanha as visitas, no site ou através da API.') ?></p>
                    <div class="guest-actions">
                        <a class="btn btn-primary" href="<?= Url::to(['/login']) ?>"><?= Yii::t('app', 'Entrar na minha conta') ?> <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i></a>
                        <a class="btn btn-neutral" href="#guest-features"><?= Yii::t('app', 'Conhecer o serviço') ?></a>
                    </div>
                    <p class="guest-caption mt-3 mb-0"><?= Yii::t('app', 'A criação e gestão de links estão disponíveis para contas ativas.') ?></p>
                </div>
                <figure class="guest-example" aria-labelledby="guest-example-caption">
                    <figcaption id="guest-example-caption" class="guest-eyebrow"><?= Yii::t('app', 'Um endereço mais simples - exemplo ilustrativo') ?></figcaption>
                    <div class="guest-example-links">
                        <div class="guest-original">
                            <span class="guest-label"><?= Yii::t('app', 'Antes') ?></span>
                            <code>https://example.com/artigos/um-endereco-comprido-para-partilhar</code>
                        </div>
                        <i class="fas fa-arrow-down guest-example-arrow" aria-hidden="true"></i>
                        <div class="guest-short">
                            <span class="guest-label"><?= Yii::t('app', 'Depois') ?></span>
                            <strong>curtos.pt/a1b2c3</strong>
                        </div>
                    </div>
                    <p class="guest-caption mb-0"><?= Yii::t('app', 'Quem segue o link curto é encaminhado para o endereço original.') ?></p>
                </figure>
            </div>
        </header>

        <section class="guest-section" aria-labelledby="guest-features">
            <div class="guest-section-heading">
                <h2 id="guest-features"><?= Yii::t('app', 'Mais do que encurtar um endereço') ?></h2>
                <p><?= Yii::t('app', 'Da criação do link à consulta das visitas, mantém tudo num só lugar.') ?></p>
            </div>
            <div class="row">
                <?php foreach ([
                    ['link', 'Partilha com simplicidade', 'Cria links curtos, copia-os e consulta os respetivos endereços de destino na tua conta.'],
                    ['chart-bar', 'Acompanha as visitas', 'Consulta a evolução das visitas e os dados disponíveis sobre navegadores, dispositivos e países.'],
                    ['code', 'Integra nas tuas ferramentas', 'Usa a API para criar, consultar estatísticas e apagar links a partir das tuas aplicações.'],
                ] as $feature): ?>
                    <div class="col-md-4 mb-4">
                        <article class="guest-feature h-100">
                            <i class="fas fa-<?= Html::encode($feature[0]) ?> guest-feature-icon" aria-hidden="true"></i>
                            <h3><?= Html::encode(Yii::t('app', $feature[1])) ?></h3>
                            <p class="mb-0"><?= Html::encode(Yii::t('app', $feature[2])) ?></p>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="guest-section" aria-labelledby="guest-how">
            <div class="guest-section-heading"><h2 id="guest-how"><?= Yii::t('app', 'Do endereço à partilha, em três passos') ?></h2></div>
            <ol class="row list-unstyled guest-steps">
                <li class="col-md-4 mb-4">
                    <span class="guest-step-number" aria-hidden="true">1</span>
                    <h3><?= Yii::t('app', 'Entra na tua conta') ?></h3>
                    <p><?= Yii::t('app', 'Usa uma conta ativa para aceder à criação e à gestão dos teus links.') ?></p>
                </li>
                <li class="col-md-4 mb-4">
                    <span class="guest-step-number" aria-hidden="true">2</span>
                    <h3><?= Yii::t('app', 'Cola o endereço') ?></h3>
                    <p><?= Yii::t('app', 'Introduz o URL HTTPS que queres partilhar e cria o teu link curto.') ?></p>
                </li>
                <li class="col-md-4 mb-4">
                    <span class="guest-step-number" aria-hidden="true">3</span>
                    <h3><?= Yii::t('app', 'Partilha e acompanha') ?></h3>
                    <p><?= Yii::t('app', 'Copia o link para onde precisares e consulta as visitas na página de detalhes.') ?></p>
                </li>
            </ol>
        </section>

        <section class="guest-integration guest-section" aria-labelledby="guest-integration-title">
            <div>
                <p class="guest-eyebrow"><?= Yii::t('app', 'Para quem integra') ?></p>
                <h2 id="guest-integration-title"><?= Yii::t('app', 'Os teus links, na tua aplicação') ?></h2>
                <p><?= Yii::t('app', 'Consulta os exemplos de pedidos HTTP e respostas JSON. A API usa a chave da tua conta para autenticação.') ?></p>
                <div class="guest-actions">
                    <a class="btn btn-primary" href="<?= Url::to(['/api-v1']) ?>"><?= Yii::t('app', 'Documentação da API') ?></a>
                    <a class="btn btn-neutral" href="<?= Url::to(['/cli']) ?>"><?= Yii::t('app', 'Conhecer a CLI') ?></a>
                </div>
            </div>
            <div class="guest-request" aria-label="<?= Yii::t('app', 'Exemplo de corpo JSON para criar um link pela API') ?>">
                <span>POST /api/v1/create</span>
                <pre><code>{
  "target": "https://example.com/",
  "expires_after": 30
}</code></pre>
            </div>
        </section>

        <section class="guest-section guest-faq" aria-labelledby="guest-questions">
            <div class="guest-section-heading"><h2 id="guest-questions"><?= Yii::t('app', 'Antes de começar') ?></h2></div>
            <details>
                <summary><?= Yii::t('app', 'Posso criar uma conta?') ?></summary>
                <p><?= Yii::t('app', 'Neste momento, o registo público não está disponível. Se já tens uma conta ativa, podes entrar e continuar a criar e gerir os teus links.') ?></p>
            </details>
            <details>
                <summary><?= Yii::t('app', 'Preciso de conta para abrir um link curto?') ?></summary>
                <p><?= Yii::t('app', 'Não. Os links curtos existentes podem ser seguidos sem iniciar sessão. A conta é necessária para criar, gerir e usar a API.') ?></p>
            </details>
            <details>
                <summary><?= Yii::t('app', 'Posso alojar a minha própria instalação?') ?></summary>
                <p><?= Yii::t('app', 'Sim. O Curtos.pt é de código aberto. O repositório inclui o código, as instruções de instalação e as limitações atuais do serviço.') ?></p>
                <a href="https://github.com/lmribeiro/curtos.pt" target="_blank" rel="noopener noreferrer"><?= Yii::t('app', 'Ver o projeto no GitHub') ?> <span aria-hidden="true">↗</span></a>
            </details>
            <p class="guest-caption mt-4"><a href="<?= Url::to(['/about']) ?>"><?= Yii::t('app', 'Saber mais sobre o Curtos.pt') ?></a> · <a href="<?= Url::to(['/terms']) ?>"><?= Yii::t('app', 'Termos de uso e privacidade') ?></a></p>
        </section>
    </div>
</div>
