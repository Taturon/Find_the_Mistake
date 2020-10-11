<?php

// db接続
require_once('db_connect.php');

$select = 'all';

if (isset($_POST['show_method'])) {
	$select = $_POST['show_method'];
}

switch ($select) {
	case 'difficult':
		$sql = "SELECT * FROM find_the_mistake WHERE difficulty = '難しい（漢字）' ORDER BY time LIMIT 10";
		break;
	case 'easy':
		$sql = "SELECT * FROM find_the_mistake WHERE difficulty = '易しい（絵文字）' ORDER BY time LIMIT 10";
		break;
	case 'all':
		$sql = 'SELECT * FROM find_the_mistake ORDER BY time LIMIT 10';
		break;
}
$stmt = $dbh->query($sql);
$players = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="style.css">
		<title>間違い探し ランキング</title>
	</head>
	<body>
		<h1>回答時間ランキング</h1>
		<form action="" method="POST">
			<select name="show_method" size="1">
				<option value="all" <?php if ($select === 'all') echo 'selected' ?>>全て</option>
				<option value="easy" <?php if ($select === 'easy') echo 'selected' ?>>易しい</option>
				<option value="difficult" <?php if ($select === 'difficult') echo 'selected' ?>>難しい</option>
			</select>
			<input type="submit" value="表示">
		</form>
		<div class="table">
			<table class="s-tbl">
				<thead>
					<tr>
						<th>順位</th>
						<th>名前</th>
						<th>難易度</th>
						<th>回答時間</th>
						<th>リセット回数</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($players as $key => $player): ?>
					<tr>
						<td><?= $key + 1; ?></td>
						<td><?= $player['name']; ?></td>
						<td><?= $player['difficulty']; ?></td>
						<td><?= $player['time']; ?></td>
						<td><?= $player['reset']; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<p><a href="start.php"><button type="button">スタートページへ</button></a></p>
	</body>
</html>
