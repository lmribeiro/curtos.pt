<?php

namespace app\modules\api\controllers;

use app\components\Shorter;
use app\models\Link;
use Yii;
use yii\db\StaleObjectException;
use yii\helpers\Url;

class V1Controller extends ApiController
{
    protected function verbs()
    {
        return [
            'create' => ['POST', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
            'stats' => ['GET', 'OPTIONS'],
        ];
    }

    /**
     * Creates a short link for the given target
     */
    public function actionCreate()
    {
        $post = $this->getRequiredFields(['target']);
        if (!is_string($post['target']) || mb_strlen($post['target'], 'UTF-8') > 255
            || filter_var($post['target'], FILTER_VALIDATE_URL) === false
            || strtolower((string) parse_url($post['target'], PHP_URL_SCHEME)) !== 'https') {
            $this->sendError(400, 'Please provide a valid HTTPS target of at most 255 characters');
        }

        if (array_key_exists('expires_after', $post)) {
            if (!is_int($post['expires_after']) || $post['expires_after'] <= 0) {
                $this->sendError(400, 'expires_after must be a positive integer number of days');
            }
            $expiresAt = @strtotime('+' . $post['expires_after'] . ' days');
            // The current MySQL TIMESTAMP column ends at 2038-01-19 03:14:07 UTC.
            if ($expiresAt === false || $expiresAt <= time() || $expiresAt > 2147483647) {
                $this->sendError(400, 'Expiration date exceeds the supported timestamp range');
            }
        }

        $shorter = new Shorter();
        $link = $shorter->getShortLink($post['target'], $this->user, $post['expires_after'] ?? false);

        $data = [
            'code' => $link->short,
            'target' => $link->target,
            'short' => Url::base(true) . "/" . $link->short,
            'expires_after' => date('Y-m-d H:i:s', strtotime($link->expires_after)),
        ];

        $this->sendOk(201, 'Short link created with success', $data);
    }

    /**
     * Delete a link with a given code
     */
    public function actionDelete()
    {
        $post = $this->getRequiredFields(['code']);
        $code = $this->validateCode($post['code']);

        if (!$link = Link::findOne(['short' => $code])) {
            $this->sendError(404, 'Link not found');
        }

        try {
            $link->delete();
        } catch (StaleObjectException $e) {
        } catch (\Throwable $e) {
            $this->sendError(400, 'Unknown error deleting link');
        }

        $this->sendOk(200, 'Short link deleted with success');
    }

    public function actionStats()
    {
        $code = $this->validateCode(Yii::$app->request->get('code', null));

        if (!$link = Link::findOne(['short' => $code])) {
            $this->sendError(404, 'Link not found');
        }

        $data = [
            'code' => $link->short,
            'target' => $link->target,
            'short' => Url::base(true) . "/" . $link->short,
            'expires_after' => date('Y-m-d H:i:s', strtotime($link->expires_after)),
            'visits' => $link->visit_count,
            'byBrowser' => $link->getDataByBrowser(),
            'byCountry' => $link->getDataByCountry()
        ];

        $this->sendOk(200, 'Data retrieved with success', $data);
    }

    private function validateCode($code)
    {
        if (!is_string($code) || !preg_match('/^\w{1,255}$/D', $code)) {
            $this->sendError(400, 'code must contain 1 to 255 letters, digits or underscores');
        }
        return $code;
    }

}
