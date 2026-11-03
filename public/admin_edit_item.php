<?php
// public/admin_edit_item.php
require_once "../includes/config.php";
requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: admin_menu.php");
    exit;
}

$item_id = (int)$_GET['id'];

// Fetch current item details
$stmt = $pdo->prepare("SELECT * FROM coffee_items WHERE id = ?");
$stmt->execute([$item_id]);
$item = $stmt->fetch();

if (!$item) {
    die("Item not found.");
}

$message = "";
$error = "";

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $image_name = $_POST['existing_image'] ?? $item['image'];

    // Handle new image upload if provided
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
            $error = "Failed to upload new image.";
        }
    }

    if ($error === "") {
        if (empty($name) || empty($description) || $price <= 0 || empty($image_name)) {
            $error = "All fields are required.";
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE coffee_items SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
                $stmt->execute([$name, $description, $price, $image_name, $item_id]);
                $message = "Item updated successfully!";
                
                // Refresh current item data
                $stmt = $pdo->prepare("SELECT * FROM coffee_items WHERE id = ?");
                $stmt->execute([$item_id]);
                $item = $stmt->fetch();
            } catch (PDOException $e) {
                $error = "Error updating item: " . $e->getMessage();
            }
        }
    }
}

$page_title = "Edit Item | Admin";
include "../includes/header.php";
?>

<div class="container">
    <div style="max-width: 600px; margin: 0 auto;">
        <a href="admin_menu.php" style="display: inline-block; margin-bottom: 20px; color: var(--secondary-color); text-decoration: none; font-weight: bold;">&larr; Back to Menu Manager</a>
        
        <h2 style="color: var(--primary-color);">Edit Coffee Item</h2>

        <?php if ($message): ?>
            <div style="background: #c8e6c9; color: #2e7d32; padding: 15px; border-radius: 5px; margin-bottom: 20px;"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div style="background: #ffcdd2; color: #c62828; padding: 15px; border-radius: 5px; margin-bottom: 20px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <form action="admin_edit_item.php?id=<?php echo $item_id; ?>" method="POST" enctype="multipart/form-data">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Description</label>
                    <textarea name="description" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; height: 80px;"><?php echo htmlspecialchars($item['description']); ?></textarea>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Price ($)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $item['price']; ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Current Image</label>
                    <div style="margin-bottom: 10px;">
                        <img src="assets/images/<?php echo htmlspecialchars($item['image']); ?>" alt="Current" style="height: 80px; border-radius: 5px; border: 1px solid #ddd;">
                    </div>
                    <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($item['image']); ?>">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px; color: var(--secondary-color);">Upload New Image (optional)</label>
                    <input type="file" name="image" accept="image/*" style="width: 100%;">
                </div>
                <button type="submit" style="width: 100%; background-color: #4e342e; color: white; padding: 12px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">Update Item</button>
            </form>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
