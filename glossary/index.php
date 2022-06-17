<?php

require_once "MyPdo.php";
require_once "Word.php";
require_once "Translation.php";
include("config.php");

$result = [];
global $serverName, $userName, $password, $dbName;
$myPdo = new MyPDO("mysql:host=$serverName; dbname=$dbName", "$userName", "$password");
$stmt = $myPdo->prepare("SELECT title, language_id, word_id, description from translations");
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$result = $stmt->fetchAll();


if (isset($_FILES['file'])) {
    $file = fopen($_FILES["file"]["tmp_name"], "r");

    while (!feof($file)) {
        $pole = fgetcsv($file, null, ';');

        if ($pole[0] ??= null) {
            $word = new Word($myPdo, $pole[0]);
            $word->save();

            $englishTranslation = new Translation($myPdo, $pole[0], $pole[1], 2, $word);
            $englishTranslation->save();

            $slovakTranslation = new Translation($myPdo, $pole[2], $pole[3], 1, $word);
            $slovakTranslation->save();
        }
    }
    header("Location: index.php");
    fclose($file);
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
    <link rel="stylesheet" href="style.css">

    <title>Zadanie2</title>
</head>
<body>
<header>
    <h1>Glosár</h1>
</header>
<div class="container">
    <?php
    if (sizeof($result) > 0){
    ?>
    <table class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Slovo</th>
            <th scope="col"> Jazyk</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($result as $item) {
            echo '<tr>';
            echo "<td>" . $item['title'] . "</td>";
            if ($item['language_id'] == 2) {
                echo "<td>Anglický</td>";
            } else {
                echo "<td>Slovenský</td>";
            }
            ?>

            <td>
                <button class="btn btn-primarybtn btn-primary" onclick=deleteWord(<?php echo($item['word_id']) ?>)>
                    Delete
                </button>
            </td>
            <td>
                <button class="btn btn-primary" onclick=EditWord(<?php echo($item['word_id']) ?>)>Edit</button>
            </td>
            <?php
            echo "</tr>";
        }
        ?>
        </tbody>
        <?php } ?>
    </table>
    <div>
        <a href="upload.php">
            <button class="btn btn-primary btn-lg btn-block">Vytvoriť vlastný pojem</button>
        </a>
    </div>
    <div class="divko">
        <form id="upload-file" action="index.php" method="post" enctype="multipart/form-data">
            <div>
                <input id="file" type="file" name="file" class="form-control-file">
            </div>
            <input type="submit" class="btn btn-primary" value="upload">
        </form>
    </div>
    <script src="delete-button.js"></script>
    <script src="edit-button.js"></script>
</body>
</html>


