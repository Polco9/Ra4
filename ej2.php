<?php

session_start();

$array10 = [10, 20, 30];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $position = $_POST["position"];
    $value = $_POST["num"];
}

if (isset($_POST["mod"])) {
    switch ($position) {
        case '0':
            $_SESSION['array'][$position] = $value;
            break;
        case '1':
            $_SESSION['array'][$position] = $value;
            break;
        case '2':
            $_SESSION['array'][$position] = $value;
            break;
    }
}elseif(isset($_POST["avg"])){
    $_SESSION["average"] = array_sum($_SESSION['array']) / count($_SESSION['array']);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ej2</title>
</head>
<body>
    <h1>Modify array saved in sesion</h1>
    <form method="post">
        <h3>Position to modify</h3>
        <select name="position" id="position">
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
        </select><br>
        New value<input type="number" value="num" name="num">
    </form>

    <p></p>
    <button type="submit" name="mod" value="mod">Modify</button>
    <button type="submit" name="avg" value="avb">Average</button>
    <button type="reset" name="reset" value="reset">Reset</button>

    <p>Array value: <?php foreach ($array10 as $key => $value) {
        echo $value . ", ";
    }?></p>
    <p>average: <?php echo isset($_SESSION["average"]) ? $_SESSION["average"] : '';?></p>
</body>
</html>