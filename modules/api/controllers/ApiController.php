<?php

namespace app\modules\api\controllers;

use Yii;
use app\models\User;
use Closure;
use yii\rest\Controller;
use yii\filters\Cors;
use yii\web\Response;

class ApiController extends Controller
{

    public $user = false;
    public $limit = 20;
    protected $isAuthenticated = false;

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
                    $this->isAuthenticated = true;
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

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

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
     * @param type $status
     * @param type $msg
     * @param type $data
     */
    public function sendShortResponse($status, $msg, $data = null)
    {
        $returnArray["status"] = $status;
        $returnArray["message"] = $msg;
        $returnArray["data"] = $data;
        self::sendResponse(200, json_encode($returnArray));
    }

    /**
     * Sends API success responses
     *
     * @param type $msg
     * @param type $obj
     */
    public function sendOk($msg, $obj)
    {
        $returnArray = array();
        $returnArray["code"] = 200;
        $returnArray["status"] = $this->getStatusCodeMessage(200);
        $returnArray["message"] = $msg;
        $returnArray["data"] = $obj;

        self::sendResponse(200, json_encode($returnArray));
    }

    public function sendError($code, $msg, $obj = null)
    {
        $returnArray = array();
        $returnArray["code"] = $code;
        $returnArray["status"] = $this->getStatusCodeMessage($code);
        $returnArray["message"] = $msg;
        $returnArray["data"] = $obj;

        self::sendResponse(200, json_encode($returnArray));
    }

    /**
     * Sends final response
     */
    public function sendResponse($status, $body = '')
    {
        Yii::debug(json_encode($body));

        $response = Yii::$app->response;
        $response->setStatusCode($status);
        $response->format = Response::FORMAT_JSON;
        $response->headers->set('X-Powered-By', '360 CITY <www.360city.pt>');


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
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            600 => 'Insufficient funds',
            700 => 'Invalid Request',
            701 => 'Bracelet Not Found',
            702 => 'Merchant Not Found',
            703 => 'Invalid Balance',
            704 => 'Local Bracelet',
            705 => 'Error',
            706 => 'Invalid Domain UUID',
            707 => 'Store Not Found'
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

    /**
     * Check Permission for access
     */
    public function checkAccess()
    {
        if (!$this->isAuthenticated) {
            $this->sendShortResponse('UNAUTHORIZED', Yii::t("app", 'Acesso negado'), null);
        }
    }

}
