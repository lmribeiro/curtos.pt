<?php

namespace app\modules\api\controllers;

use Yii;
use app\models\User;
use yii\rest\Controller;
use yii\filters\Cors;
use yii\web\Response;

class ApiController extends Controller
{

    public $user = false;
    public $limit = 20;

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {

            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: X-XMLHttpRequest, Content-Type, Authorization, AuthToken");

            if (Yii::$app->request->isOptions) {
                $this->sendOk('ok', null);
            }

            if ($auth_key = Yii::$app->request->getHeaders()->get('authorization')) {

                if ($this->user = User::find()->where(['auth_key' => $auth_key, 'status' => User::STATUS_ACTIVE])->one()) {
                    return true;
                }
            }

            return true;
        }

        return false;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
        ];

        return $behaviors;
    }

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
     *
     * @param int $count Elemets number
     * @param int $page  Page number
     *
     * @return array Pagination info
     */
    public function pagination($count, $page)
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
     *
     * @param type $msg
     * @param type $obj
     */
    public function sendOk($code, $msg, $obj = [])
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

    public function sendError($code, $msg, $obj = [])
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
     */
    public function sendResponse($status, $body = '')
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
                    $message = 'The requested URL '.$_SERVER['REQUEST_URI'].' was not found.';
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
                    $_SERVER['SERVER_SOFTWARE'].' Server at '.$_SERVER['SERVER_NAME'].' Port '.$_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templatized in a real-world solution
            $body = '<h1>'.$this->getStatusCodeMessage($status).'</h1><p>'.$message.'</p><hr /><address>'.$signature.'</address>';
        }

        $response->content = $body;
        Yii::$app->end();
    }

    /**
     * Gets the message for a status code
     *
     * @param mixed $status
     * @access private
     * @return string
     */
    private function getStatusCodeMessage($status)
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
        $post = file_get_contents("php://input");
        $params = json_decode($post, true);
        return $params;
    }

}
