<?php
$data = json_decode(file_get_contents('php://input'),true);

if (isset($data)) {
    include("config.php");

    try {
        global $serverName, $userName, $password, $dbName;

        $myPdo = new PDO("mysql:host=$serverName; dbname=$dbName", "$userName", "$password");
        $stmt = $myPdo->prepare("DELETE FROM translations WHERE word_id= :id");
        $stmt->bindParam(":id", $data['id'], PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $myPdo->prepare("DELETE FROM words WHERE id= :id");
        $stmt->bindParam(":id", $data['id'], PDO::PARAM_INT);
        $stmt->execute();

    } catch (PDOException $exception) {
        echo "ERROR" . $exception->getMessage();
    }

}
?>
