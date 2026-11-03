<?php
// public/login.php
require_once "../includes/config.php";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'] ?? 'user';
            session_regenerate_id(true);
            
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}

$page_title = "Login | Bean & Brew";
include "../includes/header.php";
?>

<section class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">
        <h1 class="h2 text-center text-dark mb-4">Login</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="vstack gap-3">
            <div>
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" autocomplete="email" required>
            </div>
            <div>
                <label for="password" class="form-label fw-semibold">Password</label>
                <input type="password" name="password" id="password" class="form-control" autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn btn-warning rounded-pill fw-semibold w-100">Login</button>
        </form>
        
        <p class="text-center text-secondary mt-4 mb-0">
            Don't have an account? <a href="register.php" class="link-dark fw-semibold">Register here</a>
        </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "../includes/footer.php"; ?>
