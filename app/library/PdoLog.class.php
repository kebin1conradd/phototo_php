<?php
require_once dirname(__FILE__) . '/../library/Log.class.php';
/**
 * pdoのログ処理　ログライブラリとSet
 */
class PdoLog extends PDO{

    /*
　　 * queryを継承して失敗時ログ取得
　　 * return　mixed
　　 */
	public function query($sql){
		try{
			return parent::query($sql);
		}catch(Exception $e){
            $messsage = $e->getMessage() . "\n" . $sql;
			Log::write("mysql's　error",$messsage);

			return false;
		}
	}
    /*
  　* execを継承して失敗時ログ取得
     * return　mized
     */
    public function exec($statement){
        try{
            return parent::exec($statement);
        }catch(Exception $e){
            $messsage = $e->getMessage() . "\n" . $statement->QueryReplace;
            Log::write('mysql_error',$messsage);

            return false;
        }
    }
}


