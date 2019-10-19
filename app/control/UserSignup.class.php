<?php
require_once dirname(__FILE__) . '/../library/CsrfToken.class.php';
require_once dirname(__FILE__) . '/../library/TemplateRenderer.class.php';
require_once dirname(__FILE__) . '/../model/User/Create.class.php';
require_once dirname(__FILE__) . '/../model/User/Mail.class.php';
require_once dirname(__FILE__) . '/../model/User/Validator.class.php';

/**
 * 新規登録
 */

class UserSignup {
    private $template;
	private $has_error = false;
	private $action ='';

	public function action(){
		$this->template = TemplateRenderer::getInstance();
		$this->action= QueryReplace::request('action',      QueryReplace::STR);

		if($this->action == 'apply'){
            $model_user_create = new ModelUserCreate();
            $mail_address = QueryReplace::request('mail_address',      QueryReplace::STR);
            $user_name    = QueryReplace::request('user_name',      QueryReplace::STR);
            $password     = QueryReplace::request('password',      QueryReplace::STR);
            $token     = QueryReplace::post('token',      QueryReplace::THROUGH);

            if(!$this->checkApplyValidate($mail_address,$password,$token)){
                return;
            }
            $model_user_create->createPreUser($password,$user_name,$mail_address);
		}
		else{
            $this->template->setValue('token', CsrfTokenGenerate::generate());
		}

	}

	/**
	 *  validate処理
     * @param 全部str
	 * @return boolean
	 */
	private function checkApplyValidate($mail_address,$password,$token){
        $csrf_token_validate = new CsrfTokenValidate();
		$model_user_validator = new ModelUserValidator();
		if(!$csrf_token_validate->isAvailableToken($token)){
			$this->template->setValue('is_token_invalid', true);
			$this->has_error = true;
			return false;
		}
        if($model_user_validator->isInvalidPassword($password) ){
            $this->template->setValue('is_password_invalid', true);
            $this->has_error = true;
        }
        if($model_user_validator->isLessLengthPassword($password)){
            $this->template->setValue('is_password_not_enough', true);
            $this->has_error = true;
        }
		if($model_user_validator->isInvalidMail($mail_address) ){
			$this->template->setValue('is_mail_invalid', true);
			$this->has_error = true;
		}
		if($model_user_validator->isDuplicateMail($mail_address)
			|| $model_user_validator->isDuplicateMailPre($mail_address) ){
			$this->template->setValue('is_duplicate_mail', true);
			$this->has_error = true;
		}
		if($this->has_error){
			return false;
		}
		else{
			return true;
		}
	}


	public function output() {
		if ($this->has_error) {
			return $this->template->render('UserSignup_error.tpl');
		}
		else{
			if($this->action == 'apply') {
				return $this->template->render('UserSignupRegister.tpl');
			}
			else{
				return $this->template->render('UserSignup.tpl');
			}
		}
	}
}