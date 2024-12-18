document.addEventListener('DOMContentLoaded', function() {
    const cartItems = document.getElementById('cart-items');
    const totalPrice = document.getElementById('total-price');
    const addToCartButtons = document.querySelectorAll('.add-to-cart');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productName = this.parentElement.querySelector('.card-title').innerText;
            const productPrice = parseFloat(this.parentElement.querySelector('.card-text').innerText.replace('$', ''));
            const cartItem = document.createElement('li');
            cartItem.className = 'list-group-item';
            cartItem.innerText = `${productName} - $${productPrice.toFixed(2)}`;
            cartItems.appendChild(cartItem);

            const currentTotal = parseFloat(totalPrice.innerText.replace('$', ''));
            totalPrice.innerText = `$${(currentTotal + productPrice).toFixed(2)}`;
        });
    });
});