<?php 
if(!isset($_SESSION)){
	session_start();
}
$print = [
	'status' => 'error',
	'message' => '',
	'data' => []
];
include_once('config.php');
$db = new mysqli($ino_driver['database']['hostname'], $ino_driver['database']['username'], $ino_driver['database']['password'], $ino_driver['database']['database']);
header('Content-type: application/json');
if(isset($_SESSION['auth']) && isset($_POST['id']) && $_SESSION['auth'] === $_POST['id'] && isset($_FILES['avatar'])){
	$val = $_FILES['avatar'];
	$name = $val['name'];
	$size = $val['size'];
  $exte = strtolower(pathinfo($name, PATHINFO_EXTENSION));
  $rename = $_SESSION['auth'] . '_' . $size . '.' . $exte;

	if(@move_uploaded_file($val['tmp_name'], 'Images/' . $rename)){
		$db->query("UPDATE users SET fotoUsers = '".$rename."' WHERE idUsers = " . $_SESSION['auth']);
		$print = [
			'status' => 'success',
			'message' => 'Berhasil Mengubah',
			'data' => [
				'id' => $_POST['id'],
				'foto' => $rename
			]
		];
	}
}
if(isset($_POST)){
	$print['post'] = $_POST;
}
if(isset($_FILES)){
	$print['files'] = $_FILES;
}
echo json_encode($print, JSON_PRETTY_PRINT);
?>