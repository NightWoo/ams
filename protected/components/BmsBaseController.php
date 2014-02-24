<?php
/**
 * class BmsBaseController.
 *
 * 应用中所有controller的基类.
 * 用于提供所有Controller的公共行为，如登录判断，公用模块加载等
 *
 * 统一的错误处理
 * <code>
 *  $this->addError('attribute', 'error message');
 * </code>
 * 使用如上的语句，记录一个错误，当最后render时，如果给定的data中没有'errors'这个变量，
 * 则会自动增加这里的error
 *
 */
class BmsBaseController extends Controller
{
    public $errors = array();

    public function init() {
        parent::init();
    }

    /**
     * 检查QueryString中必须要包含一些项
     *
     * @param mixed $param $_GET中必须要有的参数
     * @throws CHttpException 如果有的项没有出现，抛出异常
     */
    public function requireGet($param) {
        if (is_string($param)) {
            $param = explode(',', $param);
        }
        foreach ($param as $p) {
            $p = trim($p);
            if (!isset($_GET[$p])) {
                throw new CHttpException(404, "Param '".$p."' needed in GET");
            }
        }
    }

    /**
     * 检查_POST中必须要包含一些项
     *
     * @param mixed $param $_POST中必须要有的参数
     * @throws CHttpException 如果有的项没有出现，抛出异常
     */
    public function requirePost($param) {
        if (is_string($param)) {
            $param = explode(',', $param);
        }
        foreach ($param as $p) {
            $p = trim($p);
            if (!isset($_POST[$p])) {
                throw new CHttpException(404, "Param '".$p."' needed in POST");
            }
        }
    }

    /**
     * 检查_REQUEST中必须要包含一些项
     *
     * @param mixed $param $_REQUEST中必须要有的参数
     * @throws CHttpException 如果有的项没有出现，抛出异常
     */
    public function requireRequest($param) {
        if (is_string($param)) {
            $param = explode(',', $param);
        }
        foreach ($param as $p) {
            $p = trim($p);
            if (!isset($_REQUEST[$p])) {
                throw new CHttpException(404, "Param '".$p."' needed in REQUEST");
            }
        }
    }

    /**
     * 增加一个错误
     *
     * @param string $field 错误的字段
     * @param string $message 这个字段的错误描述
     */
    public function addError($field, $message) {
        $this->errors[$field][] = $message;
    }

    /**
     * 权限认证
     *
     * 具体的controller不需要调用或重写本函数，只需要重写bmsAccessRule即可。
     */
    public function filterBmsAccessControl($filterChain) {
        $userId = Yii::app()->user->id;
        $actionId = $filterChain->action->id;

        $action2Function = $this->bmsAccessRules();
        if (isset($action2Function[$actionId])) {
            $function = $action2Function[$actionId];
            throw new CHttpException(403, $msg);
        }
        $filterChain->run();
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'bmsAccessControl',
        );
    }

    /**
     * 将action映射到系统功能（function）。框架将根据此进行权限验证。
     *
     * 本函数返回一个array，key是action的名字，value是对应的系统功能。
     *
     * 未出现在本映射中的将不做权限检查。
     * <pre>
     * array(
     *    'view'=>'MONITOR_ITEM_VIEW',
     *    'add' => 'MONITOR_ITEM_ADD',
     * );
     * </pre>
     *
     * @return array
     */
    public function bmsAccessRules() {
        return array();
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated users to perform any action
                'users'=>array('@'), // todo, @
            ),
            array('deny',  // deny all users
               'users'=>array('*'),
           ),
        );
    }

    /**
     * 渲染一个smarty模板
     *
     * 使用smarty替换Yii本身自带的view层
     *
     * @param string $view 模板文件名
     * @param array $data 模板变量
     * @param boolean $return 是否显示结果，或者返回结果字符串
     * @return string 渲染结果. 如果不需要则返回null
     */
    public function renderSmarty($view, $data=null, $return=false) {
        $smarty = Yii::app()->smarty;
        if (is_array($data) && !isset($data['errors'])) {
            $data['errors'] = $this->errors;
        }

        $smarty->assign($data);
        if ($return) {
            return $smarty->fetch($view);
        }
        else {
            $smarty->display($view);
            return null;
        }
    }

    /**
     * @param mixed 要被返回的json数据
     */
    public function renderJson($data, $contentType='application/json') {
        if (!is_null($contentType)) {
            //header('Content-type: '.$contentType);
        }
        print(CJSON::encode($data));
    }

    /**
     * 按照noah统一的返回格式来返回Json
     * @param bool 是否成功执行，用来控制是否显示出错信息
     * @param string 执行信息，系统的出错信息
     * @param array 返回的数据
     */
    public function renderJsonBms($success, $message, $data='') {
        $this->renderJson(array(
            'success' => $success,
            'message' => $message,
            'data' => $data,
            ));
    }

    public function renderDebug($view, $data=null, $return=false) {
        print("template file: <b style=\"color:red\">$view</b><br/>");
        print("<table width=100% border=1>");
        if (is_array($data)) {
            foreach ($data as $k=>$v) {
                print("<tr><td>$k</td><td><pre>");
                print("</pre></td></tr>");
            }
        }
        print("</table>");
    }
}

