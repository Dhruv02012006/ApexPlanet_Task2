<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET["id"]);

$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Post not found.");
}

$post = $result->fetch_assoc();
$stmt->close();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    if (empty($title) || empty($content)) {
        $message = "Please fill in all fields.";
    } else {

        $stmt = $conn->prepare(
            "UPDATE posts SET title = ?, content = ? WHERE id = ?"
        );

        $stmt->bind_param("ssi", $title, $content, $id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $message = "Error updating post.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
     <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

<div class="card">

<h2>Edit Post</h2>

<?php if ($message != ""): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST">

    <label>Title:</label><br>
    <input
        type="text"
        name="title"
        value="<?php echo htmlspecialchars($post["title"]); ?>"
        required
    >
    <br><br>

    <label>Content:</label><br>
    <textarea name="content" rows="8" cols="50" required><?php
        echo htmlspecialchars($post["content"]);
    ?></textarea>
    <br><br>

    <button type="submit">Update Post</button>

</form>

<br>

<a href="index.php">Back to Home</a>
</div>
</div>
</body>
</html>