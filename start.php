<?php
// セッションの開始
session_start();

// エラーメッセージがある場合は取得
if (isset($_SESSION['error_msg']) && isset($_SESSION['post_name'])) {
	$error_msg = $_SESSION['error_msg'];
	$post_name = $_SESSION['post_name'];
}

// セッション変数の初期化
$_SESSION = [];
if (isset($_COOKIE['PHPSESSID'])) {
	setcookie('PHPSESSID', '', time() - 1800, '/');
}
session_destroy();

if (!empty($_POST)) {

	// 変数への代入
	$name = $_POST['name'];

	// 名前のバリデーション
	if (empty(trim($name))) {
		$error_msgs[] = '名前を入力してください(空白は無効です)';
	} elseif (mb_strlen($name) > 10) {
		$error_msgs[] = '名前は10字以内にしてください';
	} elseif ($name !== preg_replace('/\A[\x00\s]++|[\x00\s]++\z/u', '', $name)) {
		$error_msgs[] = '名前の前後に空白を入れないでください';
	}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>間違い探し</title>
</head>
<body>
<h1>間違い探し</h1>
<p>20×20個のボタンが表示されるので、指定されたボタンを見つけ出して押しましょう！</p>
<?php if (isset($error_msg)): ?>
<hr><ul>
<li><?php echo $error_msg; ?></li>
</ul><hr>
<?php endif; ?>
<form action="find_the_mistake.php" method="POST">
<label>名前<small> (10字以内)</small><br><input type="text" name="name" required value="<?php
if (isset($post_name)) echo $post_name;
?>"></label>
<p>
<span>難易度</span><br>
<label><input type="radio" name="difficulty" value="易しい(絵文字)" checked>易しい(絵文字)</label>
<label><input type="radio" name="difficulty" value="難しい(漢字)">難しい(漢字)</label>
</p>
<input type="submit" value="送信">
</form>
</body>
</html>
