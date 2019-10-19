<?php
require_once dirname(__FILE__) . '/../library/CsrfToken.class.php';
require_once dirname(__FILE__) . '/../library/Session.class.php';
require_once dirname(__FILE__) . '/../library/TemplateRenderer.class.php';
require_once dirname(__FILE__) . '/../model/Format/Photo.class.php';
require_once dirname(__FILE__) . '/../model/Photo/Upload.class.php';

/**
 *
 */
class Upload{

	private $has_error = false;
	private $action;
    private $template;
	public function action(){
		$this->template = TemplateRenderer::getInstance();
        $model_login_state= new ModelLoginState();
        if(!$model_login_state->isLogin()) {
            $this->template->setValue('is_not_login', true);
            $this->has_error = true;
            return;
        }
        $this->template->setValue('token',CsrfTokenGenerate::generate() );
        $this->action = QueryReplace::request('action',      QueryReplace::STR);



		if($this->action == 'apply'){
			$this->actionApply();
		}
	}
	/*
	 * uploadの実行
	 */
	private function actionapply(){
        $token   = QueryReplace::post('token',      QueryReplace::THROUGH);
        $title = QueryReplace::request('title',      QueryReplace::STR);
        if(!$this->checkUploadValidate($title,$token)){
            return false;
        }
		$model_login_state= new ModelLoginState();
        $model_photo_upload = new ModelPhotoUpload();
		$user_id = $model_login_state->getLoginUserId();
		$model_photo_upload->upload($title,$user_id);

		$err_message = $model_photo_upload->getErrorMessage();
		if($err_message != ''){
			$this->template->setValue('is_upload_err', true);
			$this->template->setValue('err_message', $err_message);
			$this->has_error = true;
			return false;
		}

		$photo_id =$model_photo_upload->getUploadPhotoId();
		$this->template->setValue('photo_id', $photo_id);
	}




	/**
	 * upload時の validate
	 * @return boolean
	 */
	private function checkUploadValidate($title,$token){
        $csrf_token_validate = new CsrfTokenValidate();
		if(!$csrf_token_validate->isAvailableToken($token)){
			$this->err_message ='token不正';
			$this->has_error = true;
			return false;
		}

		if($title == '') {
			$this->template->setValue('is_invalid_title', true);
			$this->has_error = true;
			return false;
		}


		return true;
	}


	public function output(){
		if ($this->has_error) {
			return $this->template->render('Upload_error.tpl');
		}
		else{
			if($this->action == 'apply') {
				return $this->template->render('UploadAfter.tpl');
			}
			else{
				return $this->template->render('Upload.tpl');
			}
		}
	}
}

