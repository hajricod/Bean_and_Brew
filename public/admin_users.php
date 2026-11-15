<?php
require_once "../includes/config.php";
requireAdmin();

// Fetch all users
$stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY name");
$users = $stmt->fetchAll();

$page_title = "User Management | Bean & Brew";
include "../includes/header.php";
?>

<section class="container py-4 py-lg-5">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end gap-3 mb-4">
        <div>
            <p class="text-uppercase small fw-semibold text-secondary mb-2">Shop management</p>
            <h1 class="display-6 fw-bold mb-2">User Management</h1>
            <p class="text-secondary mb-0">View and manage registered customers and administrators.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-secondary">No users found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>#<?php echo $user['id']; ?></td>
                            <td class="fw-semibold"><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php 
                                    $roleClass = $user['role'] === 'admin' ? 'badge bg-danger' : 'badge bg-secondary';
                                ?>
                                <span class="<?php echo $roleClass; ?>"><?php echo ucfirst($user['role']); ?></span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="admin_edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-dark">Edit</a>
                                    <?php if ($user['role'] !== 'admin'): ?>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(<?php echo $user['id']; ?>)">Delete</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<script>
async function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        return;
    }

    try {
        const response = await fetch('admin_delete_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `user_id=${userId}&csrf_token=<?php echo $_SESSION['csrf_token']; ?>`
        });

        const result = await response.json();

        if (result.success) {
            location.reload();
        } else {
            alert('Error deleting user: ' + result.error);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while trying to delete the user.');
    }
}
</script>

<?php include "../includes/footer.php"; ?>
