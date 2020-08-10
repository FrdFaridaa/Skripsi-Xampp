<?php if(!isset($_SESSION)){
	session_start();
}
include_once('config.php');
$data = new ino();
$selects = [
	'id' => 'idModel',
	'model_id' => 'userModel',
	'nama' => 'namaUsers',
	'bio' => 'bioUsers',
	'foto' => 'fotoUsers',
	'jk' => 'jkModel',
	'tglah' => 'tglahModel',
	'tb' => 'tbModel',
	'bb' => 'bbModel',
	'us' => 'usModel',
	'jr' => 'jrModel',
	'tw' => 'twModel',
	'bs' => 'bsModel',
	'wk' => 'wkModel',
	'hijab' => 'hijabModel',
];
$posts = [];
foreach ($selects as $key => $value) {
	if(!in_array($key, ['id', 'model_id', 'nama', 'foto'])){
		$posts[$key] = '';
	}
}
if(isset($_SESSION['auth'])){
	$data->setTable('from', 'model, users')->setTable('where', 'idUsers = userModel')->setTable('select', $selects);
	if(isset($_GET['me'])){
		$data->setWhere('userModel', $_SESSION['auth']);
	}
	if(isset($_GET['notin']) && $_GET['notin'] > 0){
		$data->setTable('where', "idModel NOT IN (SELECT modelEvents FROM events WHERE unikEvents = '".$_GET['notin']."')");
	}
	if(isset($_GET['in']) && $_GET['in'] > 0){
		$data->setTable('where', "idModel IN (SELECT modelEvents FROM events WHERE unikEvents = '".$_GET['in']."')");
	}
	if($data->ifPost($posts)){
		$data->cud([
			'post' => array_keys($posts)
		]);
	}
}
$data->runTable()->json(); ?>