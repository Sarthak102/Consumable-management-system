<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    $sql = "INSERT INTO categories (name) VALUES ('$name')";
    if ($conn->query($sql) === TRUE) {
        echo "Category added successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Add Category</title>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h1>Add Category</h1>
        <form method="POST">
            <label for="name">Category Name:</label>
            <input type="text" name="name" id="name" required><br>
            <input type="submit" value="Add Category">
        </form>
    </div>
</body>
</html>
