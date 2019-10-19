<?php
require_once dirname(__FILE__) . '/Base.class.php';

/**
 * DataBaseMapperPhotos
 */

class ModelDataBaseMapperPhotos extends ModelDataBaseMapperBase{

	/**
	 * テーブル名
	 */
	public $table = 'photos';
	/*


	/**
	 * 特定photo_idを取得
	 * @param int $photo_id
	 * @return mixed
	 */
	public function getPhotoInfo($photo_id) {
		$param = array("photo_id" => $photo_id);
		return $this->selectFirstColumn($param);
	}

	/**
     * 画像一覧取得
	 * @param int $page
	 * @param int $page_num
	 * @return mixed
	 */
	public function getPhotoList($page,$page_num) {
		$sql = "select * from ". $this->getTable() ."  order by add_date desc LIMIT ".intval($page*$page_num).",$page_num;";
		return $this->db->getAllData($sql);
	}



	/**
	 * 画像idの挿入
	 * @param string $title 画像タイトル
	 * @param string $extension_name 拡張子名
	 * @param string $mime_type mime-type
	 * @param int $user_id 登録user_id
	 * void
	 *
	*/
	public function insertImage($title, $extension_name, $mime_type, $user_id) {
		$insert_value = array(
			'title' => $title,
			'extension_name'     => $extension_name,
			'mime_type'  => $mime_type,
			'add_date'      => date("Y-m-d H:i:s"),
			'user_id' => $user_id,
		);
		$this->insert($insert_value);
	}





}