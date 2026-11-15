<?php
require_once "../includes/config.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$page_title = "My Profile | Bean & Brew";
include "../includes/header.php";

// Fetch full user details from the database to ensure we have the latest data
$stmt = $pdo->prepare("SELECT id, name, email, role, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    // This shouldn't happen if the session is valid, but as a safety measure:
    unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email'], $_SESSION['user_role']);
    header("Location: login.php");
    exit;
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h2 class="h4 mb-0">My Profile</h2>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-warning d-inline-flex align-items-center justify-content-center text-white fw-bold mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                        </div>
                        <h3 class="h5"><?php echo htmlspecialchars($user['name']); ?></h3>
                        <span class="badge rounded-pill text-bg-secondary"><?php echo htmlspecialchars(ucfirst($user['role'])); ?> Account</span>
                    </div>

                    <hr class="my-4">

                    <div class="row mb-3">
                        <div class="col-sm-4 text-secondary">Email Address</div>
                        <div class="col-sm-8 fw-semibold"><?php echo htmlspecialchars($user['email']); ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-secondary">Member Since</div>
                        <div class="col-sm-8"><?php echo date('F j, Y', strtotime($user['created_at'])); ?></div>
                    </div>

                    <?php if ($user['role'] === 'admin'): ?>
                    <div class="alert alert-info mt-4">
                        <i class="bi bi-shield-lock me-2"></i> You are logged in as an administrator. Access the 
                        <a href="admin_dashboard.php" class="alert-link">Admin Dashboard</a> to manage the shop.
                    </div>
                    <?php endif; ?>

                    <div class="d-grid gap-2 mt-5">
                        <a href="index.php" class="btn btn-outline-secondary">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include "../includes/footer.php";
?>
