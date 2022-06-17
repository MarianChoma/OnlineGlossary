
<?php
require_once "MyPdo.php";
require_once "Word.php";
include("config.php");
require_once "Translation.php";

if (isset($_POST['en-title']) && isset($_POST['sk-title']) && isset($_POST['sk-description']) && isset($_POST['en-description'])) {
    global $serverName, $userName, $password, $dbName;
    $myPdo = new MyPDO("mysql:host=$serverName; dbname=$dbName", "$userName", "$password");
    $slovakTitle = $_POST['sk-title'];
    $englishTitle = $_POST['en-title'];
    $slovakDescription = $_POST['sk-description'];
    $englishDescription= $_POST['en-description'];

    $word = new Word($myPdo, $englishTitle);
    $word->save();

    $englishTranslation = new Translation($myPdo, $englishTitle, $englishDescription, 2, $word);
    $englishTranslation->save();

    $slovakTranslation = new Translation($myPdo, $slovakTitle, $slovakDescription, 1, $word);
    $slovakTranslation->save();

    header("Location: index.php");
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
    <title>Upload</title>
</head>
<body>
<div class="container">
    <form action="upload.php" method="post">
        <br>
        <label for="title">Slovenský termín</label>
        <textarea class="form-control" name="sk-title" id="sk-title"></textarea>

        <label for="title">Vysvetlenie</label>
        <textarea class="form-control" name="sk-description" id="sk-description"></textarea>

        <label for="title">English term</label>
        <textarea class="form-control" name="en-title" id="en-title"></textarea>

        <label for="title">Description</label>
        <textarea class="form-control" name="en-description" id="en-description"></textarea>
        <br>

        <div>
            <input type="submit" name = "submit" class="btn btn-primary" value="upload">
        </div>
    </form>


</div>
</body>
</html>
