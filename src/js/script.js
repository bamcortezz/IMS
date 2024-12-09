document.addEventListener('DOMContentLoaded', function () {

    const loginForm = document.getElementById('login-form');
    const registrationForm = document.getElementById('registration-form');
    const showRegistrationLink = document.getElementById('show-registration');
    const showLoginLink = document.getElementById('show-login');

    showRegistrationLink.addEventListener('click', function (event) {
        event.preventDefault();
        loginForm.style.display = 'none';
        registrationForm.style.display = 'block';
    });

    showLoginLink.addEventListener('click', function (event) {
        event.preventDefault();
        registrationForm.style.display = 'none';
        loginForm.style.display = 'block';
    });
});

function confirmDeleteUser(id, username) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this action for user " + username + "!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "user-management.php?delete_id=" + id;
        }
    });
}

function editSupplier(id, name, contact) {
    document.getElementById('supplierId').value = id;
    document.getElementById('editSupplierName').value = name;
    document.getElementById('editSupplierContact').value = contact;

    var editModal = new bootstrap.Modal(document.getElementById('editSupplierModal'));
    editModal.show();
}

function confirmDeleteSupplier(id, username) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this action for supplier " + username + "!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "supplier.php?delete_id=" + id;
        }
    });
}

function confirmDeleteProduct(id, product) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this prodcut " + product + "!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "product.php?delete_id=" + id;
        }
    });
}

function reactivateUser(userId, username) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to reactivate the user "${username}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, reactivate!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'user-management.php?reactivate_id=' + userId;
        }
    });
}

function reactivateSupplier(supplierId, supplierName) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to reactivate the supplier "${supplierName}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, reactivate!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'archive-supplier.php?reactivate_id=' + supplierId;
        }
    });
}

function reactivateProduct(productId, productName) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to reactivate the product: "${productName}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, reactivate!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "archive-product.php?id=" + productId;
        }
    });
}

function updatePriceAndTotal() {
    var productSelect = document.getElementById('productId');
    var selectedOption = productSelect.options[productSelect.selectedIndex];
    var price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    var quantity = parseInt(document.getElementById('quantity').value) || 0;

    document.getElementById('productPrice').value = price ? '₱' + price.toFixed(2) : '';

    var totalPrice = price * quantity;
    document.getElementById('totalPrice').value = totalPrice ? '₱' + totalPrice.toFixed(2) : '';
}


