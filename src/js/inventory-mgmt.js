function closeModal() {
    $('#myModal').modal('hide');
    $('modal').modal('hide');
}


function showForm(formId) {
    var forms = ['addProductForm', 'addInventoryForm', 'productListing', 'productInventory'];
    var registerForm = document.getElementById('registerForm');

    forms.forEach(function (form) {
        var formElement = document.getElementById(form);
        if (formElement) {
            if (form === formId) {
                formElement.style.display = 'block';

                if (form === 'productListing' || form === 'productInventory') {
                    registerForm.style.display = 'none';
                } else {
                    registerForm.style.display = 'block';
                }
            } else {
                formElement.style.display = 'none';
            }
        }
    });
}


function openUpdateProductModal(element) {
    // Get data from attributes
    var productId = element.getAttribute('data-product-id');
    var productName = element.getAttribute('data-product-name');
    var productPrice = element.getAttribute('data-product-price');
    var productCategory = element.getAttribute('data-product-category');

    // Populate the modal fields
    document.getElementById('updateProductId').value = productId;
    document.getElementById('updateProductName').value = productName;
    document.getElementById('updateProductPrice').value = productPrice;
    document.getElementById('updateProductCategory').value = productCategory;

    // Display the current image file name
    var currentImageFileName = productId + '.png';
    document.getElementById('currentProductImageName').innerText = currentImageFileName;


    // Show the modal
    var modal = new bootstrap.Modal(document.getElementById('updateProductModal'));
    modal.show();
}

function openUpdateInventoryModal(element) {
    // Get data from attributes
    var inventoryId = element.getAttribute('data-inventory-id');
    var productId = element.getAttribute('data-product-id');
    var quantity = element.getAttribute('data-quantity');
    var inventoryDate = element.getAttribute('data-inventory-date');
    var size = element.getAttribute('data-size');

    // Populate the modal fields
    document.getElementById('updateInventoryId').value = inventoryId;
    document.getElementById('updateProductId').value = productId;
    document.getElementById('updateInventoryQuantity').value = quantity;
    document.getElementById('updateInventoryDate').value = inventoryDate;
    document.getElementById('updateInventorySize').value = size;

    // Show the modal
    var modal = new bootstrap.Modal(document.getElementById('updateInventoryModal'));
    modal.show();
}

function confirmProductAddition() {
    var confirmation = confirm('Confirm New Product?');
    if (confirmation) {
        return true;
    } else {
        alert('Product was not added!');
        window.location.href = 'inventory-management.php';
        return false;
    }
}

function confirmProductAdditionToInventory() {
    var confirmation = confirm('Confirm Product Addition to Inventory?');
    if (confirmation) {
        return true;
    } else {
        alert('Product was not added to Inventory!');
        window.location.href = 'inventory-management.php';
        return false;
    }
}

function confirmProductUpdate() {
    var confirmation = confirm('Confirm Product Update?');
    if (confirmation) {
        return true;
    } else {
        alert('Product was not updated!');
        window.location.href = 'inventory-management.php';
        return false;
    }
}

function confirmProductUpdateToInventory() {
    var confirmation = confirm('Confirm Product Update to Inventory?');
    if (confirmation) {
        return true;
    } else {
        alert('Product was not updated to Inventory!');
        window.location.href = 'inventory-management.php';
        return false;
    }
}

function openProductDeleteConfirmation(productId) {
    var confirmAction = confirm("Are you sure you want to delete this product?");
    if (confirmAction) {
        window.location.href = 'delete-product.php?productId=' + productId;
    } else {
        alert('Product deletion cancelled.');
        window.location.href = 'inventory-management.php';
    }
}

function openInventoryDeleteConfirmation(inventoryId) {
    var confirmAction = confirm("Are you sure you want to delete this product from Inventory?");
    if (confirmAction) {
        window.location.href = 'delete-product-from-inventory.php?inventoryId=' + inventoryId;
    } else {
        alert('Inventory item deletion cancelled.');
        window.location.href = 'inventory-management.php';
    }
}