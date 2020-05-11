<?php

namespace app\modules\api\controllers;

use yii\helpers\Url;
use app\models\Link;
use app\components\Shorter;

class V1Controller extends ApiController
{

    protected function verbs()
    {
        return [
            'create' => ['POST', 'OPTIONS'],
            'delete' => ['DELETE', 'OPTIONS'],
        ];
    }

    /**
     * Creates a short link for the given target
     *  
     */
    public function actionCreate()
    {
        $post = $this->getRequiredFields(['target']);
        $shorter = new Shorter();

        $link = $shorter->getShortLink($post['target'], $this->user, $post['expires_after'] ?? false);

        $data = [
            'id' => $link->id,
            'code' => $link->short,
            'target' => $link->target,
            'short' => Url::base(true)."/".$link->short,
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

        $link->delete();

        $this->sendOk(200, 'Short link deleted with success');
    }

}
