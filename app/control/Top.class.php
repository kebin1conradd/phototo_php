<?php
require_once dirname(__FILE__) . '/../library/TemplateRenderer.class.php';
require_once dirname(__FILE__) . '/../model/Format/Photo.class.php';
require_once dirname(__FILE__) . '/../model/Photo/ListProvider.class.php';

/**
 *　トップページ
 */

class Top {
	private $template;

	public function action(){
		$this->template = TemplateRenderer::getInstance();
		$model_format_photo = new ModelFormatPhoto();
		$session   = Session::getInstance();
        $model_photo_list_provider = new ModelPhotoListProvider();
        $result = $model_photo_list_provider->getNewPhotoList();
        $this->template->setValue('user_id', $session->get('user_id'));
        $this->template->setValue('top_photo_list',$model_format_photo->formatPhotoInfoList($result));
	}

	public function output(){
		return $this->template->render('Top.tpl');
	}
}