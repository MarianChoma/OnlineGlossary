<?php

try {
    include("config.php");

    global $serverName, $userName, $password, $dbName;
    $myPdo = new PDO("mysql:host=$serverName; dbname=$dbName", "$userName", "$password");
    $id = $_COOKIE['id'];

    $stmt = $myPdo->prepare("SELECT * from translations where word_id like :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();

    if (isset($_POST['submit'])) {
        $stmt = $myPdo->prepare("UPDATE translations SET title= ?, description= ? WHERE word_id like ? and language_id like 1");
        $stmt->execute([$_POST['sk-nazov'], $_POST['sk-popis'], $id]);

        $stmt = $myPdo->prepare("UPDATE translations SET title= ?, description= ? WHERE word_id like ? and language_id like 2");
        $stmt->execute([$_POST['en-title'], $_POST['en-description'], $id]);

        $stmt = $myPdo->prepare("UPDATE words SET title= ? WHERE id like ?");
        $stmt->execute([$_POST['en-title'], $id]);
        header("Location: index.php");
    }
} catch (PDOException $exception) {
    echo "ERROR" . $exception->getMessage();
}

?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Edit</title>
</head>
<body>
<div class="container">
    <?php
    if (sizeof($result) > 0) {
        ?>

        <form action="edit.php" method="post">
            <br>
            <label for="sk-nazov">Slovenský názov</label>
            <textarea id="sk-nazov" class="form-control"
                      name="sk-nazov"><?php echo $result[1]['title'] ?></textarea><br>

            <label for="sk-popis">Slovenský popis</label>
            <textarea id="sk-popis" class="form-control"
                      name="sk-popis"><?php echo $result[1]['description'] ?></textarea><br>

            <label for="en-title">Anglický názov</label>
            <textarea id="en-title" class="form-control"
                      name="en-title"><?php echo $result[0]['title'] ?></textarea><br>

            <label for="en-description">Anglický popis</label>
            <textarea id="en-description" class="form-control"
                      name="en-description"><?php echo $result[0]['description'] ?></textarea><br>
            <input type="submit" class="btn btn-primary" name="submit" id="submit">
        </form>
    <?php } ?>
</div>



</body>
</html>
