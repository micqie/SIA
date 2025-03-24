<?php
session_start();
require 'database/connect_db.php';

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'A') {
    header("Location: login.php");
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_product':
                // ...existing code...
                break;
                
            case 'update_product':
                // ...existing code...
                break;
                
            case 'archive_product':
                $product_id = intval($_POST['product_id']);
                $query = "UPDATE products SET is_active = 0 WHERE product_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $product_id);
                
                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "Product archived successfully!";
                } else {
                    $_SESSION['error_message'] = "Error archiving product: " . $stmt->error;
                }
                break;

            case 'return_product':
                $product_id = intval($_POST['product_id']);
                $query = "UPDATE products SET is_active = 1 WHERE product_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $product_id);
                
                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "Product returned successfully!";
                } else {
                    $_SESSION['error_message'] = "Error returning product: " . $stmt->error;
                }
                break;
        }
        
        // Redirect to prevent form resubmission
        header("Location: admin_inventory.php");
        exit();
    }
}

// Fetch all active products with their categories
$query = "SELECT p.*, pc.category_name 
          FROM products p 
          LEFT JOIN product_categories pc ON p.category_id = pc.category_id 
          WHERE p.is_active = 1
          ORDER BY p.product_name";
$result = mysqli_query($conn, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch all archived products with their categories
$archived_query = "SELECT p.*, pc.category_name 
                   FROM products p 
                   LEFT JOIN product_categories pc ON p.category_id = pc.category_id 
                   WHERE p.is_active = 0
                   ORDER BY p.product_name";
$archived_result = mysqli_query($conn, $archived_query);
$archived_products = mysqli_fetch_all($archived_result, MYSQLI_ASSOC);

// Fetch all categories for the dropdown
$categories_query = "SELECT * FROM product_categories ORDER BY category_name";
$categories_result = mysqli_query($conn, $categories_query);
$categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management - EZ Leather Bar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/inventory.css">
    <style>
        .add-product-btn {
    margin-top: 100px;
}

.navbar {
            background: #9D4D36 !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 0.5rem 1rem;
        }

        .navbar-brand {
            color: white !important;
            font-size: 1.5rem;
        }

        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .profile-btn {
            background: none;
            border: none;
            color: white;
            padding: 0.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .profile-btn i {
            font-size: 1.2rem;
        }

        .profile-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            z-index: 1001;
            border-radius: 8px;
            overflow: hidden;
        }

        .profile-menu.show {
            display: block;
        }

        .profile-menu a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }

        .profile-menu a:hover {
            background-color: #f8f9fa;
        }

        .profile-menu .divider {
            border-top: 1px solid #eee;
            margin: 0;
        }

        .profile-menu .logout-btn {
            color: #dc3545;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_dashboard.php">
                <i class="fas fa-store-alt me-2"></i>EZ Leather Bar Admin
            </a>

             
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
       
        </div>
    </nav>


    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="admin_dashboard.php">
                    <i class="fas fa-chart-line"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_bookings.php">
                    <i class="fas fa-calendar-check"></i>Bookings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="admin_inventory.php">
                    <i class="fas fa-boxes"></i>Inventory
                </a>
            </li>
    
        </ul>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-boxes me-2"></i>Inventory Management</h2>
            <button class="btn btn-primary add-product-btn" data-bs-toggle="modal" data-bs-target="#addProductModal">
    <i class="fas fa-plus me-2"></i>Add New Product
</button>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="inventory-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Pieces/Bundle</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo $product['image_path'] ?: 'assets/default-product.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                                         class="product-image">
                                </td>
                                <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                <td>₱<?php echo number_format($product['base_price'], 2); ?></td>
                                <td><?php echo $product['pieces_per_bundle']; ?></td>
                                <td>
                                    <?php
                                    $stock_class = 'stock-high';
                                    if ($product['stock'] < 200) {
                                        $stock_class = 'stock-low';
                                    } elseif ($product['stock'] < 500) {
                                        $stock_class = 'stock-medium';
                                    }
                                    ?>
                                    <span class="stock-badge <?php echo $stock_class; ?>">
                                        <?php echo $product['stock']; ?> pieces
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $product['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $product['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-product" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editProductModal"
                                            data-product='<?php echo json_encode($product); ?>'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning archive-product"
                                            data-bs-toggle="modal"
                                            data-bs-target="#archiveProductModal"
                                            data-product-id="<?php echo $product['product_id']; ?>"
                                            data-product-name="<?php echo htmlspecialchars($product['product_name']); ?>">
                                        <i class="fas fa-archive"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

               <!-- Archived Products Section -->
        <div class="mt-4">
            <h4>Archived Products</h4>
            <ul id="archivedProductsList" class="list-group">
                <?php foreach ($archived_products as $archived_product): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <img src="<?php echo $archived_product['image_path'] ?: 'assets/default-product.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($archived_product['product_name']); ?>"
                                 class="product-image">
                            <?php echo htmlspecialchars($archived_product['product_name']); ?> - 
                            <?php echo htmlspecialchars($archived_product['category_name']); ?> - 
                            ₱<?php echo number_format($archived_product['base_price'], 2); ?>
                        </div>
                        <button class="btn btn-sm btn-success return-product"
                                data-bs-toggle="modal"
                                data-bs-target="#returnProductModal"
                                data-product-id="<?php echo $archived_product['product_id']; ?>"
                                data-product-name="<?php echo htmlspecialchars($archived_product['product_name']); ?>">
                            <i class="fas fa-undo"></i> Return
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <!-- Return Product Modal -->
        <div class="modal fade" id="returnProductModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Return Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to return <span id="return_product_name"></span> to active products?</p>
                    </div>
                    <div class="modal-footer">
                        <form id="returnForm" method="POST">
                            <input type="hidden" name="action" value="return_product">
                            <input type="hidden" name="product_id" id="return_product_id">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" id="returnButton">Return</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add_product">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control" name="product_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category_id" required>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['category_id']; ?>">
                                                <?php echo htmlspecialchars($category['category_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Base Price</label>
                                    <input type="number" class="form-control" name="base_price" step="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pieces per Bundle</label>
                                    <input type="number" class="form-control" name="pieces_per_bundle" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Product Image</label>
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Stock</label>
                                    <input type="number" class="form-control" name="stock" required>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_active" checked>
                                        <label class="form-check-label">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_product">
                    <input type="hidden" name="product_id" id="edit_product_id">
                    <input type="hidden" name="current_image" id="edit_current_image">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control" name="product_name" id="edit_product_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category_id" id="edit_category_id" required>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['category_id']; ?>">
                                                <?php echo htmlspecialchars($category['category_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Base Price</label>
                                    <input type="number" class="form-control" name="base_price" id="edit_base_price" step="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pieces per Bundle</label>
                                    <input type="number" class="form-control" name="pieces_per_bundle" id="edit_pieces_per_bundle" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Product Image</label>
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                    <img id="edit_image_preview" class="preview-image">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Stock</label>
                                    <input type="number" class="form-control" name="stock" id="edit_stock" required>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_active" id="edit_is_active">
                                        <label class="form-check-label">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>


    <!--ARCHIVING Product Modal -->
    <div class="modal fade" id="archiveProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Archive Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to archive <span id="archive_product_name"></span>?</p>
                </div>
                <div class="modal-footer">
                <form id="archiveForm" method="POST">
    <input type="hidden" name="action" value="archive_product">
    <input type="hidden" name="product_id" id="archive_product_id">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-warning" id="archiveButton">Archive</button>
</form>


                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Handle edit product button click
                    document.querySelectorAll('.edit-product').forEach(button => {
                        button.addEventListener('click', function() {
                            const product = JSON.parse(this.dataset.product);
                            document.getElementById('edit_product_id').value = product.product_id;
                            document.getElementById('edit_product_name').value = product.product_name;
                            document.getElementById('edit_category_id').value = product.category_id;
                            document.getElementById('edit_description').value = product.description;
                            document.getElementById('edit_base_price').value = product.base_price;
                            document.getElementById('edit_pieces_per_bundle').value = product.pieces_per_bundle;
                            document.getElementById('edit_current_image').value = product.image_path;
                            document.getElementById('edit_stock').value = product.stock;
                            document.getElementById('edit_is_active').checked = product.is_active == 1;
                            
                            const preview = document.getElementById('edit_image_preview');
                            preview.src = product.image_path || 'assets/default-product.jpg';
                        });
                    });
            
                    // Handle archive product button click
                    document.querySelectorAll('.archive-product').forEach(button => {
                        button.addEventListener('click', function() {
                            document.getElementById('archive_product_id').value = this.dataset.productId;
                            document.getElementById('archive_product_name').textContent = this.dataset.productName;
                        });
                    });
            
                    // Handle archive form submission
                    document.getElementById('archiveButton').addEventListener('click', function() {
                        document.getElementById('archiveForm').submit();
                    });
            
                    // Handle return product button click
                    document.querySelectorAll('.return-product').forEach(button => {
                        button.addEventListener('click', function() {
                            document.getElementById('return_product_id').value = this.dataset.productId;
                            document.getElementById('return_product_name').textContent = this.dataset.productName;
                        });
                    });
            
                    // Handle return form submission
                    document.getElementById('returnButton').addEventListener('click', function() {
                        document.getElementById('returnForm').submit();
                    });
            
                    // Handle image preview
                    document.querySelector('input[name="image"]').addEventListener('change', function() {
                        const preview = document.getElementById('edit_image_preview');
                        if (this.files && this.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                            }
                            reader.readAsDataURL(this.files[0]);
                        }
                    });
            
                    // Handle profile menu toggle
                    document.querySelector('.profile-btn').addEventListener('click', function() {
                        toggleProfileMenu();
                    });
            
                    // Close profile menu when clicking outside
                    window.addEventListener('click', function(event) {
                        if (!event.target.matches('.profile-btn')) {
                            const menu = document.getElementById('profileMenu');
                            if (menu && menu.classList.contains('show')) {
                                menu.classList.remove('show');
                            }
                        }
                    });
                });
            
                function toggleProfileMenu() {
                    const menu = document.getElementById('profileMenu');
                    if (menu) {
                        menu.classList.toggle('show');
                    }
                }
            </script>
</body>
</html> 





