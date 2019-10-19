<?php
require_once dirname(__FILE__) . '/../config/Config.class.php';
require_once dirname(__FILE__) . '/../library/TemplateRenderer.class.php';
require_once dirname(__FILE__) . '/../model/Login/Auth.class.php';

/**
 * ログアウト
 */

class Signout{
	private $template;
	private $has_error = false;

	public function action(){
		$this->template = TemplateRenderer::getInstance();
		$model_login_state= new ModelLoginState();
		$user_id = $model_login_state->getLoginUserId();

		if(!$model_login_state->isLogin()){
			$this->has_error = true;
		}
		else{
			$model_login_auth = new ModelLoginAuth();
			$model_login_auth->logout($user_id);
		}


	}

	public function output(){
		// config
		$config = Config::getInstance();
		if ($this->has_error) {
			return $this->template->render('Signut_error.tpl');
		}
		else{
			header("Location: http://" . $config->getDomain() . "/");
			exit;
		}
	}
}