<html>
<meta charset="utf-8">

<head>
<title>ミッション５－１</title>
</head>

<body>
<h3><center>掲示板</center></h3>
<hr>
<h4>コメントを投稿</h4>

<?php
//編集表示機能
if (isset($_POST['edit_button']))
{
	if (!empty($_POST['edit_number']) and !empty($_POST['edit_password']))
	{
	$dsn = 'dsn';
	$user = 'user';
	$password = 'password';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

	$edit_password = $_POST['edit_password'];
	$id = $_POST['edit_number'];
	$sql = "SELECT passw FROM mission5_1 WHERE id = $id";
	$sth = $pdo -> query($sql);
	$sql_pass =  $sth->fetch(PDO::FETCH_COLUMN);
	if ($edit_password==$sql_pass)
	{
		$sql = "SELECT * FROM mission5_1 WHERE id = $id";
		$sth = $pdo -> query($sql);
		$aryList = $sth -> fetch(PDO::FETCH_ASSOC);
		$edit_postnumber=$id;
		$edit_name=$aryList['name'];
		$edit_comment=$aryList['comment'];
		$edit_passwordnew=$aryList['passw'];
		}
		else {
				echo "wrong password";
		}
}
}



?>

<form method="POST" action="">
名前：<input type="text" charset="utf-8" name="input_name"
value="<?php

if (!empty($edit_name)) {
	echo "$edit_name";
	}
?>"><br>
コメント：<input type="text" charset="utf-8" name="input_comment"
value="<?php

if (!empty($edit_comment)) {
		echo "$edit_comment";}
?>"><br>
パスワード：<input type="text" name="input_password" value="<?php
if (!empty($edit_passwordnew)) {
	echo "$edit_passwordnew";}
  ?>"><br>
<input type="submit" name="submit_button" value="送信">
<input type="hidden" name="edit_hiddennumber" value="<?php
if (isset($_POST['edit_button'])) {
	echo "$edit_postnumber";}
  ?>">
</form>

<hr>
<form method="POST" action="">
<h4>コメントを削除</h4>
削除したいコメント番号：<input type="number" min="0" name="delete_value" value=""><br>
パスワード：<input type="text" name="delete_password"><br>
<input type="submit" name="delete_button" value="削除">
</form>
<hr>

<form method="POST" action="">
<h4>コメントを編集</h4>
編集したいコメント番号：<input type="number" min="0" name="edit_number" value=""><br>
パスワード：<input type="text" name="edit_password" value=""><br>
<input type="submit" name="edit_button" value="編集">
</form>
<hr>
<h3><center>掲示板</center></h3>
<hr>


<body>

<?php
$dsn = 'mysql:dbname=tb210588db;host=localhost';
$user = 'user';
$password = 'password';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

if (isset($_POST['edit_button']))
{
if (empty($_POST['edit_number']) or empty($_POST['edit_password']))
 {
echo "enter comment and password";
}}


//削除

if (isset($_POST['delete_button']))
{
	//エラー表示
	if (empty($_POST['delete_value']) or empty($_POST['delete_password']))
	{
	 echo "エラーが出ました。<br><br><br>";
	}
	else {
	$delete_password = $_POST['delete_password'];
	$id = $_POST['delete_value'];
	$sql = "SELECT passw FROM mission5_1 WHERE id = $id";
	$sth = $pdo -> query($sql);
	$sql_pass =  $sth->fetch(PDO::FETCH_COLUMN);

	if ($delete_password == $sql_pass)
	{
		$sql = 'delete from mission5_1 where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
	}
}
}

//投稿編集機能
if (isset($_POST['submit_button']))
{
//コメントか名前が空な場合はエラー表示
if (empty($_POST['input_comment']))
{
echo "エラーが出ました。コメントをご入力ください。";
}
elseif(empty($_POST['input_name']))
{
echo "エラーが出ました。名前をご入力ください。";
}
//以上の条件OKなら投稿する
//編集edit
elseif (!empty($_POST['edit_hiddennumber'])) {
	 //変更する投稿番号
	$id=$_POST['edit_hiddennumber'];
	$name = $_POST['input_name'];
	$comment = $_POST['input_comment'];
	$passw = $_POST['input_password'];
	$tanggal = DATE("Y-m-d"); //変更したい名前、変更したいコメントは自分で決めること
	$sql = 'update mission5_1 set name=:name,comment=:comment,passw=:passw, tanggal=:tanggal where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':passw', $passw, PDO::PARAM_STR);
	$stmt -> bindParam(':tanggal', $tanggal, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt -> execute();
}
else
  {
  //comment biasa
  $sql = $pdo -> prepare("INSERT INTO mission5_1 (name, comment, passw,tanggal) VALUES (:name, :comment, :passw, :tanggal)");
  $sql -> bindParam(':name', $name, PDO::PARAM_STR);
  $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
  $sql -> bindParam(':passw', $passw, PDO::PARAM_STR);
	$sql -> bindParam(':tanggal', $tanggal, PDO::PARAM_STR);
  $name = $_POST['input_name'];
  $comment = $_POST['input_comment'];
  $passw = $_POST['input_password'];
	$tanggal = DATE("Y-m-d");
  $sql -> execute();
  }
}


//show comments
$sql = 'SELECT * FROM mission5_1';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
//$rowの中にはテーブルのカラム名が入る
echo $row['id'].'. ';
echo $row['name'].'さん'."<br>";
echo $row['comment'].'<br>';
echo $row['tanggal'].'<br>';
echo "<hr>";
}

?>
</html>
