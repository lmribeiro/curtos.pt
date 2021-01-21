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
        $shorter = new Shorter();

        if (strpos($post['target'], 'https://') === false) {
            $this->sendError(400, 'Please provide a secure target with https');
        }

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

        if (!$link = Link::findOne(['short' => $post['code']])) {
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
        $code = Yii::$app->request->get('code', null);

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

}
