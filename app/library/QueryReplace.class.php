<?php
/**
 * 取得クエリの安全な変換
 */
class QueryReplace{
    //値をそのまま使う
    const THROUGH       = 'through';
	const INT        = 'int';
	const STR     = 'string';

    /*
     * GETパラメータのvalidate
     */
	static public function get($name, $type = self::STR)
    {
        if (!isset($_GET[$name])) {
            return null;
        } else {
            return self::divideRequest($_GET[$name], $type);
        }
    }
    /*
     * postパラメータのvalidate
     */
	static public function post($name, $type = self::STR){
        if(!isset($_POST[$name])) {
            return null;
        }
        else {
            return self::divideRequest($_POST[$name], $type);
        }
	}
    /*
     * REQUESTのvalidate
     */
	static public function request($name, $type = self::STR){
            if(!isset($_REQUEST[$name])) {
                return null;
            }
            else {
                return self::divideRequest($_REQUEST[$name], $type);
            }
	}
    /*
     * 処理の分割
     */
	static private function divideRequest($val, $type = self::STR ){
		if(is_array($val)){
			foreach($val as $key => $body){
				if(is_array($body)){
					$val[$key] = self::divideRequest($val[$key], $type);
				}else{
					$val[$key] = self::validate($body, $type);
				}
			}	
		}else{
			$val = self::validate($val, $type);
		}

		return $val;
	}
    /*
     * int変換 null BYTE　対策など
     * @param　val
     * @param　validate_type
     */
	static private function validate($val, $type){
		$ret = '';

		if(strlen($val) != 0){
			$ret = $val;
		}
		$ret = str_replace("\0", "", $ret);
		if (strlen($ret)> 0) {
			if($type === self::INT){
				$ret = intval($ret);
            }elseif($type === self::STR){
				$ret = str_replace('\"',      '"',      $ret);
				$ret = str_replace("\r\n",    "\n",     $ret);
				$ret = str_replace("\r",      "\n",     $ret);
			}
			return $ret;
		}
		return '';
	}

}
