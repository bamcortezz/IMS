<nav class="sidebar d-flex flex-column p-3">
    <h4 class="text-white mb-4 text-center">Admin Panel</h4>
    <hr>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="index.php" class="nav-link">
                <i class="bi bi-house-fill me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item mt-2">
            <a href="user-management.php" class="nav-link">
                <i class="bi bi-people-fill me-2"></i> User Management
            </a>
        </li>

        <li class="nav-item mt-2">
            <a href="#productDropdown" class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" aria-expanded="false" aria-controls="productDropdown">
                <span><i class="bi bi-box-seam me-2"></i> Product</span>
                <i class="bi bi-caret-down-fill rotate-icon"></i>
            </a>
            <div id="productDropdown" class="collapse">
                <ul class="nav flex-column ps-3">
                    <li class="nav-item">
                        <a href="product.php" class="nav-link">
                            <i class="bi bi-box-fill me-2"></i> Product List
                        </a>
                    </li>
                    <li class="nav-item mt-2">
                        <a href="purchase-order.php" class="nav-link">
                            <i class="bi bi-receipt me-2"></i> Purchase Order
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item mt-2">
            <a href="sales-order.php" class="nav-link">
                <i class="bi bi-cart-fill me-2"></i> Sales Order
            </a>
        </li>
        <li class="nav-item mt-2">
            <a href="supplier.php" class="nav-link">
                <i class="bi bi-truck me-2"></i> Supplier
            </a>
        </li>
    </ul>
    <div class="mt-auto">
        <button class="btn btn-danger w-100">
            <a href="../authentication/authentication.php?signout" class="text-decoration-none text-white">
                <i class="bi bi-box-arrow-right me-2"></i> Sign Out
            </a>
        </button>
    </div>
</nav>