<?php

namespace app\modules\api\controllers;

use app\models\User;
use Yii;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\web\Response;

class ApiController extends Controller
{

    public $user = false;
    public $limit = 20;

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {

            if (Yii::$app->request->isOptions) {
                Yii::$app->response->setStatusCode(200);
                return false;
            }

            if ($auth_key = Yii::$app->request->getHeaders()->get('authorization')) {

                if ($this->user = User::find()->where(['auth_key' => $auth_key, 'status' => User::STATUS_ACTIVE])->one()) {
                    return true;
                }
            }

            return $this->sendError(401, 'You must be authorized to performe this request.');;
        }

        return false;
    }

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'DELETE', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['Authorization', 'Content-Type', 'X-Requested-With', 'X-XMLHttpRequest', 'AuthToken'],
                ],
                'actions' => [
                    'create' => ['Access-Control-Request-Method' => ['POST', 'OPTIONS']],
                    'delete' => ['Access-Control-Request-Method' => ['DELETE', 'OPTIONS']],
                    'stats' => ['Access-Control-Request-Method' => ['GET', 'OPTIONS']],
                ],
            ],
        ] + parent::behaviors();
    }

    /**
     * Get required fields
     * @param array $fields
     * @return array
     * @throws \yii\base\ExitException
     */
    public function getRequiredFields($fields = [])
    {
        $post = $this->getParams();

        foreach ($fields as $field) {
            if (!isset($post[$field])) {
                $this->sendError(400, "Missing required field '{$field}'");
            }
        }
        return $post;
    }

    /**
     * Get pagination.
     * @param int $count
     * @param int $page
     * @return array
     */
    public function pagination(int $count, int $page)
    {
        $pageCount = ceil($count / $this->limit);
        $nextPage = false;
        if ($pageCount > $page) {
            $nextPage = true;
        }

        $pagination['count'] = $count;
        $pagination['page'] = $page;
        $pagination['page_count'] = $pageCount;
        $pagination['next_page'] = $nextPage;

        return $pagination;
    }

    /**
     * Sends API success responses
     * @param int $code
     * @param string $msg
     * @param array $obj
     * @throws \yii\base\ExitException
     */
    public function sendOk(int $code, string $msg, $obj = [])
    {
        $returnArray = [
            'name' => $this->getStatusCodeMessage($code),
            'message' => $msg,
            'code' => $code,
            'status' => "OK",
            'data' => $obj
        ];

        self::sendResponse(200, json_encode($returnArray));
    }

    /**
     * Sends API error responses
     * @param int $code
     * @param string $msg
     * @param array $obj
     * @throws \yii\base\ExitException
     */
    public function sendError(int $code, string $msg, $obj = [])
    {
        $returnArray = [
            'name' => $this->getStatusCodeMessage($code),
            'message' => $msg,
            'code' => $code,
            'status' => "Error",
            'data' => $obj
        ];

        self::sendResponse(200, json_encode($returnArray));
    }

    /**
     * Sends final response
     * @param int $status
     * @param string $body
     * @throws \yii\base\ExitException
     */
    public function sendResponse(int $status, string $body = '')
    {
        $response = Yii::$app->response;
        $response->setStatusCode($status);
        $response->format = Response::FORMAT_JSON;
        $response->headers->set('X-Powered-By', 'Curtos.pt');

        if (empty($body)) {
            $response->format = Response::FORMAT_HTML;
            $message = '';
            switch ($status) {
                case 401:
                    $message = 'You must be authorized to performe this request.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ?
                $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templatized in a real-world solution
            $body = '<h1>' . $this->getStatusCodeMessage($status) . '</h1><p>' . $message . '</p><hr /><address>' . $signature . '</address>';
        }

        $response->content = $body;
        Yii::$app->end();
    }

    /**
     * Gets the message for a status code
     * @param int $status
     * @return string
     */
    private function getStatusCodeMessage(int $status)
    {
        $codes = array(
            200 => 'OK',
            201 => 'Created',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );

        return (isset($codes[$status])) ? $codes[$status] : '';
    }

    /**
     * Get Params
     * Returns all params send by POST/PUT request
     *
     * @return array with the request params
     */
    public function getParams()
    {
        $post = json_decode(Yii::$app->request->getRawBody());
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->sendError(400, 'Invalid JSON request body');
        }
        if (!$post instanceof \stdClass) {
            $this->sendError(400, 'Request body must be a JSON object');
        }
        return (array) $post;
    }

}
