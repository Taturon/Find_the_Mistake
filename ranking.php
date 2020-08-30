<?php
// db接続
require_once('db_connect.php');

$select = 'all';

if (isset($_POST['show_method'])) {
	$select = $_POST['show_method'];
}

switch ($select) {
	case 'difficult':
		$sql = "SELECT * FROM find_the_mistake WHERE difficulty = '難しい(漢字)' ORDER BY time LIMIT 10";
		$stmt = $dbh->query($sql);
		$players = $stmt->fetchAll();
		break;
	case 'easy':
		$sql = "SELECT * FROM find_the_mistake WHERE difficulty = '易しい(絵文字)' ORDER BY time LIMIT 10";
		$stmt = $dbh->query($sql);
		$players = $stmt->fetchAll();
		break;
	case 'all':
	default:
		$sql = "SELECT * FROM find_the_mistake ORDER BY time LIMIT 10";
		$stmt = $dbh->query($sql);
		$players = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>間違い探し ランキング</title>
</head>
<body>
<h1>回答時間ランキング</h1>
<form action="" method="POST">
<select name="show_method" size="1">
<option value="all">全て</option>
<option value="easy">易しい(絵文字)</option>
<option value="difficult">難しい(漢字)</option>
</select>
<input type="submit" value="表示">
<table>
<tr>
<th>順位</th>
<th>名前</th>
<th>難易度</th>
<th>回答時間</th>
<th>リセット回数</th>
</tr>
<?php foreach ($players as $key => $player): ?>
<tr>
<td><?php echo $key + 1; ?></td>
<td><?php echo $player['name']; ?></td>
<td><?php echo $player['difficulty']; ?></td>
<td><?php echo $player['time']; ?></td>
<td><?php echo $player['reset']; ?></td>
</tr>
<?php endforeach; ?>
</table>
<p>
<a href="start.php"><button type="button">スタートページへ</button></a>
</p>
</body>
</html>
