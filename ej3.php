<?php
session_start();

// Initialize the shopping list if it doesn't exist
if (!isset($_SESSION['shopping_list'])) {
    $_SESSION['shopping_list'] = [];
}

// Function to calculate the total cost of the list
function calculateTotalCost() {
    $total = 0;
    foreach ($_SESSION['shopping_list'] as $item) {
        $total += $item['total_cost'];
    }
    return $total;
}

// Add new item
if (isset($_POST['add'])) {
    $name = htmlspecialchars($_POST['name']);
    $quantity = floatval($_POST['quantity']);
    $price = floatval($_POST['price']);
    
    if (!empty($name) && $quantity > 0 && $price > 0) {
        $total_cost = $quantity * $price;
        $_SESSION['shopping_list'][] = [
            'name' => $name,
            'quantity' => $quantity,
            'price' => $price,
            'total_cost' => $total_cost
        ];
    }
}

// Update existing item by name
if (isset($_POST['update_by_name'])) {
    $name = htmlspecialchars($_POST['name']);
    $quantity = floatval($_POST['quantity']);
    $price = floatval($_POST['price']);
    
    if (!empty($name) && $quantity > 0 && $price > 0) {
        $found = false;
        
        foreach ($_SESSION['shopping_list'] as $id => $item) {
            if ($item['name'] === $name) {
                $_SESSION['shopping_list'][$id]['quantity'] = $quantity;
                $_SESSION['shopping_list'][$id]['price'] = $price;
                $_SESSION['shopping_list'][$id]['total_cost'] = $quantity * $price;
                $found = true;
                break;
            }
        }
        
        // If not found, add as new
        if (!$found) {
            $total_cost = $quantity * $price;
            $_SESSION['shopping_list'][] = [
                'name' => $name,
                'quantity' => $quantity,
                'price' => $price,
                'total_cost' => $total_cost
            ];
        }
    }
}

// Reset (delete all items)
if (isset($_POST['reset'])) {
    $_SESSION['shopping_list'] = [];
}

// Edit item
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $quantity = floatval($_POST['quantity']);
    $price = floatval($_POST['price']);
    
    if (!empty($name) && $quantity > 0 && $price > 0) {
        $_SESSION['shopping_list'][$id]['name'] = $name;
        $_SESSION['shopping_list'][$id]['quantity'] = $quantity;
        $_SESSION['shopping_list'][$id]['price'] = $price;
        $_SESSION['shopping_list'][$id]['total_cost'] = $quantity * $price;
    }
}

// Delete item
if (isset($_POST['delete'])) {
    $id = intval($_POST['delete_id']);
    if (isset($_SESSION['shopping_list'][$id])) {
        array_splice($_SESSION['shopping_list'], $id, 1);
    }
}

// Get item to edit
$edit_item = null;
$edit_id = null;
if (isset($_POST['edit'])) {
    $edit_id = intval($_POST['edit_id']);
    if (isset($_SESSION['shopping_list'][$edit_id])) {
        $edit_item = $_SESSION['shopping_list'][$edit_id];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping List Manager</title>
</head>
<body>
    <div class="container">
        <h1>Shopping List Manager</h1>
        
        <form method="post" action="">
            <?php if ($edit_item): ?>
                <h3>Edit Item</h3>
                <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
            <?php else: ?>
                <h3>Add/Update Item</h3>
            <?php endif; ?>
            
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $edit_item ? $edit_item['name'] : ''; ?>" required>
            </div>
            
            <div>
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="0.01" step="0.01" value="<?php echo $edit_item ? $edit_item['quantity'] : ''; ?>" required>
            </div>
            
            <div>
                <label for="price">Price (€):</label>
                <input type="number" id="price" name="price" min="0.01" step="0.01" value="<?php echo $edit_item ? $edit_item['price'] : ''; ?>" required>
            </div>
            
            <div style="margin-top: 10px;" class="button-group">
                <?php if ($edit_item): ?>
                    <button type="submit" name="update" class="edit-btn">Update</button>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>" style="margin-left: 10px;">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="add">Add</button>
                    <button type="submit" name="update_by_name" class="edit-btn">Update</button>
                    <button type="submit" name="reset" class="reset-btn" onclick="return confirm('Are you sure you want to delete ALL items?');">Reset</button>
                <?php endif; ?>
            </div>
        </form>
        
        <h3>Shopping List</h3>
        <?php if (empty($_SESSION['shopping_list'])): ?>
            <p>There are no items in the shopping list.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price (€)</th>
                        <th>Total Cost (€)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['shopping_list'] as $id => $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo number_format($item['quantity'], 2); ?></td>
                            <td><?php echo number_format($item['price'], 2); ?> €</td>
                            <td><?php echo number_format($item['total_cost'], 2); ?> €</td>
                            <td class="actions">
                                <form method="post" action="" style="display: inline; margin: 0; padding: 0; background: none;">
                                    <input type="hidden" name="edit_id" value="<?php echo $id; ?>">
                                    <button type="submit" name="edit" class="edit-btn">Edit</button>
                                </form>
                                <form method="post" action="" style="display: inline; margin: 0; padding: 0; background: none;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    <input type="hidden" name="delete_id" value="<?php echo $id; ?>">
                                    <button type="submit" name="delete" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="total">
                Total Cost of the List: <?php echo number_format(calculateTotalCost(), 2); ?> €
            </div>
        <?php endif; ?>
    </div>
</body>
</html>