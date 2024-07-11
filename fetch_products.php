<?php
include 'db.php';

if (isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
    $product_result = $conn->query("SELECT id, name FROM products WHERE category_id = $category_id");

    echo '<option value="">Select Product</option>';
    while ($product = $product_result->fetch_assoc()) {
        echo '<option value="' . $product['id'] . '">' . $product['name'] . '</option>';
    }
}
?>

