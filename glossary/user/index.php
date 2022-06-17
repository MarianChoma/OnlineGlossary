<?php
$result = [];

if (isset($_GET['search'])) {
    include("../config.php");

    try {
        global $serverName, $userName, $password, $dbName;
        $stmt = null;
        $myPdo = new PDO("mysql:host=$serverName; dbname=$dbName", "$userName", "$password");
        if (isset($_GET['fulltext'])) {
            if(isset($_GET['en-pojem']) && isset($_GET['sk-pojem'])){
                $stmt = $myPdo->prepare("SELECT t1.title as searched, t2.title as result, t1.description as skDescription, t2.description as enDescription from translations t1 join translations t2 on t1.word_id = t2.word_id where t1.language_id = :language_id and ((t2.title like :search or t1.title like :search)COLLATE utf8mb4_general_ci or (t2.description like :search or t1.description like :search)COLLATE utf8mb4_general_ci)  and t1.id<>t2.id ");

            }
            else if(isset($_GET['en-pojem'])){
                $stmt = $myPdo->prepare("SELECT t1.title as searched, t2.title as result, t1.description as skDescription, t2.description as enDescription from translations t1 join translations t2 on t1.word_id = t2.word_id where t1.language_id = :language_id and (t2.title like :search COLLATE utf8mb4_general_ci or t2.description like :search COLLATE utf8mb4_general_ci)  and t1.id<>t2.id ");

            }else{
                $stmt = $myPdo->prepare("SELECT t1.title as searched, t2.title as result, t1.description as skDescription, t2.description as enDescription from translations t1 join translations t2 on t1.word_id = t2.word_id where t1.language_id = :language_id and (t1.title like :search COLLATE utf8mb4_general_ci or t1.description like :search COLLATE utf8mb4_general_ci)  and t1.id<>t2.id ");

            }
            $search = "%" . $_GET['search'] . "%";
        } else {
            $stmt = $myPdo->prepare("SELECT t1.title as searched, 
                                           t2.title as result,
                                           t1.description as skDescription,
                                           t2.description as enDescription 
                                           from translations 
                                           t1 join translations t2 on t1.word_id = t2.word_id
                                           where t1.language_id = :language_id 
                                           and t1.title like :search 
                                           COLLATE utf8mb4_general_ci 
                                           and t1.id<>t2.id ");
            $search = $_GET['search'] . "%";
        }
        if (isset($_GET['choose']) && $_GET['choose'] == 'vysvetlenie') {
            $one = 1;
            $stmt->bindParam(":language_id", $one, PDO::PARAM_INT);
        } else {
            $stmt->bindParam(":language_id", $_GET['language_id'], PDO::PARAM_INT);
        }
        $stmt->bindParam(":search", $search);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

    } catch (PDOException $exception) {
        echo "ERROR" . $exception->getMessage();
    }
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
    <title>User</title>
</head>
<body>
<header>
    <h1>Glosar</h1>
</header>
<div class="container">
    <form method="get" action="index.php">
        <div>
            <label for="search">Zadajte hľadaný termín</label>
            <input id="search" class="form-control" type="text" name="search">
        </div>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-primary">
                <input type="radio" name="choose" id="vysvetlenie" autocomplete="off" value="vysvetlenie"> Vysvetliť
                pojem
            </label>
            <label class="btn btn-secondary">
                <input type="radio" name="choose" id="preklad" autocomplete="off" value="preklad"> Preklad
            </label>
        </div>
        <div id="vBox">
            <input type="checkbox" id="sk-pojem" name="sk-pojem" value="sk-pojem">
            <label for="sk-pojem"> Vysvetliť pojem v slovenčine</label><br>
            <input type="checkbox" id="en-pojem" name="en-pojem" value="en-pojem">
            <label for="en-pojem"> Vysvetliť pojem v angličtine</label><br>
            <input type="checkbox" id="fulltext" name="fulltext" value="fulltext">
            <label for="en-pojem"> Fulltextové vyhľadávanie</label><br>
        </div>
        <div id="pBox">
            <label for="language">Preložiť z:</label>
            <select class="form-select" name="language_id" id="language">
                <option value="1">Slovenčina</option>
                <option value="2">Angličtina</option>
            </select>
        </div>
        <input id="searchButton" class="btn btn-primary" type="submit" value="hladaj">
    </form>
    <br>


    <?php
    if (sizeof($result) > 0){
    ?>
    <table class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <?php

            if (isset($_GET['sk-pojem']) || isset($_GET['en-pojem'])) {

                if (isset($_GET['sk-pojem'])) {
                    echo "<th scope='col'>SK</th>";
                    echo "<th scope='col'>Vysvetlenie</th>";
                }
                if (isset($_GET['en-pojem'])) {
                    echo "<th scope='col'>EN</th>";
                    echo "<th scope='col'>Description</th>";
                }
            } else {
                if ($_GET['language_id'] == 1) {
                    echo "<th scope='col'>SK</th>";
                    echo "<th scope='col'>EN</th>";
                } else {
                    echo "<th scope='col'>EN</th>";
                    echo "<th scope='col'>SK</th>";

                }
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($result as $item) {
            echo '<tr>';
            if (isset($_GET['sk-pojem']) || isset($_GET['en-pojem'])) {

                if (isset($_GET['sk-pojem'])) {
                    echo "<td>" . $item['searched'] . "</td>";
                    echo "<td>" . $item['skDescription'] . "</td>";
                }
                if (isset($_GET['en-pojem'])) {
                    echo "<td>" . $item['result'] . "</td>";
                    echo "<td>" . $item['enDescription'] . "</td>";
                }
            } else {
                echo "<td>" . $item['searched'] . "</td>";
                echo "<td>" . $item['result'] . "</td>";
            }

            echo '</tr>';
        }
        ?>
        </tbody>
        <?php } ?>
    </table>
</div>
</body>
<script src="main.js"></script>
</html>
