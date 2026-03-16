<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "portfolio_db";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);
$conn->query("CREATE TABLE IF NOT EXISTS Category_Tbl (
    Category_ID INT AUTO_INCREMENT PRIMARY KEY,
    Category_Name VARCHAR(100) NOT NULL
)");

if (isset($_POST['save'])) {
    $name = $_POST['Category_Name'];
    $conn->query("INSERT INTO Category_Tbl (Category_Name)
                  VALUES ('$name')");
    echo "<p>Category added successfully!</p>";
}

if (isset($_POST['delete_id'])) {
    $id = (int) $_POST['delete_id'];
    $conn->query("DELETE FROM Category_Tbl WHERE Category_ID = $id");
    echo "<p>Category deleted successfully!</p>";
}

if (isset($_POST['update'])) {
    $id = (int) $_POST['update_id'];
    $name = $_POST['Category_Name'];
    $conn->query("UPDATE Category_Tbl SET Category_Name = '$name' WHERE Category_ID = $id");
    echo "<p>Category updated successfully!</p>";
}

$editCategory = null;
if (isset($_POST['edit_id'])) {
    $id = (int) $_POST['edit_id'];
    $result = $conn->query("SELECT * FROM Category_Tbl WHERE Category_ID = $id");
    if ($result->num_rows > 0) {
        $editCategory = $result->fetch_assoc();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<a href="index.php">< Back to Dashboard</a>
<hr>

<h2>Add Category</h2>

<form method="POST">
    <input type="text" name="Category_Name" placeholder="Category Name" required>
    <button type="submit" name="save">Save</button>
</form>

<?php if (!empty($editCategory)): ?>
    <hr>
    <h2>Edit Category</h2>
    <form method="POST">
        <input type="hidden" name="update_id" value="<?= $editCategory['Category_ID'] ?>">
        <input type="text" name="Category_Name" value="<?= htmlspecialchars($editCategory['Category_Name']) ?>" required>
        <button type="submit" name="update">Update</button>
    </form>
<?php endif; ?>

<hr>

<h2>Category List</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM Category_Tbl");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['Category_ID']}</td>
                <td>{$row['Category_Name']}</td>
                <td>
                    <form method='POST' style='display:inline;'>
                        <input type='hidden' name='delete_id' value='{$row['Category_ID']}'>
                        <button type='submit'>Delete</button>
                    </form>
                    <form method='POST' style='display:inline;'>
                        <input type='hidden' name='edit_id' value='{$row['Category_ID']}'>
                        <button type='submit'>Edit</button>
                    </form>
                </td>
              </tr>";
    }
    ?>
</body>
</html>
