<?php 
if(!isset($_SESSION)){
	session_start();
}
include_once('config.php');
$data = new ino();
if(isset($_SESSION['auth']) && $_SESSION['auth'] > 0){
	$_id = $_SESSION['auth'];
	$data->setTable('select', [
		'id' => 'idMessage',
		'dari' => 'dariMessage',
		'ke' => 'keMessage',
		'txt' => 'txtMessage',
		'date' => 'dateMessage',
		'lihat' => 'lihatMessage',
	]);
	if(isset($_GET['id']) && $_GET['id'] > 0 && $_SESSION['auth'] !== $_GET['id']){
		$_us = $_GET['id'];
		$data->setTable('from', 'message')->setTable('order', "dateMessage DESC")->setTable('where', "((dariMessage = '$_id' AND keMessage = '$_us') OR (dariMessage = '$_us' AND keMessage = '$_id'))");
		if($data->ifPost(['txt' => ''])){
			$data->cud([
				'post' => ['txt'],
				'push' => [
					'dari' => $_id,
					'ke' => $_us
				]
			]);
			$data->setPrint('sendMessage', 'Yes');
		}else{
			$user = $data->db->query("SELECT * FROM users WHERE idUsers = '$_us'");
			if($user->num_rows > 0){
				$rowUsers = $user->fetch_assoc();
				$data->setPrint('userKe', [
					'id' => $rowUsers['idUsers'],
					'nama' => $rowUsers['namaUsers'],
					'foto' => $rowUsers['fotoUsers'],
					'online' => $rowUsers['onlineUsers']
				]);
			}
		}
		$data->runTable([], false);
	}else{
		$sql = "SELECT 
				max(idMessage) as idm,
			    IF(dariMessage = '1', keMessage, dariMessage) as usr
			FROM message, users 
			WHERE IF(dariMessage = '1', keMessage, dariMessage) = idUsers 
				AND (dariMessage = '1' OR keMessage = '1')
			GROUP BY idUsers
			ORDER BY idm DESC";
		$data->setTable('from', "($sql) as tmp, message, users")->setTable('select', [
			'user' => 'idUsers',
			'nama' => 'namaUsers',
			'foto' => 'fotoUsers',
			'online' => 'onlineUsers',
		])->setTable('where', "idm = idMessage AND usr = idUsers")->setTable('order', "idMessage DESC");
		$data->runTable([], false);
	}
}else{
	$data->setPrint('message', 'Error Auth')->setPrint('sesi', $_SESSION);
}
$data->json();
?>