<?php
require_once dirname(__FILE__) . '/../../config/Config.class.php';
require_once dirname(__FILE__) . '/../../model/DataBaseMapper/Users.class.php';
require_once dirname(__FILE__) . '/../../config/ServerDefine.class.php';
/**
 *
 * 整形処理
 */

class ModelFormatPhoto {
	/*
	 * 写真のデータ整形
	 * @param array $rows
	 * @return array
	 */
	public function formatPhotoInfoList($rows){

        if(!$rows){
            return $rows;
        }
		foreach ($rows as &$row) {
			$row['img_url'] =  $this->getImageUrl($row['photo_id'],$row['extension_name']);
		}

		return $rows;
	}
	
	/*
	 * 画像URLの取得
	 * @param int $photo_id
	 * @param string $extension_name
	 * return string
	 */
	protected function getImageUrl($photo_id,$extension_name){
		$config = Config::getInstance();
		$url = 'https://'.$config->getS3Domain().'/phototo/pc/' . $photo_id . '.' . $extension_name;
		
		return $url;
	}

}