<?php 
if(!isset($_SESSION)){
	session_start();
}
include_once('config.php');
$data = new ino();
if(isset($_SESSION['auth']) && $_SESSION['auth'] > 0){
	$data->setTable('from', 'event')->setTable('select', [
		'id' => 'idEvent',
		'fee' => 'feeEvent',
		'nama' => 'namaEvent',
		'time' => 'timeEvent',
		'date' => 'dateEvent',
		'company' => 'companyEvent',
	]);
	if($data->ifPost(['nama' => '', 'date' => '', 'fee' => '', 'time' => ''])){
		$data->cud([
			'post' => ['nama', 'fee', 'date', 'time'],
			'push' => [
				'company' => $_SESSION['auth']
			]
		]);
	}elseif($data->ifPost(['id' => 0])){
		$data->setWhere('idEvent', $_POST['id']);
	}else{
		if(isset($_GET['home'])){
			$data->setTable('from', 'users')
				->setTable('select', [
					'nama' => 'namaUsers',
					'foto' => 'fotoUsers',
					'txt' => 'namaEvent',
					'date' => 'dateEvent'
				])
				->setTable('where', "idUsers = companyEvent");
				if(isset($_GET['profile'])){
					$data->setTable('where', "dateEvent < '".date('Y-m-d')."'");
					if($_GET['profile'] === 'model'){
						$data->setTable('where', "(SELECT COUNT(*) FROM events, model WHERE idModel = modelEvents AND unikEvents = idEvent AND userModel = '".$_SESSION['auth']."') > 0");
					}else{
						$data->setTable('where', "companyEvent = '".$_SESSION['auth']."'");
					}
				}else{
					$data->setTable('where', "(SELECT COUNT(*) FROM events, model WHERE idModel = modelEvents AND unikEvents = idEvent AND userModel = '".$_SESSION['auth']."') < 1 AND 
						dateEvent >= '".date('Y-m-d')."'");
				}
		}else{
			$data->setWhere('companyEvent', $_SESSION['auth']);
		}
	}
	$data->runTable();
}else{
	$data->setPrint('message', 'Error Auth')->setPrint('sesi', $_SESSION);
}
$data->json();
?>