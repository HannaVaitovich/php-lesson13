<?php

$host = 'localhost';    //127.0.0.1
$db = 'php-13-homework';
$user = 'root';
$password = "alex1983";


$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);

$button = 'Добавить';

if (isset($_GET['id'])) {
$id = $_GET['id'];

//Izmenit' 
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    $button = 'Сохранить';

    $select = "SELECT * FROM `tasks` WHERE `id` = ?";
    $statement = $pdo->prepare($select);
    $statement->execute([$id]);
    $description = $statement->fetch()['description'];
}

//Udalit'
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $select = "DELETE FROM `tasks` WHERE `id` = ?";
    $statement = $pdo->prepare($select);
    $statement->execute([$id]);
    $description = $statement->fetch()['description'];
}


//izmenit' status
if (isset($_GET['action']) && $_GET['action'] == 'done') {
    $select = "UPDATE `tasks` SET `is_done` = 1 WHERE `id` = ?";
    $statement = $pdo->prepare($select);
    $statement->execute([$id]);
    $description = $statement->fetch()['description'];
}

}

if (isset($_POST['action']) && empty($_POST['id'])) {
    $description = $_POST['description'];

    $select = "INSERT INTO `tasks` (`description`, `is_done`, `date_added`) VALUES (?, ?, CURRENT_TIMESTAMP)";
    $statement = $pdo->prepare($select);
    $statement->execute([$description, 0]);
}elseif (isset($_POST['save']) && $_POST['save'] == 'saving') {
    $description = $_POST['description'];
    $id = $_POST['id'];

    $select = "UPDATE `tasks` SET `description`= ? WHERE `id` = ? LIMIT 1";
    $statement = $pdo->prepare($select);
    $statement->execute([$description, $id]);
}

$select = "SELECT * FROM `tasks`";
$statement = $pdo->prepare($select);
$statement->execute();

$results = [];
while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $results[] = $row;
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<title>PHP: Lesson 13</title>
</head>
<body>
<style type="text/css">
* {
	box-sizing: border-box;
}
table {
	border-collapse: collapse;
	margin: 20px 0 0;
	padding: 0;
	background-color: #cccccc;
	font-family: sans-serif;
}
table tr td,
table tr th {
	border: 1px solid black;
	padding: 5px;
}
</style>
<h1>Список дел на сегодня</h1>

<form method="POST" action="index.php">
    <input type="hidden" name="id" value="<?= $_GET ? $_GET['id'] : "" ?>">
	<input type="hidden" name="save" value="saving">
	  <input type="text" name="description" placeholder="Описание задачи" value="<?= $_GET ? $description : "" ?>">
  	<button type="submit" name="action" value="save"><?php echo $button; ?></button>
</form>

<table>
	<tr>
		<th>Описание задачи</th>
		<th>Дата добавления</th>
		<th>Статус</th>
		<th>Action</th>
	</tr>
	<?php foreach ($results as $row) { ?>
	<tr>
    <td>
    	<?= $row['description']; ?>
    </td>
    <td>
    	<?= $row['date_added']; ?>
    </td>
    <td>
    	<?php if ($row['is_done'] == 1) {
            echo 'Выполнено';
            }elseif ($row['is_done'] == 0) {
                echo 'В процессе';
            }
            ?>
    </td>
    <td>
        <a href="index.php?id=<?= $row['id']; ?>&action=edit">Изменить</a>  
        <a href="index.php?id=<?= $row['id']; ?>&action=done">Выполнить</a>  
        <a href="index.php?id=<?= $row['id']; ?>&action=delete">Удалить</a>
    </td>  
	</tr>
	<?php } ?>
</table>


</body>
</html>
