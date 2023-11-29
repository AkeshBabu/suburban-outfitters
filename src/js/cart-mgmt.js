
let cart = {};

function addToCart(productId, price) {
    if (!cart[productId]) {
        cart[productId] = { quantity: 1, price: price };
    } else {
        cart[productId].quantity += 1;
    }
    updateCartDisplay();
}

function updateQuantity(productId, quantity) {
    if (quantity <= 0) {
        delete cart[productId];
    } else {
        cart[productId].quantity = quantity;
    }
    updateCartDisplay();
}

function updateCartDisplay() {

    let cartContainer = document.getElementById('cart-container');
    cartContainer.innerHTML = ''; // Clear current display

    let totalAmount = 0;
    for (const [productId, productDetails] of Object.entries(cart)) {
        totalAmount += productDetails.quantity * productDetails.price;
    }
    document.getElementById('cartData').value = JSON.stringify(cart);

}
