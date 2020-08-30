<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class SetPasswordForm
 * @package app\models
 */
class SetPasswordForm extends Model
{

    public $password;
    private $user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [['password', 'required'], ['password', 'string', 'min' => 6]];
    }

    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Nova Password')
        ];
    }

    public function setUser($user)
    {
        $this->user = $user;
    }


    public function resetPassword()
    {
        $this->user->setPassword($this->password);
        $this->user->status = 1;
        $this->user->removePasswordResetToken();

        return $this->user->save(false);
    }
}
