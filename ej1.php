<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $worker = $_POST["worker"];
    $product = $_POST["product"];
    $quantity = $_POST["quantity"];

    $_SESSION["worker"] = $_POST["worker"];

    if (isset($_POST["add"])) {
        switch ($product) {
            case 'milk':
                $_SESSION["quantitymilk"] += $quantity;
                break;
            case 'soft drink':
                $_SESSION["quantitysoftdrink"] += $quantity;
                break;
        }
    }elseif (isset($_POST["remove"])) {
        switch ($product) {
            case 'milk':
                if ($quantity > $_SESSION["quantitymilk"]) {
                    echo "No se pueden quitar mas productos de los que hay";
                }else {
                    $_SESSION["quantitymilk"] -= $quantity;
                }
                break;
            case 'soft drink':
                if ($quantity > $_SESSION["quantitysoftdrink"]) {
                    echo "No se pueden quitar mas productos de los que hay";
                }else {
                    $_SESSION["quantitysoftdrink"] -= $quantity;
                }
                break;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ej1</title>
    </head>
    <body>
        <h1>SUPERMARKET MANAGEMENT</h1>
            <p></p>
        <form id="cub1" method="POST">
            <br>
            Worker name: <input type="text" id="worker" name="worker" required><br>
            <h2>Choose Product:</h2>
            <select id="product" name="product">
                <option value="milk">milk</option>
                <option value="soft drink">soft drink</option>
            </select>
            <h2>Product quantity:</h2>
            <input type="number" id="quantity" name="quantity" required>

            <button type="submit" name="add" value="add">Add</button>
            <button type="submit" name="remove" value="remove">Remove</button>
            <button type="reset" name="reset" value="reset">Reset</button>
        </form>

        <h2>Inventary</h2>
        <p>Worker: <?php echo isset($worker) ? $worker : '';?></p>
        <p>units milk: <?php echo isset($_SESSION["quantitymilk"]) ? $_SESSION["quantitymilk"] : '';?></p>
        <p>units soft drinks <?php echo isset($_SESSION["quantitysoftdrink"]) ? $_SESSION["quantitysoftdrink"] : '';?></p>

    </body>
</html>