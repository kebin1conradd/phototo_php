<?php

require_once dirname(__FILE__) . '/../../config/ServerDefine.class.php';
require_once dirname(__FILE__) . '/../../library/DatabaseRemote.class.php';

/**
 * 簡易Ormapper
 * よく使うSQLのみ自動生成　必要最低限のみ
 * DataBaseMapper
 */
abstract class ModelDataBaseMapperBase {
	public $table = null;
	protected $db = null;
    const DEF_TARGET = '*';
	/**
	 * コンストラクタ
	 */
	public function __construct() {
		$this->db =  DatabaseRemote::getInstance();
	}

	/**
	 * insert
	 * @param mixed $insert_val
	 * @return boolean
	 */
	public function insert($insert_val) {
		if (count($insert_val) == 0) {
			return false;
		}
        $column_name_sql =  $this->createColumnSql($insert_val);
        $value_of_sql = $this->createSetSql($insert_val);
		$sql = 'INSERT INTO '.$this->getTable().' ('.$column_name_sql.') VALUES ('.$value_of_sql.')';
		$this->db->execute($sql, $insert_val);
		return true;
	}

	/**
     * DUPLICATE KEY UPDATEを用いたinsert
	 * @param array $insert_val
	 * @param array $update_val
	 * @return boolean
	 */
	public function insertDuplicated($insert_val, $update_val) {
		if (count($insert_val) == 0) {
			return false;
		}
        $set_sql = $this->createUpdateSetSql($update_val);
        $column_name_sql =  $this->createColumnSql($insert_val);
        $value_of_sql = $this->createSetSql($insert_val);
		$sql = 'INSERT INTO '.$this->getTable().'('.$column_name_sql.') VALUES ('.$value_of_sql.') ON DUPLICATE KEY UPDATE '.$set_sql;
		$param = array_merge($insert_val, $update_val);
		$this->db->execute($sql, $param);
		return true;

	}


	
	/**
	 * update
	 * @param array $update_val　keyをcolumn　valueを値とする
	 * @param array $where_param　keyをcolumn　valueを値とする
	 * @param int $limit
	 * @return boolean
	 */
	public function update($update_val, $where_param, $limit = null) {
		if (count($update_val) == 0) {
			return false;
		}
        $where_sql = $this->createWhereSql($where_param);
		if ($limit != null) {
            $limit = (int) $limit;
            $limit_sql = " LIMIT $limit ";
		}
        $set_sql = $this->createUpdateSetSql($update_val);
		$sql = 'UPDATE '.$this->getTable().' SET '.$set_sql.' '.$where_sql.' '.$limit_sql;
		$param = array_merge($update_val, $where_param);
		$this->db->execute($sql, $param);
		return true;
	}


	/**
	 * delete
	 * @param array $where_param　keyをcolumn　valueを値とする
	 * @param int $limit
	 * @return boolean
	 */
	public function delete($where_param,$limit) {
       
		if ($limit != null) {
            $limit = (int) $limit;
            $limit_sql = " LIMIT $limit ";
		}
        $where_sql = $this->createWhereSql($where_param);
		$sql = 'DELETE FROM '.$this->getTable().' '. $where_sql.' '.$limit_sql;
	
		$this->db->execute($sql, (array)$where_param);
		return true;
	}


	/**
	 * select
	 * @param array $where_param keyをcolumn　valueを値とする
	 * @param string $target 　取得対象columnの文字列
	 * @param int $limit
	 * @return mixed
	 */
	public function select($where_param, $target = null, $limit = null) {
        if($target == null) {
            $target = self::DEF_TARGET;
        }
		$sql = $this->createSelectSQL( $where_param, $target, $limit);
		return $this->db->getAllData($sql,  $where_param);
	}



	/**
	 *
	 * @param array $where_param　keyをcolumn　valueを値とする
	 * @param string $target　取得対象columnの文字列
	 * @return array
	 */
	public function selectFirstColumn( $where_param, $target = null) {
        if($target == null) {
            $target = self::DEF_TARGET;
        }
		$sql = $this->createSelectSQL( $where_param, $target,1);
		return $this->db->getOneLine($sql,  $where_param);
	}


	/**
	 * SQL文の作成
	 * @param array $where_param　keyをcolumn　valueを値とする
	 * @param string $target　取得対象comlunの文字列
	 * @param int　$limit
	 * @return string
	 */
	private function createSelectSQL( $where_param, $target, $limit = null) {
        $where_sql = $this->createWhereSql($where_param);
        $sql_target = $target;
		if($sql_target == null) {
            $sql_target = self::DEF_TARGET;
        }
		if ($limit != null) {
			$limit = (int) $limit;
            $limit_sql = " LIMIT $limit ";
		}


		return 'SELECT '.$sql_target.' FROM '.$this->getTable().' '.$where_sql.' '.$limit_sql;
	}

	/*
	 * where文を作る
	 * @param array $where_param　keyをcolumn　valueを値とする
	 * return string
	 */
    protected function createWhereSql($where_param){
        $where_sql = "";
        foreach ((array)$where_param as $column_name => $value) {
            if($where_sql) {
                $where_sql .= ' AND ';
            }
            else{
                $where_sql .= ' WHERE ';
            }
            $where_sql .= "`{$column_name}` =:{$column_name}";
        }
        return $where_sql;
    }
    /*
     * insertのvalue文を作る
     * @param array $set_param　keyをcolumn　valueを値とする
     * return string
     */
    protected function createSetSql($set_param){
        $set_sql = "";
        foreach ((array)$set_param as $column_name => $value) {
            if($set_sql) {
                $set_sql .= ',';
            }
            $set_sql .= ":{$column_name}";
        }
        return $set_sql;
    }
    /*
 　 * UpodateのSet文を作る
 　 * @param array $set_param　keyをcolumn　valueを値とする
  * return string
 　 */
    protected function createUpdateSetSql($set_param){
        $set_sql = "";
        foreach ((array)$set_param as $column_name => $value) {
            if($set_sql) {
                $set_sql .= ',';
            }
            $set_sql .=  "`{$column_name}` =:{$column_name}";
        }
        return $set_sql;
    }
    /*
     * insertのcolumn指定文を作る
     * @param array $val_param　keyをcolumn　valueを値とする
     * return string
     */
    protected function createColumnSql($val_param){
        $col_sql = "";
        foreach ((array)$val_param as $column_name => $value) {
            if($col_sql) {
                $col_sql .= ',';
            }
            $col_sql .=  "`{$column_name}`";
        }
        return $col_sql;
    }
    /*
     * テーブル名取得
     */
	protected function getTable(){
        return $this->table;

	}
	/**
	 * 最後にinsertしたID
	 * return int
	 */
	public function getLastInsertId(){
		return $this->db->lastInsertId();
	}


}
