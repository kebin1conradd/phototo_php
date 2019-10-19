<?php
require_once dirname(__FILE__) . '/../DataBaseMapper/Photos.class.php';
/**
 *
 */

class ModelPhotoListProvider {

	private $result_count;
	/*
	 * get new list
	 * @return array
	 */

	public function getNewPhotoList(){
		return $this->getList(0,3);
	}

	/**
	 * get photo ist for top
	 * @param int $page
	 * @param int $page_num
     * return array
	 */
	public function getList($page,$page_num){
        $photos = new ModelDataBaseMapperPhotos();
        $result = $photos->getPhotoList($page,$page_num);
		return $result;
	}
}