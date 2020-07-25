<?php
// セッションの開始
session_start();

// リセット回数の計測開始
if (isset($_SESSION['count'])) {
	$_SESSION['count']++;
} else {
	$_SESSION['count'] = 0;
}

// 送信されたデータの検証
if (isset($_POST['name'])) {

	// 変数への代入
	$_SESSION['post_name'] = $name = $_POST['name'];

	// 名前のバリデーション
	if (empty(trim($name))) {
		$_SESSION['error_msg'] = '名前を入力してください(空白は無効です)';
	} elseif (mb_strlen($name) > 10) {
		$_SESSION['error_msg'] = '名前は10字以内にしてください';
	} elseif ($name !== preg_replace('/\A[\x00\s]++|[\x00\s]++\z/u', '', $name)) {
		$_SESSION['error_msg'] = '名前の前後に空白を入れないでください';
	}

	if (isset($_SESSION['error_msg'])) {
		header('Location:start.php');
	}
}

// セッション変数への代入
if (isset($_POST['name']) && isset($_POST['difficulty'])) {
	$_SESSION['name'] = $_POST['name'];
	$_SESSION['difficulty'] = $_POST['difficulty'];
}

// 無効なアクセスの拒否
if (empty($_SESSION)) {
	header('Location:start.php');
	exit();
}

// 開始時刻を記録
$_SESSION['start_time'] = microtime(true);

// ターゲット配列の設定
if ($_SESSION['difficulty'] === '難しい(漢字)') {
	$chars = [
		['猫', '描'],
		['犬', '大'],
		['幸', '辛'],
		['白', '臼'],
		['矢', '失'],
		['力', '刀'],
		['防', '妨'],
		['土', '士'],
		['卵', '卯'],
		['巨', '臣'],
		['寒', '塞'],
		['旅', '族'],
		['車', '東'],
		['釘', '針']
	];
} else {
	$chars = [
		['&#x1f415;', '&#x1f408;'],
		['&#x1f405;', '&#x1f406;'],
		['&#x1f98e;', '&#x1f40d;'],
		['&#x1f433;', '&#x1f42c;'],
		['&#x1f339;', '&#x1f337;'],
		['&#x1f34a;', '&#x1f34b;'],
		['&#x1f34e;', '&#x1f351;'],
		['&#x1f955;', '&#x1f336;'],
		['&#x1f96f;', '&#x1f95e;'],
		['&#x1f358;', '&#x1f359;'],
		['&#x1f341;', '&#x1f342;'],
		['&#x1f332;', '&#x1f333;'],
		['&#x1f47b;', '&#x1f47d;'],
		['&#x1f396;', '&#x1f3c5;']
	];
}

// ターゲット配列のシャッフル
shuffle($chars);
for ($i = 0; $i < count($chars); $i++) {
	shuffle($chars[$i]);
}

// 正解と選択対象配列を設定
$correct = html_entity_decode($chars[0][0]);
$_SESSION['correct'] = $correct;
for ($j = 0; $j <= 19; $j++) {
	for ($k = 0; $k <= 19; $k++) {
		$targets[$j][$k] = $chars[0][1];
	}
}

// 正解のターゲットを選択対象配列に1つだけ挿入
$key = range(0, 9);
$key1 = array_rand($key);
$key2 = array_rand($key);
$targets[$key1][$key2] = $correct;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>間違い探し</title>
</head>
<body>
<h1><?php echo html_entity_decode($correct); ?>を見つけよう！</h1>
<p>
<small>難易度: <?php echo $_SESSION['difficulty']; ?></small>
<a href="find_the_mistake.php"><button type="button">分かるか！（リセット）</button></a>
<a href="start.php"><button type="button">スタートページへ</button></a>
</p>
<table>
<form action="result.php" method="POST">
<?php foreach ($targets as $target): ?>
<tr>
<?php for ($l = 0; $l < count($target); $l++): ?>
<td><input type="submit" name="answer" value="<?php echo html_entity_decode($target[$l]); ?>"></td>
<?php endfor; ?>
</tr>
<?php endforeach; ?>
</form>
</table>
</body>
</html>