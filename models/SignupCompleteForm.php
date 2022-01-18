<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Class SignupForm
 * @package app\models
 */
class SignupCompleteForm extends Model
{

    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => Yii::t('app', 'Username indisponível.')],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => Yii::t('app', 'Email indisponível.')],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
        ];
    }

    /**
     * Signs user up.
     * @return bool
     * @throws \yii\base\Exception
     */
    public function signup(): bool
    {
        if ($this->validate()) {
            $user = new User();
            $user->loadDefaultValues();

            $user->attributes = $this->attributes;
            $user->setPassword($this->password);
            $user->generatePasswordResetToken();
            $user->generateAuthKey();
            $user->status = 1;
            $user->save();

            return $this->sendEmail($user);
        }

        return false;
    }

    public function sendEmail($user): bool
    {
        return \Yii::$app->mailer
            ->compose('FollowUp', ['user' => $user])
            ->setTo($user->email)
            ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name])
            ->setSubject(Yii::$app->name . " | " . Yii::t('app', 'Conta criada com sucesso.'))
            ->send();
    }

}
