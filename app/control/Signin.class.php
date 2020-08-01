<?php
require_once dirname(__FILE__) . '/../config/Config.class.php';
require_once dirname(__FILE__) . '/../library/TemplateRenderer.class.php';
require_once dirname(__FILE__) . '/../library/Session.class.php';
require_once dirname(__FILE__) . '/../library/CsrfToken.class.php';
require_once dirname(__FILE__) . '/../model/Login/Auth.class.php';
/**
 * ログイン
 */
class Signin{
	private $template;
	private $login;

	public function action(){

		$this->template = TemplateRenderer::getInstance();

		$mail_address = QueryReplace::post('mail_address',   QueryReplace::STR);
		$password    = QueryReplace::post('password',      QueryReplace::STR);

		if(!$this->validateToken()){
			$this->template->setValue('token', CsrfTokenGenerate::generate());
			return false;
		}

		$model_login_auth = new ModelLoginAuth();
		if(!$model_login_auth->login($mail_address,$password)){
			$this->template->setValue('err_message', $model_login_auth->getMessage());
		}

		$this->template->setValue('login', $this->login);

	}



	/*
	 * Token validate
	 */
	protected function validateToken(){
        $token   = QueryReplace::post('token',QueryReplace::THROUGH);
		$csrf_token_validate = new CsrfTokenValidate();
		return $csrf_token_validate->isAvailableToken($token);
	}

	public function output(){
		$session   = Session::getInstance();

		// config
		$config = Config::getInstance();
		if($session->get('user_id') == ''){
			return $this->template->render('Signin.tpl');
		}
		else{
			header("Location: https://" . $config->getDomain() . "/");
			exit;
		}
	}
}