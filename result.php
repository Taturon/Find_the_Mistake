<?php

// セッションの開始
session_start();

// 無効なアクセスの拒否
if (empty($_SESSION) || empty($_POST)) {
	header('Location:start.php');
	exit();
}

// 回答時間を算出
$end_time = microtime(true);
$start_time = $_SESSION['start_time'];
$time = sprintf('%05.2f', $end_time - $start_time) . '秒';

// 回答を変数に格納
$answer = $_POST['answer'];

// セッション変数を変数に格納
$name = $_SESSION['name'];
$difficulty = $_SESSION['difficulty'];
$permission = $_SESSION['permission'];
$correct = $_SESSION['correct'];
$count = sprintf('%02d', $_SESSION['count']) . '回';

// セッションの放棄
$_SESSION = [];
setcookie(session_name(), '', time() - 1, '/');
session_destroy();

// 回答時間が100秒以上の場合は値を上書き
if ($time > 100) {
	$time = '100秒以上';
}

// リセット回数が100回以上の場合は値を上書き
if ($count > 100) {
	$count = '100回以上';
}

// 正解・不正解によるメッセージの分岐
if (html_entity_decode($correct) === $answer) {
	$result = '正解です！';
} else {
	$result = '不正解です。。。';
}

// 正解かつ許可されていた場合のみDBに登録
if ($result === '正解です！' && $permission === '許可する') {

	// db接続
	require_once('db_connect.php');

	// 新規登録処理
	$sql = 'INSERT INTO rankings (name, difficulty, time, reset) VALUES (?, ?, ?, ?)';
	$stmt = $dbh->prepare($sql);
	$stmt->execute([$name, $difficulty, $time, $count]);
}

?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>間違い探し</title>
	</head>
	<body>
		<h1><small>結果は...<?= $result ?></small></h1>
		<h2><small>難易度: <?= $difficulty; ?></small></h2>
		<h2><small>回答時間: <?= $time; ?></small></h2>
		<h2><small>リセット回数: <?= $count; ?></small></h2>
		<button type="button" onclick="location.href='start.php'">スタートページへ</button>
		<button type="button" onclick="location.href='ranking.php'">ランキングページへ</button>
	</body>
</html>
