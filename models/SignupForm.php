<?php

namespace app\models;

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
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup(): User|bool|null
    {
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
