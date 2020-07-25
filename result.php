<?php
// セッションの開始
session_start();

// 無効なアクセスの拒否
if (empty($_SESSION) || empty($_POST)) {
	header('Location:start.php');
	exit();
}

//回答時間を算出
$end_time = microtime(true);
$start_time = $_SESSION['start_time'];
$time = round($end_time - $start_time, 2);

// セッション変数を変数に格納
$name = $_SESSION['name'];
$difficulty = $_SESSION['difficulty'];
$correct = $_SESSION['correct'];
$count = $_SESSION['count'];

// セッション変数の初期化
$_SESSION = [];
if (isset($_COOKIE['PHPSESSID'])) {
	setcookie('PHPSESSID', '', time() - 1800, '/');
}
session_destroy();

// 回答を変数に格納
$answer = html_entity_decode($_POST['answer']);

if ($correct === $answer) {
	$result = '正解です！';
} else {
	$result = '不正解です。。。';
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>間違い探し</title>
</head>
<body>
<h1>結果は...<?php echo $result ?></h1>
<h2>回答時間: <?php echo $time; ?>秒</h2>
<h2>リセット回数: <?php echo $count; ?>回</h2>
<a href="start.php"><button type="button">スタートページへ</button></a>
</body>
</html>
