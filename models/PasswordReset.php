<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class PasswordReset
 * @package app\models
 */
class PasswordReset extends Model
{
    public $emailusername;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [['emailusername', 'trim'], ['emailusername', 'required']];
    }

    public function attributeLabels()
    {
        return [
            'emailusername' => Yii::t('app', 'Username ou Email')
        ];
    }
}
