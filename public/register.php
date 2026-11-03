<?php
// public/register.php
require_once "../includes/config.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email is already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            try {
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $hashed_password]);
                $success = "Registration successful! You can now <a href='login.php'>login here</a>.";
            } catch (PDOException $e) {
                $error = "An error occurred. Please try again.";
            }
        }
    }
}

$page_title = "Register | Bean & Brew";
include "../includes/header.php";
?>

<section class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">
        <h1 class="h2 text-center text-dark mb-4">Create Account</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success" role="alert"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST" class="vstack gap-3">
            <div>
                <label for="name" class="form-label fw-semibold">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" autocomplete="name" required>
            </div>
            <div>
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" autocomplete="email" required>
            </div>
            <div>
                <label for="password" class="form-label fw-semibold">Password</label>
                <input type="password" id="password" name="password" class="form-control" autocomplete="new-password" required>
            </div>
            <div>
                <label for="confirm_password" class="form-label fw-semibold">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" autocomplete="new-password" required>
            </div>
            <button type="submit" class="btn btn-warning rounded-pill fw-semibold w-100">Register</button>
        </form>
        
        <p class="text-center text-secondary mt-4 mb-0">
            Already have an account? <a href="login.php" class="link-dark fw-semibold">Login here</a>
        </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "../includes/footer.php"; ?>
