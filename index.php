<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Blog</title>
     <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h1>My Blog</h1>



<p>
    Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!
</p>

<a href="create.php">Create New Post</a> |
<a href="logout.php">Logout</a>

<hr>

<h2>Posts</h2>

<?php if ($result->num_rows > 0): ?>

    <?php while ($post = $result->fetch_assoc()): ?>

        <h3><?php echo htmlspecialchars($post["title"]); ?></h3>

        <p><?php echo nl2br(htmlspecialchars($post["content"])); ?></p>

        <small>
            Created: <?php echo $post["created_at"]; ?>
        </small>
        <br><br>

<a href="edit.php?id=<?php echo $post["id"]; ?>">Edit</a>

<a href="delete.php?id=<?php echo $post["id"]; ?>"
   onclick="return confirm('Are you sure you want to delete this post?');">
   Delete
</a>

        <hr>

    <?php endwhile; ?>

<?php else: ?>

    <p>No posts available yet.</p>

<?php endif; ?>
</div>
</body>
</html>