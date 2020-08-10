<?php 
if(!isset($_SESSION)){
	session_start();
}
include_once('config.php');
$data = new ino();
if(isset($_SESSION['auth']) && $_SESSION['auth'] > 0){
	$data->setTable('from', 'events')->setTable('select', [
		'id' => 'idEvents',
		'unik' => 'unikEvents',
		'model' => 'modelEvents',
		'status' => 'statusEvents'
	]);
	if($data->ifPost(['unik' => 0, 'model' => 0])){
		$data->cud([
			'post' => ['unik', 'model']
		]);
	}elseif($data->ifPost(['id' => 0,'unik' => 0]) && isset($_GET['delete'])){
		$data->cud([
			'post' => ['unik'],
		]);
	}elseif(isset($_GET['unik']) && $_GET['unik'] > 0){
		$data->setTable('from', 'model, event, users')
			->setTable('where', "idModel = modelEvents AND idEvent = unikEvents AND idUsers = userModel")
			->setTable('select', [
				'model_id' => 'idUsers',
				'nama' => 'namaUsers',
				'foto' => 'fotoUsers',
				'txt' => 'namaEvent',
				'date' => 'dateEvent',
				'company' => 'companyEvent',
			])
			->setWhere('unikEvents', $_GET['unik']);
	}elseif(isset($_GET['notive'])){
		$data->setTable('from', 'model, event, users')
			->setTable('where', "idModel = modelEvents AND idEvent = unikEvents AND idUsers = companyEvent")
			->setTable('select', [
				'nama' => 'namaUsers',
				'foto' => 'fotoUsers',
				'txt' => 'namaEvent',
				'date' => 'dateEvent',
				'company' => 'companyEvent',
			])
			->setTable('where', "dateEvent >= '".date('Y-m-d')."'")
			->setWhere('userModel', $_SESSION['auth']);
	}
	$data->runTable();
}else{
	$data->setPrint('message', 'Error Auth')->setPrint('sesi', $_SESSION);
}
$data->json();
?>