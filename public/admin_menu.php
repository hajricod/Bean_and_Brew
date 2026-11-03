<?php
// public/admin_menu.php
require_once "../includes/config.php";
requireAdmin();

$message = "";
$error = "";

// Handle Deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    if (!$id) {
        http_response_code(400);
        exit('Invalid item ID.');
    }
    try {
        // Delete associated items in order_items first if they exist (though we don't have FK constraints enforced in SQLite by default unless enabled)
        // For simplicity, we just delete the item.
        $stmt = $pdo->prepare("DELETE FROM coffee_items WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Item deleted successfully.";
    } catch (PDOException $e) {
        $error = "Error deleting item: " . $e->getMessage();
    }
}

// Handle Adding New Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $ingredients = trim($_POST['ingredients'] ?? '');
    $calories = filter_var($_POST['calories'] ?? null, FILTER_VALIDATE_INT);
    
    // Image handling
    $image_name = $_POST['existing_image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/assets/images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_file_name = uniqid() . '.' . $file_ext;
        $target_file = $upload_dir . $new_file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_name = $new_file_name;
        } else {
            $error = "Failed to upload image.";
        }
    }

    if ($error === "") {
        if (empty($name) || empty($description) || empty($category) || $price <= 0 || empty($image_name)) {
            $error = "Name, description, category, a positive price, and an image are required.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO coffee_items (name, description, price, image, category, ingredients, calories) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $description, $price, $image_name, $category, $ingredients, $calories]);
                $message = "New item added successfully!";
            } catch (PDOException $e) {
                $error = "Error adding item: " . $e->getMessage();
            }
        }
    }
}

$items = getCoffeeItems($pdo);
$page_title = "Admin - Menu Manager | Bean & Brew";
include "../includes/header.php";
?>

<div class="container">
    <h2 style="margin-bottom: 30px;">☕ Menu Manager (Admin)</h2>

    <?php if ($message): ?>
        <div style="background: #c8e6c9; color: #2e7d32; padding: 15px; border-radius: 5px; margin-bottom: 20px;"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div style="background: #ffcdd2; color: #c62828; padding: 15px; border-radius: 5px; margin-bottom: 20px;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Add New Item Form -->
    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 40px;">
        <h3 style="margin-top: 0; color: var(--primary-color);">Add New Coffee Item</h3>
        <form action="admin_menu.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Name</label>
                    <input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Price ($)</label>
                    <input type="number" step="0.01" name="price" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Description</label>
                <textarea name="description" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; height: 80px;"></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Category</label>
                    <select name="category" required class="form-select">
                        <option value="Hot Coffee">Hot Coffee</option>
                        <option value="Iced Drinks">Iced Drinks</option>
                        <option value="Pastries">Pastries</option>
                    </select>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Calories (kcal)</label>
                    <input type="number" min="0" name="calories" class="form-control">
                </div>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Ingredients</label>
                <input type="text" name="ingredients" class="form-control" placeholder="Comma-separated ingredients">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Product Image</label>
                <input type="file" name="image" accept="image/*" required style="width: 100%;">
            </div>
            <button type="submit" style="background-color: #4e342e; color: white; padding: 12px 25px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">Add to Menu</button>
        </form>
    </div>

    <!-- Existing Items Table -->
    <h3>Current Menu Items</h3>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <thead style="background-color: #f8f8f8; color: var(--primary-color);">
                <tr>
                    <th style="padding: 15px; text-align: left;">Image</th>
                    <th style="padding: 15px; text-align: left;">Name</th>
                    <th style="padding: 15px; text-align: left;">Category</th>
                    <th style="padding: 15px; text-align: left;">Description</th>
                    <th style="padding: 15px; text-align: left;">Price</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">
                            <?php if (hasLocalCatalogImage($item['image'])): ?>
                                <img src="assets/images/<?php echo rawurlencode($item['image']); ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                            <?php else: ?>
                                <span style="font-size: 2rem;"><?php echo htmlspecialchars($item['image']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 15px; font-weight: bold;"><?php echo htmlspecialchars($item['name']); ?></td>
                        <td style="padding: 15px;"><?php echo htmlspecialchars($item['category']); ?></td>
                        <td style="padding: 15px; font-size: 0.9rem; color: #666;"><?php echo htmlspecialchars($item['description']); ?></td>
                        <td style="padding: 15px;">$<?php echo number_format($item['price'], 2); ?></td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="admin_edit_item.php?id=<?php echo $item['id']; ?>" style="display: inline-block; background: #795548; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 0.8rem; margin-right: 5px;">Edit</a>
                            <form action="admin_menu.php" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this item?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>">
                                <button type="submit" style="background: #e53935; color: white; padding: 5px 10px; border: 0; border-radius: 3px; font-size: 0.8rem; cursor: pointer;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: #999;">No items found in the menu.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
