<?php
require_once dirname(__FILE__) . '/../config/Config.class.php';
require_once dirname(__FILE__) . '/../config/ServerDefine.class.php';
require_once dirname(__FILE__) . '/../library/Smarty/Smarty.class.php';
require_once dirname(__FILE__) . '/../model/Login/State.class.php';


/**
 * TemplateRenderer
 */
class TemplateRenderer {

	const TEMPLATE_DIR_PC   = 'pc';
	const LEFT_DELIMITER = '{{';
    const RIGHT_DELIMITER = '}}';
	private static $class_cache = null;

	final private function __construct() {
        $this->smarty = new Smarty();
        $this->smarty->force_compile =true;

        $this->smarty->left_delimiter = self::LEFT_DELIMITER;
        $this->smarty->right_delimiter = self::RIGHT_DELIMITER;
	}


	/**
	 * @return TemplateRenderer
	 */
	public static function getInstance() {
		if(!is_object(self::$class_cache)) {
			self::$class_cache = new self();
		}
		return self::$class_cache;
	}


	/**
	 * テンプレート表示処理
	 * @param string $name
	 * @param string $dir
	 */
	public function render($name, $dir = null){
		$this->setTemplateValue();
		$this->setTemplateDir($dir);
		
		return $this->smarty->fetch($name);
	}

	/**
	 * 変数セット
	 * @param string $key
	 * @param string $val
	 */
	public function setValue($key, $val) {
			$this->smarty->assign(array($key => $val));
	}


	/**
	* テンプレートディレクトリ設定
	* @param string $dir テンプレートディレクトリ
	*/
	private function setTemplateDir($dir){
		$template_dir                = $this->getTemplateDirPath($dir);
		$this->smarty->template_dir  = $template_dir;
		$this->smarty->compile_dir   = $template_dir . "compile/";
	}
	
	/**
	* テンプレートディレクトリパスを取得
	* @param string $dir
	*/
	
	private function getTemplateDirPath($dir) {

		if(empty($dir)){
				$template_dir = dirname(__FILE__) . '/../view/'.self::TEMPLATE_DIR_PC.'/html/';
		}else{
			$template_dir = dirname(__FILE__) . "/../view/{$dir}/".self::TEMPLATE_DIR_PC."/html/";
		}
		return $template_dir;
	}
	
	/**
	* template 情報取得
	*/
	private function setTemplateValue(){
        $model_login_state=  new ModelLoginState();
        $config = config::getInstance();
        $this->setValue('base_url', 'http://'.$config->getDomain().'/');

        //ログイン
        $header['is_login'] = $model_login_state->isLogin();
        $this->setValue('header', $header);
	}

	
}
