<?php

namespace XSYD\DB;
use Mysqli;

/**
* @version 0.2
* Simple MySQL API
* For PHP 5.6+
* ALL RIGHTS RESERVED
* @author GHL(LiuXing)
* @uses new XSYD\DB\MySQL();
* @uses  
*/
class MySQL
{
	private $dbconn;
	private $is_down;

	function __construct($addr,$dbuser,$dbpass,$dbname)
	{
		$mysqli = new Mysqli($addr,$dbuser,$dbpass,$dbname);
		$mysqli_errno = $this->connect_errno();
    	if ( $mysqli_errno ) return $mysqli_errno;
    	$mysqli->set_charset('utf8mb4');
	    $this->$dbconn =  $mysqli;
	    $this->$is_down = false;
	}

	public function Execute(
							string $sql,
							string $types=null,
							...$params
							){
		$result  = '';

		if ( !isset($params) ){
			//PLZ ATTENTION! 
			//For security,please USE prepare method
			//This may cause some SQL Injections
			$query = $this->$dbconn->query($sql);
			$result = $this->FetchArray($query,null,'fetch_assoc',true);
			$query->free();
			$query->close();
			return $result;
		}else{
			if ( is_array($params[0]) ) {
				$params = $params[0];
			}
			$params  = $this->apply_filter_array($params);
			$prepare = $this->$dbconn->prepare($sql);
			$types = empty($types) ? str_pad('', count($params),'s') : $types;  
			array_unshift($params,$types);
			call_user_func_array(array($prepare,'bind_param'), $params);
			$prepare->execute();
			if ( $this->CheckMySQLndDriver() ){
				$_GetResult = $prepare->get_result();
				$result = $this->FetchArray($prepare,$_GetResult,'fetch_assoc');
			}else{
				$prepare->store_result();
				$result = $this->FetchArray($prepare,'$this','fetch_assoc_prepare');
			}
			$prepare->free_result();
			$prepare->close();
			return $result;
		}
	}

	public function Get(
						array $params,
						string $table,
						string $columns=null,
						string $where=null,
						string $order=null,
						int $length=-1,
						int $offset=0
						){
	  	$columns = is_null($columns) ? '*' : $columns;
		$sql = "SELECT $columns FROM $table";

		if ( isset($where) ){
			$sql .= " WHERE $where";
		}
		if ( isset($order) ){
			$sql .= " ORDER BY $order";
		}
		if ( -1 !== $length && $length > 0) {
			$sql .= " LIMIT $length";
		}
		if ( $offset > 0 ){
			$sql .= " OFFSET $offset";
		}
		try {
			return $this->Execute( $sql,
								   str_pad('',count($params),'s'),
								   $params );

		}catch( Exeception $e){
			return $e;
		}

	}

	public function Insert(
						   array $params,
						   string $table,
						   string $columns=null
						  ){
		$values = str_pad('', count($params),'?,');
		$values = substr($values,0,strlen($values)-1);
		$sql = isset($columns) ? "INSERT INTO {$table} VALUES( {$values} )" :
								 "INSERT INTO {$table} ( {$columns} ) VALUES( {$values} )" ;
		try {
			return $this->Execute( $sql,
								   str_pad('',count($params),'s'),
								   $params );

		}catch( Exeception $e){
			return $e;
		}
	}

	public function Update(
						   array $params,
						   string $table,
						   string $columns,
						   string $where=null
						  ){
		$sql = "UPDATE $table SET $columns";
		if ( isset($where) ){
			$sql .= " WHERE $where";
		}
		try {
			return $this->Execute( $sql,
								   str_pad('',count($params),'s'),
								   $params );

		}catch( Exeception $e){
			return $e;
		}

	}

	public function Drop(
						 string $table
						){
		// Prevent from some sb
		if ( '*' == $table) return false;
		$table = $this->apply_filter($table);
		$sql = "DROP TABLE IF EXISTS $table";

		try {
			return $this->Execute( $sql );

		}catch( Exeception $e){
			return $e;
		}

	}

	public function Delete(){

	}

	public function Close() : bool{
		if ( $this->$is_down || is_null($this->$is_down) || empty($this->$is_down )){
			return false;
		}else{
			if ( $this->$dbconn->close() ){
				$this->$is_down = null;
				$this->$dbconn  = null;
				return true;
			}else{
				return false;
			}

		}

	}
	/**
	* For those PHP which are not compiled with mysqlnd
	* Code From @link http://stackoverflow.com/a/28622062/1617737
	*/
	public function fetch_assoc_prepare($stmt){
        	$result = array();
        	$md = $stmt->result_metadata();
        	$params = array();
        	while($field = $md->fetch_field()) {
            	$params[] = &$result[$field->name];
        	}
        	call_user_func_array(array($stmt, 'bind_result'), $params);
        	if($stmt->fetch())
            	return $result;
    		}

    		return null;
	}

	public function FetchArray($stmt,$class=null,$function,$isQuery=false){
			if ( '1' == $stmt->num_rows || '0' == $stmt->num_rows){
				return $isQuery ? call_user_func(array($stmt,$function)) : 
								  call_user_func(array($class,$function),$stmt);
			}elseif ( $stmt->num_rows > 1 ) {
				$result = array();
				$array = array();
				if ( $isQuery ) {
					while ( $array = call_user_func(array($stmt,$function)) ) {
						$result[] = $array;
					}
				}else {
					if ($class == '$this'){
						while ( $array = $this->$function($stmt) ) {
							$result[] = $array;
						}
					}else{
						while ( $array = call_user_func(array($class,$function),$stmt) ) {
							$result[] = $array;
						}
					}
				}
				return $result;
			}else{
				return null;
			}
		}

	public function CheckMySQLndDriver(){
		return strpos( $this->$dbconn->client_info , 'mysqlnd' );
	}
	public function apply_filter_array(array $value) : array{
		foreach ($value as $k => $v) {
			$value[$k] = $this->apply_filter($v);
		}
		return $value;
	}
	public function apply_filter($value){
		return addslashes( $this->$dbconn->real_escape_string($value) );
	}
}



?>