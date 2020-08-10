<?php 
$ino_driver = [
	'database' => [
		'hostname' => 'localhost',
		'username' => 'root',
		'password' => '',
		'database' => 'farida'
	],
	'timezone' => 'Asia/Jakarta',
	'backup' => true
];
$content = file_get_contents("php://input");
if(isset($content) && !empty($content)){
  $json = json_decode($content, true);
  foreach ($json as $key => $value) {
  	$_POST[$key] = $value;
  }
}
class ino{
	public $db;
	private $print = [
		'status' => 'error',
		'message' => '',
		'data' => []
	];
	private $table = [
		'select' => [],
		'from' => '',
		'where' => '',
		'order' => ''
	];
	private $field = [];
	private $post = [];
	private $error = [
		'db' => [],
		'cud' => [],
		'sql' => [],
		'post' => [],
		'sesi' => [],
		'field' => []
	];
	public $run = true;
	public $lastid = 0;
	public $crud = 'select';

	function __construct(){
		if(!isset($_SESSION)){
			session_start();
		}
		$ino_driver = $GLOBALS['ino_driver'];
		date_default_timezone_set($ino_driver['timezone']);
		$this->db = new mysqli(
			$ino_driver['database']['hostname'],
			$ino_driver['database']['username'],
			$ino_driver['database']['password'],
			$ino_driver['database']['database']
		);
		if ($this->db->connect_error) {
			$this->error['db'][] = $this->db->connect_errno . ': ' . $this->db->connect_error;
		}
	}

	function ifPost($arr){
		$no = 0;
		$this->post = [];
		$this->error['post'] = [];
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			foreach ($arr as $key => $if) {
				if(isset($_POST[$key])){
					$this->post[$key] = $_POST[$key];
					if($if === '' && strlen($_POST[$key]) > 0){
						// $no++;
					}elseif(gettype($if) === 'integer' && (Int)$_POST[$key] && (Int)$_POST[$key] > $if){
						// $no++;
					}elseif(gettype($if) === 'array' && in_array($_POST[$key], $if)){
						// $no++;
					}elseif(gettype($if) === 'NULL'){
						if(empty($_POST[$key])){
							$this->post[$key] = '';
						}
					}else{
						$this->post[$key] = '';
						$this->error['post'][] = 'failed ' . $key;
					}
					$no++;
				}else{
					$this->post[$key] = '';
					$this->error['post'][] = 'not isset ' . $key;
				}
			}
		}
		return count(array_keys($arr)) === $no;
	}

	function cud($arr = []){
		$default = [
			'id' => 'id',
			'table' => '',
			'post' => [],
			'push' => [],
			'not_exits' => [],
			'set_query' => true
		];
		if(!empty($this->table['from'])){
			$default['table'] = trim(explode(',', $this->table['from'])[0]);
		}
		foreach ($default as $key => $value) {
			if(!isset($arr[$key])){
				$arr[$key] = $value;
			}
		}
		if(!empty($arr['table']) && $this->run && isset($_POST[$arr['id']]) && 
		 (count($arr['post']) > 0 || array_keys($arr['push']) > 0)){
			$id = !empty($_POST[$arr['id']]) ? $_POST[$arr['id']] : 0;
			$sql = $insertKeys = $insertValues = $update = $chxExits = "";
			$no = 0;
			foreach ($arr['post'] as $key) {
				if(isset($this->post[$key]) && isset($this->table['select'][$key])){
					$value = $this->post[$key];
					$insertKeys .= ($no > 0 ? ", " : "") . $this->table['select'][$key];
					$insertValues .= ($no > 0 ? ", " : "") . "'" . $value . "'";
					$update .= ($no > 0 ? ", " : "") . $this->table['select'][$key] . "='" . $value . "'";
					$chxExits .= ($no > 0 ? ", " : "") . "'" . $value . "' AS " . $this->table['select'][$key];
					$no++;
				}else{
					$this->error['cud'][] = 'not isset post ' . $key;
				}
			}
			foreach ($arr['push'] as $key => $value) {
				if(isset($this->table['select'][$key])){
					$insertKeys .= ($no > 0 ? ", " : "") . $this->table['select'][$key];
					$insertValues .= ($no > 0 ? ", " : "") . "'" . $value . "'";
					$update .= ($no > 0 ? ", " : "") . $this->table['select'][$key] . "='" . $value . "'";
					$chxExits .= ($no > 0 ? ", " : "") . "'" . $value . "' AS " . $this->table['select'][$key];
					$no++;
				}else{
					$this->error['cud'][] = 'not isset field ' . $key;
				}
			}
			if(!empty($id)){
				if(isset($_GET['delete'])){
					$crud = 'delete';
					$sql = "DELETE FROM " . $arr['table'];
				}else{
					$crud = 'update';
					$sql = "UPDATE " . $arr['table'] . " SET $update";
				}
				$sql .= " WHERE " . $this->table['select'][$arr['id']] . " = '" . $id . "'";
			}else{
				$crud = 'insert';
				$sql = "INSERT INTO " . $arr['table'] . " ($insertKeys)";
				$not_exits = array_keys($arr['not_exits']);
				if(count($not_exits) > 0){
					$sql .= " SELECT * FROM (SELECT $chxExits) AS tmp WHERE NOT EXISTS (
  SELECT ".implode(',', $not_exits)." FROM " . $arr['table'] . " WHERE ".implode(',', $not_exits)." = '".implode("','", array_values($arr['not_exits']))."'
) LIMIT 1";
				}else{
					$sql .= " VALUES ($insertValues)";
				}
			}
			$this->print['cud'] = $sql;
			$query = $this->query($sql);
			if($query){
				$this->lastid = $id;
				if($crud === 'insert'){
					$this->lastid = $this->db->insert_id;
				}
				if($this->crud === 'select' && $arr['set_query']){
					$this->setWhere($this->table['select'][$arr['id']], $this->lastid);
				}
				$this->crud = $crud;
			}
		}else{
			$this->run = false;
			$this->error['cud'] = 'System Shutdown';
			if(!isset($_POST[$arr['id']])){
				$this->error['cud'] = 'Cannot Get ID';
			}elseif(count($arr['post']) < 1){
				$this->error['cud'] = 'Empty Post';
			}elseif(empty($arr['table'])){
				$this->error['cud'] = 'Table Null';
			}
		}
		return $this;
	}

	function setTable($key = 'from', $value = '', $attr = null){
		$key = strtolower($key);
		if(array_key_exists($key, $this->table)){
			if($key === 'select'){
				foreach ($value as $as => $val) {
					$this->table[$key][$as] = $val;
				}
			}elseif($key === 'where'){
				$attr = $attr ? $attr : 'AND';
				$this->table[$key] .= (empty($this->table[$key]) ? '' : " $attr ") . $value;
			}else{
				$this->table[$key] .= (empty($this->table[$key]) ? '' : ", ") . $value;
			}
		}
		return $this;
	}

	function setWhere($key, $value, $attr = 'AND'){
		return $this->setTable('where', "$key = '$value'", $attr);
	}

	function postWhere($arr = []){
		$arr = (count($arr) > 0) ? $arr : array_keys($this->post);
		foreach ($arr as $key) {
			if(isset($this->post[$key]) && isset($this->table['select'][$key])){
				$this->setWhere($this->table['select'][$key], $this->post[$key]);
			}
		}
		return $this;
	}

	function runTable($arr = [], $setObj = true, $group = false){
		if(!empty($this->table['from'])){
			$sql = "SELECT ";
			$no = 0;
			if(count(array_keys($this->table['select'])) > 0){
				foreach ($this->table['select'] as $value => $key) {
					$sql .= ($no > 0 ? ", " : "") . $key . " AS " . $value;
					$no++;
				}
			}
			// foreach ($this->field as $key => $value) {
			// 	$sql .= ($no > 0 ? ", " : "") . $key . " AS " . $value;
			// 	$no++;
			// }
			$sql .= " FROM " . $this->table['from'];
			if(!empty($this->table['where'])){
				$sql .= " WHERE " . $this->table['where'];
			}
			if(!empty($this->table['order'])){
				$sql .= " ORDER BY " . $this->table['order'];
			}
			if($group){
				$sql = "SELECT * FROM ($sql) AS tmp_table GROUP BY $group";
			}
			if(isset($_GET['print_sql_ino'])){
				$this->print['sql'] = $sql;
			}
			if($this->run){
				$query = $this->query($sql);
				$this->print['data'] = [];
				$this->print['status'] = 'success';
				if($query && $query->num_rows > 0){
					if(count($arr) > 0){
						while($row = $query->fetch_assoc()){
							$temp = [];
							foreach ($arr as $key) {
								if(isset($row[$key])){
									$temp[$key] = $row[$key];
								}else{
									$this->error['field'][] = 'Cannot get field ' . $key;
								}
							}
							$this->print['data'][] = $temp;
						}
					}else{
						while($row = $query->fetch_assoc()){
							$this->print['data'][] = $row;
						}
					}
				}
				if($setObj && count($this->print['data']) === 1){
					$this->print['data'] = $this->print['data'][0];
				}
			}else{
				$this->print['message'] = 'Cannot Get Data';
			}
		}else{
			$this->print['message'] = 'Table Null';
		}
		return $this;
	}

	function query($sql){
		$query = $this->db->query($sql);
		if(!$this->db->error && $query){
			return $query;
		}else{
			$this->error['db'][] = $this->db->error;
			return false;
		}
	}

	function setSession($uniq, $key){
		if(in_array($key, array_keys($this->print['data']))){
			$_SESSION[$uniq] = $this->print['data'][$key];
		}else{
			$this->error['sesi'][] = 'Cannot Set Session ' . $uniq;
		}
		return $this;
	}

	function setPrint( $key, $val ){
		$this->print[$key] = $val;
		return $this;
	}

	function json(){
		header('Content-type: application/json');
		if($this->run){
			foreach ($this->error as $key => $value) {
				if(count($value) > 0){
					$this->print['message'] .= "\n".strtoupper($key).": ";
					if(gettype($value) === 'array' && count($value) > 0){
						$this->print['message'] .= implode(', ', $value);
					}else{
						$this->print['message'] .= $value;
					}
				}
			}
		}
		echo json_encode($this->print, JSON_PRETTY_PRINT);
		$this->db->close();
	}
}
?>