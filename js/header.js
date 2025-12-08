function formatCurrency(amount) {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(amount);
}

function renderMiniCart() {
  const cartData = localStorage.getItem("my_cart");
  const cartContainer = document.getElementById("mini-cart-content");
  const badge = document.getElementById("cart-badge");

  let cart = [];
  if (cartData) {
    try {
      cart = JSON.parse(cartData);
    } catch (e) {
      console.error(e);
    }
  }

  if (badge) badge.innerText = cart.length;

  if (cart.length === 0) {
    if (cartContainer) {
      cartContainer.innerHTML = `
                <div class="text-center py-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" width="60" style="opacity:0.5">
                    <p class="mb-0 mt-2 text-muted fw-bold">Giỏ hàng chưa có sản phẩm</p>
                </div>
            `;
    }
  } else {
    let itemsHtml = '<div class="cart-items-scroll">';
    let grandTotal = 0;

    cart.forEach((item) => {
      let itemTotal = item.price * item.qty;
      grandTotal += itemTotal;
      let imgSrc = item.image ? item.image : "https://via.placeholder.com/50";

      itemsHtml += `
                <div class="cart-item">
                    <img src="${imgSrc}" alt="Img">
                    
                    <div class="cart-item-info">
                        <div class="cart-item-title">${item.name}</div>
                        <div class="d-flex justify-content-between mt-1">
                            <span style="color:#d63384; font-weight:bold;">${formatCurrency(
                              item.price
                            )}</span>
                            <span class="text-muted">x${item.qty}</span>
                        </div>
                    </div>

                    <button class="btn-remove-cart-item" onclick="removeCartItem('${
                      item.id
                    }')" title="Xóa">&times;</button>
                </div>
            `;
    });

    itemsHtml += `</div>
            <div class="cart-total-row">
                <span>Tổng cộng:</span>
                <span style="color:#d63384;">${formatCurrency(
                  grandTotal
                )}</span>
            </div>
            <a href="cart.php" class="btn btn-view-cart">Xem giỏ hàng</a>
        `;

    if (cartContainer) cartContainer.innerHTML = itemsHtml;
  }
}

window.removeCartItem = function (id) {
  let cartData = localStorage.getItem("my_cart");
  if (!cartData) return;

  let cart = JSON.parse(cartData);

  let itemToRemove = cart.find((item) => item.id == id);
  let itemName = itemToRemove ? itemToRemove.name : "Sản phẩm";

  let newCart = cart.filter((item) => item.id != id);

  localStorage.setItem("my_cart", JSON.stringify(newCart));
  renderMiniCart();

  showToast(`Đã xóa <b>${itemName}</b> khỏi giỏ!`, "success");
};

document.addEventListener("DOMContentLoaded", renderMiniCart);
