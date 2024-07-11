<?php
include 'db.php';

// Set your threshold value here
$threshold = 10;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax'])) {
    $product_id = $_POST['product_id'];
    $increment_quantity = $_POST['increment_quantity'];

    $sql = "UPDATE products SET available_quantity = available_quantity + $increment_quantity WHERE id = $product_id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Product quantity updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
    exit;
}

// Fetch all products
$product_result = $conn->query("SELECT id, name, available_quantity FROM products");

// Fetch products with quantity below the threshold
$low_quantity_result = $conn->query("SELECT id, name, available_quantity FROM products WHERE available_quantity < $threshold");
$low_quantity_products = [];
while ($product = $low_quantity_result->fetch_assoc()) {
    $low_quantity_products[] = $product;
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>View Products</title>
    <script>
        // Function to show the popup
        function showPopup(products) {
            let popup = document.getElementById('popup');
            let popupContent = document.getElementById('popup-content');

            let content = '<h2>Low Stock Alert</h2>';
            products.forEach(product => {
                content += `<p>Product: ${product.name}, Quantity: ${product.available_quantity}</p>`;
            });

            popupContent.innerHTML = content;
            popup.style.display = 'block';

            // Hide the popup after 3 seconds
            setTimeout(function() {
                popup.style.display = 'none';
            }, 3000);
        }

        // Show the popup if there are low quantity products
        <?php if (!empty($low_quantity_products)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showPopup(<?php echo json_encode($low_quantity_products); ?>);
            });
        <?php endif; ?>

        // Function to handle form submission via AJAX
        function submitForm(event, form) {
            event.preventDefault();
            let formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Function to fetch products based on selected category
        function fetchProducts(categoryId) {
            if (!categoryId) {
                document.getElementById('product-dropdown').innerHTML = '<option value="">Select Product</option>';
                return;
            }
            fetch(`fetch_products.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `category_id=${categoryId}`
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('product-dropdown').innerHTML = html;
            });
        }
    </script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h1>Available Products</h1>
        <form method="GET" action="view_products.php">
            <div class="form-group">
                <label for="category-dropdown">Select Category:</label>
                <select id="category-dropdown" name="category_id" onchange="fetchProducts(this.value)">
                    <option value="">Select Category</option>
                    <?php
                    $categories_result = $conn->query("SELECT id, name FROM categories");
                    while ($category = $categories_result->fetch_assoc()) {
                        echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="product-dropdown">Select Product:</label>
                <select id="product-dropdown" name="product_id">
                    <option value="">Select Product</option>
                    <!-- Product options will be populated based on the selected category -->
                </select>
            </div>
            <input type="submit" value="Search">
        </form>
        
        <div class="table-responsive">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Available Quantity</th>
                        <th>Add Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($_GET['product_id']) && $_GET['product_id'] != ''): ?>
                        <?php
                        $product_id = $_GET['product_id'];
                        $product = $conn->query("SELECT id, name, available_quantity FROM products WHERE id = $product_id")->fetch_assoc();
                        ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $product['available_quantity']; ?></td>
                            <td>
                                <form method="POST" action="view_products.php" onsubmit="submitForm(event, this)">
                                    <input type="hidden" name="ajax" value="1">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="number" name="increment_quantity" min="1" required>
                                    <input type="submit" value="Add">
                                </form>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while ($product = $product_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['available_quantity']; ?></td>
                                <td>
                                    <form method="POST" action="view_products.php" onsubmit="submitForm(event, this)">
                                        <input type="hidden" name="ajax" value="1">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="number" name="increment_quantity" min="1" required>
                                        <input type="submit" value="Add">
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Popup div -->
    <div id="popup">
        <div id="popup-content"></div>
    </div>
</body>
</html>
