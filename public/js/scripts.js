$(document).ready(function() {

  $.ajax({
      url: '/products',
      method: 'GET',
      success: function(data) {
          var productsContainer = $('#products-container');
          data.forEach(function(product) {
              var productCard = `
                  <div class="card mb-3">
                      <div class="card-body" product-id="${product.id}">
                          <h5 class="card-title">${product.name}</h5>
                          <p class="card-text">$${product.price}</p>
                          <button class="btn btn-primary add-to-cart" data-id="${product.id}">Add to Cart</button>
                      </div>
                  </div>
              `;
              productsContainer.append(productCard);
          });
      },
      error: function() {
          alert('Failed to load products.');
      }
  });

  // Cart functionality
  $(document).on('click', '.add-to-cart', function() {
      const productId = $(this).data('id');
      const productName = $(this).siblings('.card-title').text();
      const productPrice = parseFloat($(this).siblings('.card-text').text().replace('$', ''));

      // Check if the product is already in the cart
      const existingCartItem = $(`#cart-items li[data-id="${productId}"]`);
      if (existingCartItem.length > 0) {
          // Update the quantity and total price
          const quantityElement = existingCartItem.find('.quantity');
          const newQuantity = parseInt(quantityElement.text().replace('x', '')) + 1;
          quantityElement.text(`x${newQuantity}`);

          const itemTotalPriceElement = existingCartItem.find('.item-total-price');
          const newItemTotalPrice = (newQuantity * productPrice).toFixed(2);
          itemTotalPriceElement.text(`$${newItemTotalPrice}`);
      } else {
          // Add new item to the cart
          const cartItem = $(`
              <li class="list-group-item d-flex justify-content-between align-items-center" data-id="${productId}">
                  ${productName} - $${productPrice.toFixed(2)}
                  <span class="badge badge-primary badge-pill quantity">x1</span>
                  <span class="item-total-price">$${productPrice.toFixed(2)}</span>
              </li>
          `);
          $('#cart-items').append(cartItem);
      }

      // Update the total price
      const currentTotal = parseFloat($('#total-price').text().replace('$', ''));
      $('#total-price').text(`$${(currentTotal + productPrice).toFixed(2)}`);
  });
});