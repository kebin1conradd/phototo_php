<?php
require_once dirname(__FILE__) . '/../library/QueryReplace.class.php';
require_once dirname(__FILE__) . '/../library/Session.class.php';
require_once dirname(__FILE__) . '/../model/Login/Auth.class.php';
require_once dirname(__FILE__) . '/../model/Login/State.class.php';
require_once dirname(__FILE__) . '/../model/ModelCommon.class.php';
/**
 * phototo
 */
class phototo {
	private $feature;
	private $mode;
    private $accept_feature = array('signin','signout','upload','user');
    private $accept_mode = array('signup','mailconfirmation');
	public function __construct(){
		$this->feature      = QueryReplace::request('feature',      QueryReplace::STR);
		$this->mode      = QueryReplace::request('mode',      QueryReplace::STR);
	}

    /**
     * 出力前に再ログイン
     * @return string
     */
    private function callAutomationLogin(){

        $model_login_state=  new ModelLoginState();
        if(!$model_login_state->isLogin()){
            $model_login_auth = new ModelLoginAuth();
            $model_login_auth->loginAutomation();
        }
    }

	/**
	 * 出力
	 * void
	 */
	public function displayRender(){
		$this->callAutomationLogin();
        if(in_array($this->feature,$this->accept_feature) && (in_array($this->mode,$this->accept_mode) ||  $this->mode=='')){
            echo $this->callCtrl($this->feature, $this->mode);
        }
        else{
            echo $this->callCtrl('top');
        }
	}

	/**
	 * コントローラー呼び出し
	 * @param string $feature
	 * @param string $mode
	 * return string 出力内容
	 */
	private function callCtrl($feature,$mode=''){
		$class = $this->getClassName($feature,$mode);
		$file = $this->getClassFullPath($class);
		if($this->isClassFileExists($class)){
			require_once($file);
		}else{
			exit;
		}

		if(class_exists($class)){
			return $this->renderClass($class);
		}
        exit;
	}


	/**
	 * クラス名をfeatureとmodeから取得する。
     * @param string $feature
     * @param string $mode
	 * return string
	 */
	private function getClassName($feature,$mode){
		$class = ucwords($feature);
		if($mode != '') {
			$class .= ucwords($mode);
		}
		return $class;
	}
	/**
	 * クラスのレンダリング
	 * return string
	 */
	private function renderClass($class){
		$render_class = new $class();
        $render_class->action();
        $result = $render_class->output();
		return $result;
	}

	/**
	 * クラスファイルがあるか
	 * return boolean
	 */
	private function isClassFileExists($class){
		$file = $this->getClassFullPath($class);
		return file_exists($file);
	}
	/**
	 * クラスのパス
	 * return string
	 */
	private function getClassFullPath($class){
		return dirname(__FILE__) . '/../control/'.$class.'.class.php';
	}

}