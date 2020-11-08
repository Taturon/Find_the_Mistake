<?php

// セッションの開始
session_start();

// 送信されたデータの検証
if (isset($_POST['name'], $_POST['difficulty'], $_POST['permission'])) {

	// 変数への代入
	$name = $_POST['name'];
	$difficulty = $_POST['difficulty'];
	$permission = $_POST['permission'];

	// 名前のバリデーション
	if (empty(trim($name))) {
		$error_msg = '名前に空白は無効です';
	} elseif (mb_strlen($name, 'UTF-8') > 10) {
		$error_msg = '名前は10字以内にしてください';
	} elseif ($name !== preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $name)) {
		$error_msg = '名前の前後に空白文字や制御文字を含めないで下さい';
	}

	// セッション変数への格納と問題表示ページへの遷移
	if (empty($error_msg)) {
		$_SESSION['name'] = $name;
		$_SESSION['difficulty'] = $difficulty;
		$_SESSION['permission'] = $permission;
		header('Location:find_the_mistake.php');
		exit();
	}
}

// セッションの放棄
$_SESSION = [];
setcookie(session_name(), '', time() - 1, '/');
session_destroy();

?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>間違い探し</title>
	  	<link rel="apple-touch-icon" type="image/png" href="/favicon/apple-touch-icon-180x180.png">
		<link rel="icon" type="image/png" href="/favicon/icon-192x192.png">
	</head>
	<body>
		<h1>間違い探し</h1>
		<p>20×20個のボタンが表示されるので、指定されたボタンを見つけ出して押しましょう！</p>
		<?php if (isset($error_msg)): ?>
		<hr>
		<ul>
			<li><?= $error_msg; ?></li>
		</ul>
		<hr>
		<?php endif; ?>
		<form method="POST">
			<label>名前
				<small> (10字以内)</small><br>
				<input type="text" name="name" required value="<?php if (isset($name)) echo $name;?>">
			</label>
			<p>
				<span>難易度</span><br>
				<label>
					<input type="radio" name="difficulty" value="易しい（絵文字）" required <?php if (empty($difficulty) || isset($difficulty) && $difficulty === '易しい（絵文字）') echo 'checked';?>>
					易しい（絵文字）
				</label>
				<label>
					<input type="radio" name="difficulty" value="難しい（漢字）" <?php if (isset($difficulty) && $difficulty === '難しい（漢字）') echo 'checked';?>>
					難しい（漢字）
				</label>
			</p>
			<p>
				<span>ランキングへの登録<small>（正解だった場合のみ）</small></span><br>
				<label>
					<input type="radio" name="permission" value="許可しない" required <?php if (empty($permission) || isset($permission) && $permission === '許可しない') echo 'checked';?>>
					許可しない
				</label>
				<label>
					<input type="radio" name="permission" value="許可する" <?php if (isset($permission) && $permission === '許可する') echo 'checked';?>>
					許可する
				</label>
			</p>
			<input type="submit" value="問題に挑戦!（時間計測が開始されます）">
		</form>
		<p>
			<button type="button" onclick="location.href='ranking.php'">ランキングページへ</button>
		</p>
	</body>
</html>
