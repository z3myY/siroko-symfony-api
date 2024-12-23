let cartId;
let totalPrice = 0;
let totalItems = 0;

$(document).ready(function () {
  cart();
  loadProducts();
  setupEventListeners();
});

function cart() {
  $.ajax({
    url: '/carts/last',
    method: 'GET',
    success: function (data) {
      cartId = data.id;
      $('#cart').data('cart', cartId);
      loadCartProducts(cartId);
      updateCartSummary();
    },
    error: function () {
      alert('Failed to load cart.');
    }
  });
}

function setupEventListeners() {
  $(document).on('click', '.add-to-cart', addProductToCart);
  $(document).on('click', '.increase-product', increaseProduct);
  $(document).on('click', '.reduce-product', reduceProduct);
}

function updateCartSummary() {
  $.ajax({
    url: '/carts/last',
    method: 'GET',
    success: function (data) {

      $('#total-price').text(`${data.totalPrice.toFixed(2)}€`);
      $('#total-items').text(data.totalProducts);
    },
    error: function () {
      alert('Failed to load cart.');
    }
  });
}

function addProductToCart() {
  const productId = $(this).data('id');
  const productName = $(this).siblings('.card-title').attr('value');
  const productPrice = parseFloat($(this).siblings('.discounted-price').attr('value'));

  const existingCartItem = $(`#cart-items li[data-id="${productId}"]`);
  if (existingCartItem.length > 0) {
    updateCartItem(existingCartItem, productPrice, 1);
  } else {
    addNewItemToCart(productId, productName, productPrice);
  }

  addToCart(productId, productName, productPrice);
}

function updateCartItem(cartItem, productPrice, quantityChange) {
  const quantityElement = cartItem.find('.quantity');
  const newQuantity = parseInt(quantityElement.attr('value')) + quantityChange;
  quantityElement.attr('value', newQuantity).text(`x${newQuantity}`);

  const itemTotalPriceElement = cartItem.find('.item-total-price');
  const newItemTotalPrice = (newQuantity * productPrice).toFixed(2);
  itemTotalPriceElement.text(`${newItemTotalPrice}€`);
}

function addNewItemToCart(productId, productName, productPrice) {
  const cartItem = $(`
    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="${productId}" data-price="${productPrice}" data-name="${productName}">
      ${productName} - ${productPrice.toFixed(2)}€
      <span class="item-total-price">${productPrice.toFixed(2)}€</span>
      <button class="btn btn-sm btn-danger ml-2 reduce-product">-</button>
      <span class="badge badge-primary badge-pill quantity" value="1">x1</span>
      <button class="btn btn-sm btn-success ml-2 increase-product">+</button>
    </li>
  `);
  $('#cart-items').append(cartItem);
}

function increaseProduct() {
  const cartItem = $(this).parent();
  const productId = cartItem.data('id');
  const productPrice = parseFloat(cartItem.data('price'));
  const productName = cartItem.data('name');

  updateCartItem(cartItem, productPrice, 1);
  addToCart(productId, productName, productPrice);
}

function reduceProduct() {
  const cartItem = $(this).parent();
  const productId = cartItem.data('id');
  const productPrice = parseFloat(cartItem.data('price'));
  const productName = cartItem.data('name');

  const quantityElement = $(this).siblings('.quantity');
  const newQuantity = parseInt(quantityElement.attr('value')) - 1;
  if (newQuantity > 0) {
    updateCartItem(cartItem, productPrice, -1);
    updateCartProductQuantity(productId, productName, productPrice, newQuantity);
    updateCartSummary();
  } else {
    cartItem.remove();
    removeFromCart(productId);
    updateCartSummary();
  }
}

function updateCartProductQuantity(productId, productName, productPrice, newQuantity) {
  $.ajax({
    url: `/carts/products/${productId}`,
    method: 'PUT',
    contentType: 'application/json',
    data: JSON.stringify({
      cartId: cartId,
      quantity: newQuantity,
      name: productName,
      price: productPrice
    }),
    success: function (response) {
      updateCartSummary();
      console.log('Product quantity reduced:', response);
    },
    error: function () {
      alert('Failed to reduce product quantity.');
    }
  });
}

function removeFromCart(productId) {
  $.ajax({
    url: `/carts/${cartId}/products/${productId}`,
    method: 'DELETE',
    success: function (response) {
      console.log('Product removed from cart:', response);
      updateCartSummary();
    },
    error: function () {
      alert('Failed to remove product from cart.');
    }
  });
}

function addToCart(productId, productName, price) {
  $.ajax({
    url: '/carts/products',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
      cartId: cartId,
      productId: productId,
      quantity: 1,
      name: productName,
      price: price
    }),
    success: function (response) {
      updateCartSummary();
      console.log('Product added to cart:', response);
    },
    error: function () {
      alert('Failed to add product to cart.');
    }
  });
}

function loadProducts() {
  $.ajax({
    url: '/products',
    method: 'GET',
    success: function (data) {
      const productsContainer = $('#products-container');
      data.forEach(function (product) {
        if (product.stock > 0) {
          const productCard = `
        <div class="card mb-3">
          <div class="card-body" data-id="${product.id}">
            <h5 class="card-title" value="${product.name}" style="cursor: pointer;" onclick="window.open('/products/${product.id}', '_blank')">${product.name}</h5>
            <p class="card-text original-price">Original Price: ${product.price}€</p>
            <p class="card-text discounted-price" value="${product.priceWithDiscount}">Discounted Price: ${product.priceWithDiscount}€</p>
            <button class="btn btn-primary add-to-cart" data-id="${product.id}">Add to Cart</button>
          </div>
        </div>
          `;
          productsContainer.append(productCard);
        }
      });
        },
        error: function () {
      alert('Failed to load products.');
    }
  });
}

function loadCartProducts(cartId) {
  $.ajax({
    url: `/carts/${cartId}/products`,
    method: 'GET',
    success: function (data) {
      const cartItems = $('#cart-items');
      let totalPrice = 0;
      let totalItems = 0;

      data.forEach(function (product) {
        const itemTotalPrice = (product.quantity * product.price).toFixed(2);
        totalPrice += parseFloat(itemTotalPrice);
        totalItems += product.quantity;

        const cartItem = $(`
          <li class="list-group-item d-flex justify-content-between align-items-center" data-id="${product.productId}" data-name="${product.name}" data-price="${product.price}">
            ${product.name} - ${product.price.toFixed(2)}€
            <span class="item-total-price" value="${itemTotalPrice}">${itemTotalPrice}€</span>
            <button class="btn btn-sm btn-danger ml-2 reduce-product">-</button>
            <span class="badge badge-primary badge-pill quantity" value="${product.quantity}">x${product.quantity}</span>
            <button class="btn btn-sm btn-success ml-2 increase-product">+</button>
          </li>
        `);
        cartItems.append(cartItem);
      });

      $('#total-price').text(`${totalPrice.toFixed(2)}€`);
      $('#total-items').text(totalItems);
    },
    error: function () {
      alert('Failed to load cart items.');
    }
  });
}

function countProducts() {
  let totalItems = 0;
  
  $.ajax({
    url: `/carts/${cartId}/products/count`,
    method: 'GET',
    async: false,
    success: function (data) {
        totalItems = data.count;
    },
    error: function () {
      alert('Failed to count products.');
    }
  });
  return totalItems;
}
