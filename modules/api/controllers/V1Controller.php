<?php

namespace app\modules\api\controllers;

use yii\helpers\Url;
use app\components\Shorter;

class V1Controller extends ApiController
{

    protected function verbs()
    {
        return [
            'short' => ['POST', 'OPTIONS'],
        ];
    }

    /**
     * Creates a short link for the given target
     *  
     */
    public function actionShort()
    {
        $post = $this->getRequiredFields(['target']);
        $shorter = new Shorter();

        $link = $shorter->getShortLink($post['target'], $this->user, $post['time' ?? false]);

        $data = [
            'id' => $link->id,
            'code' => $link->short,
            'target' => $link->target,
            'short' => Url::base(true)."/".$link->short,
            'expires_after' => date('Y-m-d H:i:s', strtotime($link->expires_after)),
        ];

        $this->sendOk('Short link created with success', $data);
    }

}
