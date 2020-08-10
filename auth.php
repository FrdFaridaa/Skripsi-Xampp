<?php 
if(!isset($_SESSION)){
	session_start();
}
include_once('config.php');
$data = new ino();
// $_POST['auth'] = '1-1161';
// $setName = 'rino3';
// $_POST['nama'] = $setName;
// $_POST['mail'] = $setName;
// $_POST['pass'] = $setName;
// $_POST['type'] = 'model';
// $_POST['register'] = '';
// $_POST['id'] = 0;
// $_POST['jk'] = 'l';
// $_POST['tglah'] = '1992-12-02';
// $_POST['tb'] = '180';
// $_POST['bb'] = '60';
// $_POST['us'] = '28';
// $_POST['jr'] = 'ikal';
// $_POST['wk'] = 'kuning';
// $_POST['hijab'] = '0';
$saveSesi = false;
$company = isset($_POST['type']) && strtolower($_POST['type']) === 'model' ? false : true;
$data->setTable('from', 'users')->setTable('select', [
		'id' => 'idUsers',
		'nama' => 'namaUsers',
		'mail' => 'mailUsers',
		'pass' => 'passUsers',
		'type' => 'typeUsers',
		'foto' => 'fotoUsers',
		'bio' => 'bioUsers',
		'token' => 'tokenUsers',
		'auth' => "CONCAT_WS('-', idUsers, tokenUsers)",
	]);
if(!$company){
	$data->setTable('from', 'model')->setTable('select', [
		'idm' => 'idModel',
		'user' => 'userModel',
		'jk' => 'jkModel',
		'tglah' => 'tglahModel',
		'tb' => 'tbModel',
		'bb' => 'bbModel',
		'us' => 'usModel',
		'jr' => 'jrModel',
		'tw' => 'twModel',
		'bs' => 'bsModel',
		'wk' => 'wkModel',
		'hijab' => 'hijabModel'
		])->setTable('where', 'userModel = idUsers');
}
if(isset($_POST['register'])){
	$postUsers = [
		'nama' => '',
		'mail' => '',
		'pass' => '',
		'type' => ['company', 'model']
	];
	if(!$company){
		$postUsers = [
			'nama' => '',
			'mail' => '',
			'pass' => '',
			'type' => ['company', 'model'],
			'jk' => '',
			'tglah' => '',
			'tb' => '',
			'bb' => '',
			'us' => '',
			'jr' => '',
			'tw' => '',
			'bs' => '',
			'wk' => '',
			'hijab' => '',
		];
	}
	if($data->ifPost($postUsers)){
		$data->cud([
			'post' => ['nama', 'mail', 'pass', 'type'],
			'not_exits' => [
				'mailUsers' => $_POST['mail']
			]
		]);
		if(!$company){
			$lastid = $data->lastid;
			$_POST['user'] = $_POST['id'];
			$data->cud([
				'id' => 'user',
				'table' => 'model',
				'post' => ['jk', 'tglah', 'tb', 'bb', 'us', 'jr', 'tw', 'bs', 'wk', 'hijab'],
				'push' => [
					'user' => $lastid
				],
				'not_exits' => [
					'userModel' => $lastid
				]
			]);
		}
	}
}elseif($data->ifPost([
	'mail' => '',
	'pass' => '',
	'type' => ['company', 'model']
])){
	$data->postWhere()->cud([
		'id' => 'mail',
		'push' => ['token' => rand(1000, 9999)],
		'set_query' => false
	]);
}elseif($data->ifPost([
	'bio' => ''
])){
	$data->cud([
		'post' => ['bio']
	]);
}elseif($data->ifPost([
	'auth' => ''
])){
	$saveSesi = true;
	$data->postWhere();
}else{
	$data->run = false;
}
$data->runTable(['id', 'nama', 'bio', 'token', 'type', 'foto']);
if($saveSesi){
	$data->setSession('auth', 'id')->setPrint('sesi', $_SESSION);
}
$data->json();
?>