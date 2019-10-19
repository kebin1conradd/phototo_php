<?php
require_once dirname(__FILE__) . '/../library/TemplateRenderer.class.php';
require_once dirname(__FILE__) . '/../model/User/Create.class.php';
require_once dirname(__FILE__) . '/../model/Login/Auth.class.php';
/**
 *　ユーザー認証メール確認
 */

class UserMailConfirmation  {
	private $has_error = false;
	public function action(){
		$this->template = TemplateRenderer::getInstance();
		$model_login_auth = new ModelLoginAuth();
		$model_user_create = new ModelUserCreate();
		$activation_code = QueryReplace::request('activation_code',      QueryReplace::STR);
		$reserved_user_id = QueryReplace::request('reserved_user_id',      QueryReplace::INT);

		if($model_user_create->isActivate($activation_code,$reserved_user_id)){
			$user_id = $model_user_create->createUserFromPreId($reserved_user_id);
			if($user_id != '') {
				$model_login_auth->loginFirst($user_id);
			}
		}
		else {
			$this->template->setValue('is_invalid', true);
			$this->has_error = true;
		}



	}
	public function output(){
		if ($this->has_error) {
			return $this->template->render('UserMailConfirmation_error.tpl');
		}else {
			//ページ遷移想定
			return $this->template->render('UserMailConfirmation.tpl');
		}
	}
}