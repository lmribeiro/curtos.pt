<?php

namespace tests\unit\views;

use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\DynamicModel;
use yii\web\Application;
use yii\web\View;

class HomePageTest extends TestCase
{
    protected function setUp(): void
    {
        new Application([
            'id' => 'home-page-test',
            'name' => 'Curtos.pt',
            'basePath' => dirname(__DIR__, 3),
            'components' => [
                'user' => new DynamicModel(['isGuest' => true]),
                'request' => [
                    'cookieValidationKey' => 'test-only',
                    'scriptFile' => dirname(__DIR__, 3) . '/web/index.php',
                    'scriptUrl' => '/index.php',
                    'baseUrl' => '',
                ],
                'urlManager' => ['enablePrettyUrl' => true, 'showScriptName' => false],
            ],
        ]);
    }

    protected function tearDown(): void
    {
        Yii::$app = null;
        parent::tearDown();
    }

    public function testGuestHomeExplainsTheServiceWithoutOfferingUnavailableActions()
    {
        $view = new View();
        $html = $view->renderFile(Yii::$app->basePath . '/views/site/index.php');
        $dom = new \DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new \DOMXPath($dom);
        $this->assertSame(1, $xpath->query('//h1')->length);
        $this->assertSame(0, $xpath->query('//input')->length);
        $this->assertSame(0, $xpath->query('//form')->length);
        $this->assertSame(0, $xpath->query('//a[contains(@href,"signup")]')->length);
        $this->assertSame(1, $xpath->query('//a[@href="/login"]')->length);
        $this->assertSame(1, $xpath->query('//a[@href="/api-v1"]')->length);
        $this->assertSame(1, $xpath->query('//a[@href="/cli"]')->length);
        $this->assertStringContainsString('registo público não está disponível', $html);
        $this->assertStringContainsString('exemplo ilustrativo', $html);
        $this->assertStringNotContainsString('curtos.gif', $html);
        $this->assertArrayHasKey('/css/guest-home.css', $view->assetBundles);
        $request = $xpath->query('//div[contains(@class,"guest-request")]/pre/code')->item(0)->textContent;
        $this->assertSame(['target' => 'https://example.com/', 'expires_after' => 30], json_decode($request, true));
    }

    public function testLoggedInShortenerControlsArePreserved()
    {
        Yii::$app->user->isGuest = false;
        $view = new View();
        $html = $view->renderFile(Yii::$app->basePath . '/views/site/index.php');
        $this->assertStringContainsString('id="target"', $html);
        $this->assertStringContainsString('id="copy"', $html);
        $this->assertStringContainsString('app.short()', $html);
        $this->assertStringContainsString('app.copy()', $html);
        $this->assertStringNotContainsString('guest-home', $html);
        $this->assertEmpty($view->cssFiles);
        $this->assertEmpty($view->assetBundles);
    }
}
