<?php
include 'db.php';

// Fetch all categories for the dropdown
$category_result = $conn->query("SELECT id, name FROM categories");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $issued_quantity = $_POST['issued_quantity'];
    $issued_by = $_POST['issued_by'];

    // Fetch available quantity for the selected product
    $product_result = $conn->query("SELECT available_quantity FROM products WHERE id = $product_id");
    $product = $product_result->fetch_assoc();
    $new_balance = $product['available_quantity'] - $issued_quantity;

    // Update product quantity and log the issue
    $update_product = "UPDATE products SET available_quantity = $new_balance WHERE id = $product_id";
    $log_inventory = "INSERT INTO inventory_log (product_id, date, issued_quantity, issued_by, balance_quantity) VALUES ($product_id, CURDATE(), $issued_quantity, '$issued_by', $new_balance)";

    if ($conn->query($update_product) === TRUE && $conn->query($log_inventory) === TRUE) {
        echo "Product issued successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Issue Product</title>
    <script>
        // Fetch products based on the selected category
        function fetchProducts(categoryId) {
            const formData = new FormData();
            formData.append('category_id', categoryId);

            fetch('fetch_products.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                const productSelect = document.getElementById('product_id');
                productSelect.innerHTML = data;
            })
            .catch(error => {
                console.error('Error fetching products:', error);
            });
        }
    </script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h1>Issue Product</h1>
        <form method="POST">
            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id" required onchange="fetchProducts(this.value)">
                <option value="">Select Category</option>
                <?php while ($category = $category_result->fetch_assoc()): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                <?php endwhile; ?>
            </select><br>
            <label for="product_id">Product Name:</label>
            <select name="product_id" id="product_id" required>
                <option value="">Select a category first</option>
            </select><br>
            <label for="issued_quantity">Issued Quantity:</label>
            <input type="number" name="issued_quantity" id="issued_quantity" required><br>
            <label for="issued_by">Issued By:</label>
            <input type="text" name="issued_by" id="issued_by" required><br>
            <input type="submit" value="Issue Product">
        </form>
    </div>
</body>
</html>
