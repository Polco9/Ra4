<?php
session_start();

// Inicializar el array si no está en la sesión
if (!isset($_SESSION['array'])) {
    $_SESSION['array'] = [10, 20, 30];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["mod"])) {
        $position = $_POST["position"];
        $value = $_POST["num"];
        
        // Modificar el valor en la posición especificada
        if (isset($_SESSION['array'][$position])) {
            $_SESSION['array'][$position] = $value;
        }
    } elseif (isset($_POST["avg"])) {
        // Calcular el valor medio
        $_SESSION["average"] = array_sum($_SESSION['array']) / count($_SESSION['array']);
    } elseif (isset($_POST["reset"])) {
        // Resetear el array a los valores iniciales
        $_SESSION['array'] = [10, 20, 30];
        unset($_SESSION["average"]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 2</title>
</head>
<body>
    <h1>Modify array saved in session</h1>
    <form method="post">
        <h3>Posicion a modificar</h3>
        <select name="position" id="position">
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
        </select><br>
        New value: <input type="number" name="num" required><br>
        <button type="submit" name="mod" value="mod">Modify</button>
        <button type="submit" name="avg" value="avg">Average</button>
        <button type="submit" name="reset" value="reset">Reset</button>
    </form>

    <p>Current array: <?php echo implode(", ", $_SESSION['array']); ?></p>
    <p>Average: <?php echo isset($_SESSION["average"]) ? $_SESSION["average"] : ''; ?></p>
</body>
</html>
