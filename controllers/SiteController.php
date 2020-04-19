<?php

namespace app\controllers;

use Yii;
use yii\web\{
    Controller
};
use yii\filters\{
    VerbFilter,
    AccessControl
};
use app\models\{
    User,
    Link,
    LinkSearch,
    LoginForm,
    SignupForm,
    AccountForm,
    PasswordReset,
    SetPasswordForm,
    PasswordResetByEmail,
    PasswordResetByUsername
};

class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'short', 'about', 'login', 'signup', 'reset-password', 'set-password', 'verify-account', 'error', 'terms'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'short', 'about', 'signup', 'login', 'logout', 'links', 'error', 'account', 'delete-account', 'terms'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get', 'post'],
                    'register' => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * About
     *
     * @return yii\web\View
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * User account
     * 
     * @return yii\web\View
     */
    public function actionAccount()
    {
        $password = new SetPasswordForm();
        $password->setUser(Yii::$app->user->identity);
        $account = new AccountForm();
        $account->setUser(Yii::$app->user->identity);
        $account->attributes = Yii::$app->user->identity->attributes;

        $post = Yii::$app->request->post();

        if (isset($post['AccountForm'])) {
            $account->attributes = $post['AccountForm'];
            $account->save();
            Yii::$app->getSession()->setFlash(
                    'success', Yii::t('app', 'Dados pessoais atualizados com sucesso.')
            );
            return $this->redirect(['account']);
        }
        if (isset($post['SetPasswordForm'])) {
            $password->attributes = $post['SetPasswordForm'];
            $password->resetPassword();
            Yii::$app->getSession()->setFlash(
                    'success', Yii::t('app', 'Password alterada com sucesso.')
            );
            return $this->redirect(['account']);
        }

        return $this->render('account', [
                    'account' => $account,
                    'password' => $password
        ]);
    }

    /**
     * Delete user account
     * 
     * @return redirect
     */
    public function actionAccountDelete()
    {
        Yii::$app->user->identity->delete();
        Yii::$app->getSession()
                ->setFlash('success', Yii::t('app', 'Conta apagada com sucesso.'));
        return $this->actionLogout();
    }

    /**
     * Site index
     * 
     * @return yii\web\View
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Get user's links
     * 
     * @return yii\web\View
     */
    public function actionLinks()
    {
        $searchModel = new LinkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 10;

        return $this->render('links', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Login action.
     *
     * @return yii\web\View
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    /**
     * Logout
     * 
     * @return redirect
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Reset Password
     * 
     * @return yii\web\View
     */
    public function actionResetPassword()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new PasswordReset();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (filter_var($model->emailusername, FILTER_VALIDATE_EMAIL)) {
                $byEmail = new PasswordResetByEmail();
                $byEmail->email = $model->emailusername;

                if ($byEmail->validate() && $byEmail->sendEmail()) {
                    Yii::$app->session
                            ->setFlash('success', Yii::t('app', 'Verifique o seu email para concluir o processo.'));

                    return $this->redirect(['login']);
                }
                $model->addError('username', $byEmail->errors['email'][0]);
            } else {
                $byUsername = new PasswordResetByUsername();
                $byUsername->username = $model->emailusername;

                if ($byUsername->validate() && $byUsername->sendEmail()) {
                    Yii::$app->session
                            ->setFlash('success', Yii::t('app', 'Verifique o seu email para concluir o processo.'));

                    return $this->redirect(['login']);
                }
                $model->addError('username', $byUsername->errors['username'][0]);
            }
        }

        return $this->render('reset-password', [
                    'model' => $model,
        ]);
    }

    /**
     * Set Password
     * 
     * @param string $token reset token
     * @return yii\web\View
     */
    public function actionSetPassword($token = false)
    {
        $model = new SetPasswordForm();

        if (empty($token) || !is_string($token)) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Pedido inválido/expirado. Faça novo pedido.'));
            return $this->redirect(['login']);
        }

        if (!$user = User::findByPasswordResetToken($token)) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Pedido inválido/expirado. Faça novo pedido.'));
            return $this->redirect(['login']);
        }

        $model->setUser($user);

        if (
                $model->load(Yii::$app->request->post()) &&
                $model->validate() &&
                $model->resetPassword()
        ) {
            Yii::$app->session->setFlash(
                    'success', Yii::t('app', 'Password alterada com sucesso')
            );

            return $this->redirect(['login']);
        }

        return $this->render('set-password', [
                    'model' => $model,
        ]);
    }

    /**
     * Redirect user to target link
     * 
     * @param string $id the short link code
     * @return redirect
     */
    public function actionShort($id)
    {
        $link = Link::find()->where(['short' => $id])->one();
        $link->visit_count++;
        $link->save();

        return $this->redirect($link->target);
    }

    /**
     * Signup
     * 
     * @return yii\web\View
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->signup()) {

                Yii::$app->getSession()
                        ->setFlash('success', Yii::t('app', 'Verifique o seu email para concluir o processo.'));

                return $this->goHome();
            }
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    /**
     * Site terms of use
     * 
     * @return yii\web\View
     */
    public function actionTerms()
    {
        return $this->render('terms');
    }

    /**
     * Verify Account
     * 
     * @param string $key user auth key
     * @return yii\web\View
     */
    public function actionVerifyAccount($key)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (!$user = User::find()->where(['auth_key' => $key])->one()) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Conta não encontrada.'));
            return $this->goHome();
        }

        $user->status = 1;
        $user->save();

        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Conta validada com sucesso.'));
        return $this->redirect(['login']);
    }

}
