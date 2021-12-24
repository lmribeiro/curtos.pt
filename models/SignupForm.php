<?php

namespace app\models;

use app\components\VerifyEmail;
use app\components\VerifyEmailDomain;
use Yii;
use yii\base\Model;

/**
 * Class SignupForm
 * @package app\models
 */
class SignupForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => Yii::t('app', 'Email indisponível.')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
        ];
    }

    /**
     * Signs user up
     * @return bool
     */
    public function signup(): bool
    {
        if (strpos($this->email, '@gmail')) {
            $verifyEmail = new VerifyEmail();
        } else {
            $verifyEmail = new VerifyEmailDomain();
        }

        if (!$verifyEmail->check($this->email)) {
            $this->addError('email', Yii::t('app', 'Email inválido.'));
            return false;
        }

        if(User::findByEmail($this->email)) {
            $this->addError('email', Yii::t('app', 'Email indisponível.'));
            return false;
        }
        return $this->sendEmail();
    }

    public function sendEmail(): bool
    {
        return \Yii::$app->mailer
            ->compose('CreateAccount', ['email' => $this->email])
            ->setTo($this->email)
            ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name])
            ->setSubject(Yii::$app->name . " | " . Yii::t('app', 'Criar conta'))
            ->send();
    }

}
