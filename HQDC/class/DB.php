<?php
class DB{
	private static $_instance = null;
	private $_results,
	        $_pdo,
		    $_query,
		    $_error = false,
		    $_count = 0;
	private function __construct(){
		try{
			//echo 'mysql:host = '.Config::get('mysql/host').'; db = '.Config::get('mysql/db') ,Config::get('mysql/username'), Config::get('mysql/password');
		   $this->_pdo = new  PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db') ,Config::get('mysql/username'), Config::get('mysql/password'));
           $this->_pdo ->exec("SET names UTF8"); 
		}catch(PDOException $e){
			die($e->getMessage());
		}

	}
	public static function getInstance(){
		if(isset(self::$_instance)===false){
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	public function query($sql, $params = array()){
		$this -> _error = false;
		$this -> _count = 0;
		if($this-> _query = $this -> _pdo->prepare($sql)) {
			$x=1;
			
			if(count($params)){
				foreach ($params as $param) {
					
					$this -> _query ->bindValue($x, $param);
					$x++;
				}
			}
			//$cai = "cai";$this -> _query ->bindValue(1, $cai);
			
			
			if($this -> _query -> execute()){
				
				$this-> _results = $this-> _query->fetchAll(PDO::FETCH_OBJ);
				$this -> _count = $this -> _query -> rowCount();
				
			}else{
				$this -> _error = true;
			}

			
		}
		return $this;
	}
	public function action($action, $table, $where = array()){
		if(count($where)===3){
			$operators = array('=','>','<','>=','<=');

			$field        =  $where[0];
			$operator   =  $where[1];
			$value      =  $where[2];
			if(in_array($operator, $operators)){
				$sql = "{$action} from {$table} where {$field} {$operator} ?";
				if(!$this -> query ($sql , array($value))->error()){
					
					return $this;
				}
			}
		}
		else if(count($where)===7){
			$operators = array('=','>','<','>=','<=');
			$connectors = array('and','or');
			$field        =  array($where[0],$where[4]);
			$operator = array($where[1],$where[5]);
			$value = array($where[2],$where[6]);
			$connector = $where[3];
			if(in_array($connector,$connectors)&&in_array($operator[0], $operators)&&in_array($operator[1], $operators)){
				$sql = "{$action} from {$table} where {$field[0]} {$operator[0]} ? {$connector} {$field[1]} {$operator[1]} ?";
				
				if(!$this -> query ($sql , $value)->error()){
					
					return $this;
				}
				
			}
		}
		else if ($where == '') {
		    $sql = "{$action} from {$table}";
		    if ($this->query($sql)) {
		        return $this;
		    }
		}
		return false;
	}

	public function get($table,$where = ''){
		return $this -> action('select * ', $table ,$where);
	}
	public function delete($table,$where){
		return $this -> action('delete', $table ,$where);

	}
	public function insert($table, $fields = array()){
		
		$keys = array_keys($fields);
		$values = null;
		$x =1;

		foreach ($fields as $field) {
			$values .= "?";
			if($x < count ($fields)){
				$values .=  ', ';
			}
			$x++;
			
		}
		// var_dump($fields);
		// die();
		$sql = "insert into {$table} (`"   .  implode('`,`', $keys)  .    "`) values ({$values})";

		if (!$this -> query($sql , $fields)->error()){
			return true;
		}

		return false;
	}
	public function update($table, $id, $fields){
		$set = '';
		$x = 1;

		foreach ($fields as $name =>$value) {
			$set .= "{$name} = ?";
			if($x < count($fields)){
				$set .=  ' , ';
			}
			$x++;
		}
		
		$sql = "update {$table} set {$set} where id = {$id}";

		if(!$this -> query($sql , $fields)-> error()){
			return true;
		}
		return false;

	}
	public function error(){
		return $this -> _error;
	}
	public function count(){
		return $this -> _count;
	}
	public function results(){
		return $this -> _results;
	}
	public function first(){
		return $this -> _results[0];
	}
}