<?php
require_once "../includes/config.php";
requireAdmin();

$user_id = $_GET['id'] ?? null;
$user = null;

if ($user_id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
}

if (!$user) {
    die("User not found.");
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Invalid CSRF token.";
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'user';

        if (empty($name)) $errors[] = "Name is required.";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "A valid email is required.";

        if (empty($errors)) {
            try {
                $query = "UPDATE users SET name = ?, email = ?, role = ?";
                $params = [$name, $email, $role];

                if (!empty($_POST['password'])) {
                    $query .= ", password = ?";
                    $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }

                $query .= " WHERE id = ?";
                $params[] = $user_id;

                $stmt = $pdo->prepare($query);
                $stmt->execute($params);
                $success = true;
            } catch (PDOException $e) {
                $errors[] = "Update failed: " . $e->getMessage();
            }
        }
    }
}

$page_title = "Edit User | Bean & Brew";
include "../includes/header.php";
?>

<section class="container py-4 py-lg-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="admin_users.php">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit User</li>
            </ol>
        </nav>
        <h1 class="display-6 fw-bold">Edit User</h1>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            User updated successfully! <a href="admin_users.php">Back to list</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-6">
                <article class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        <form action="" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Full Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>

                            <div class="mb-4">
                                <label for="role" class="form-label fw-semibold">User Role</label>
                                <select name="role" id="role" class="form-select">
                                    <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>Customer</option>
                                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Administrator</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">New Password (leave blank to keep current)</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password">
                                <div class="form-text">If you leave this empty, the current password will not be changed.</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-dark rounded-pill px-4">Save Changes</button>
                                <a href="admin_users.php" class="btn btn-light rounded-pill">Cancel</a>
                            </div>
                        </form>
                    </div>
                </article>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php include "../includes/footer.php"; ?>
