# Inventory Management System

This project is an Inventory Management System built with PHP, MySQL, HTML, and CSS. It allows users to add category, add products, issue products, view available products, and generate monthly inventory reports.

## Features

- Add products associated with categories
- Issue products
- View available products with the ability to increment quantities
- Generate inventory reports

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/Sarthak102/Inventory-management.git
   cd Inventory-management
   ```

2. **Set up the database:**

   - Create a database named `inventory_db`:

   ```sql
   CREATE DATABASE inventory_db;
   USE inventory_db;
   ```

3. **Configure the database connection:**

   - Update the `db.php` file with your MySQL database credentials.

   ```php
   <?php
   $servername = "your_servername";
   $username = "your_username";
   $password = "your_password";
   $dbname = "inventory";
   $port = "your_port"

   // Create connection
   $conn = new mysqli($servername, $username, $password, $dbname, $port);

   // Check connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   ?>
   ```

4. **Start the server:**

   - If you are using XAMPP or WAMP, place the project folder in the `htdocs` directory.
   - Start Apache and MySQL services.

5. **Access the application:**

   - Open your web browser and go to `http://localhost/Inventory-management`.

## Database Tables

Execute the following SQL commands to set up the necessary tables in your MySQL database:

```sql
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    available_quantity INT NOT NULL,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE inventory_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    date DATE NOT NULL,
    issued_quantity INT,
    issued_by VARCHAR(255),
    balance_quantity INT,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

## File Structure

```
inventory-management-system/
│
├── font                  # Fonts Directory
├── db.php                # Database connection file
├── navbar.php            # Navbar component
├── fpdf.php              # PDF generation file
├── add_category.php      # Add category form
├── add_product.php       # Add product form
├── fetch_products.php    # Fetch product
├── issue_product.php     # Issue product form
├── view_products.php     # View available products
├── generate_report.php   # Generate inventory reports
├── styles.css            # CSS styles
├── index.php             # Home page
└── README.md             # Project readme
```

## Screenshots

### Home Page
![image](https://github.com/Sarthak102/Inventory-management/assets/91387298/1c312692-4954-497c-b620-021edd2b501f)


### Add Category
![image](https://github.com/Sarthak102/Inventory-management/assets/91387298/f97dcea5-0178-4ddd-9dab-b78880c83ab9)


### Add Product
![image](https://github.com/Sarthak102/Inventory-management/assets/91387298/0ce97aba-bad8-40f7-9594-2b40abaff176)


### Issue Products
![image](https://github.com/Sarthak102/Inventory-management/assets/91387298/2569a745-d9ec-40ef-8afc-f0c8f90ed91b)


### View Products
![image](https://github.com/Sarthak102/Inventory-management/assets/91387298/6369e4cb-0dd0-4e54-a1a1-53b035934d16)


### Generate Report
![image](https://github.com/Sarthak102/Inventory-management/assets/91387298/4d1eb7ca-ede9-4ae9-8bf6-ac1c90e9e6d0)

![image](https://github.com/Sarthak102/Inventory-management/assets/91387298/deead9cc-2194-4af0-b290-528befd09714)





## Contact

- **Author:** Sarthak Kamble
- **Email:** sarthakkamble101@gmail.com
- **GitHub:** [Sarthak102](https://github.com/Sarthak102)
- **LinkedIn:** [Sarthak Kamble](https://www.linkedin.com/in/sarthak-kamble-101/)
