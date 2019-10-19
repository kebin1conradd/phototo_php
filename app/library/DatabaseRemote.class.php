<?php
require_once dirname(__FILE__) . '/../config/Config.class.php';
require_once dirname(__FILE__) . '/../config/ServerDefine.class.php';
require_once dirname(__FILE__) . '/../library/PdoLog.class.php';
/**
 * データベースRemote
 */
class DatabaseRemote {

	protected $num_rows;
    private static $pdo_cache;
	private static $class_cache;
	/**
	 * コンストラクタ
	 */
	public function __construct(){
        $this->pdo = $this->createPdo();
	}


    /*
     * pdoオブジェクトの作成
     * return pdo
     */
    private function createPdo(){
        $name = 'phototo';

        if(!is_object(self::$class_cache[$name])) {
            $server = new ServerDefine();
            $configClass = Config::getInstance();
            $db   = $configClass->getMysqlDb();
            $host     = $server->getDbServer();
            $info = 'mysql:dbname='.$db.';host='.$host.';charset=utf8;';
            self::$pdo_cache  = new PdoLog($info,
                $configClass->getMysqlUser(),
                $configClass->getMysqlPass(),
                array(
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                )
            );
        }

        return self::$pdo_cache;
    }
	/**
	 * インスタンス
	 * @return　DatabaseRemote
	 */
	public static function getInstance(){
        if(!is_object(self::$class_cache)){
            self::$class_cache = new self();
        }
        return self::$class_cache;
	}
	/**
	 * Bind
	 * @param PDOStatement $statement 
	 * @param array $hash
	 */
	public function bind($statement, $hash) {
		foreach ((array) $hash as $key => $val) {
			$statement->bindValue(':' . $key, $val);
		}
		return $statement;
	}

	/**
	 * クエリを実行
	 * @param string $sql
	  * @param array $hash
	 * @return boolean
	 */
	public function execute($sql, $hash = array()) {
		$statement = $this->pdo->prepare($sql);
		$statement = $this->bind($statement, $hash);
		if(!$statement->execute()){
			return false;
		}
		$this->effect_rows = $statement->rowCount();
		return true;
	}

	/**
	 * クエリ実行しすべてのデータを取得
	 * @param string $sql
	 * @param array $hash
	 * @return mixed 
	 */
	public function getAllData($sql, $hash = array()) {
		$statement = $this->pdo->prepare($sql);
		$statement = $this->bind($statement, $hash);
		$statement->execute();
		$this->num_rows = $statement->rowCount();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	/**
	 * クエリーを実行して1行取得する
	 * @param string $sql
	 * @param array $hash
	 * @return mixed 
	 */
	public function getOneLine($sql, $hash = array()) {
		$statement = $this->pdo->prepare($sql);
		$statement = $this->bind($statement, $hash);
		if(!$statement->execute()){
			return false;
		}
		$this->num_rows = $statement->rowCount();
		return $statement->fetch(PDO::FETCH_ASSOC);
	}
	/**
	 * 最後にinsertした行の取得
	 */
	public function lastInsertId(){
		return $this->pdo->lastInsertId();
	}

}
