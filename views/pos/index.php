<?php
/**
 * POS (Punto de Venta) Interface
 * Main point of sale interface for cashiers
 */
?>

<div class="pos-container">
    <div class="row">
        <!-- Product Search and Selection -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-shopping-cart"></i> Punto de Venta
                        <?php if (isset($branch) && $branch): ?>
                            - <?= htmlspecialchars($branch['name']) ?>
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Search Bar -->
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="productSearch" 
                                   placeholder="Buscar producto por nombre o código...">
                            <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-3">
                        <select class="form-select" id="categoryFilter">
                            <option value="">Todas las categorías</option>
                            <?php if (isset($categories) && $categories): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>">
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Products Grid -->
                    <div class="products-grid" id="productsGrid">
                        <?php if (isset($products) && $products): ?>
                            <?php foreach ($products as $product): ?>
                                <div class="product-card" data-product-id="<?= $product['id'] ?>"
                                     data-product-name="<?= htmlspecialchars($product['name']) ?>"
                                     data-product-price="<?= $product['retail_price'] ?>"
                                     data-product-stock="<?= $product['stock'] ?>">
                                    <div class="product-image">
                                        <?php if ($product['image']): ?>
                                            <img src="<?= SITE_URL ?>uploads/products/<?= $product['image'] ?>" 
                                                 alt="<?= htmlspecialchars($product['name']) ?>">
                                        <?php else: ?>
                                            <div class="no-image">
                                                <i class="fa fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-info">
                                        <h6><?= htmlspecialchars($product['name']) ?></h6>
                                        <p class="product-code"><?= htmlspecialchars($product['code']) ?></p>
                                        <p class="product-price"><?= formatCurrency($product['retail_price']) ?></p>
                                        <p class="product-stock">Stock: <?= $product['stock'] ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-products">
                                <i class="fa fa-box"></i>
                                <p>No hay productos disponibles</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shopping Cart -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-receipt"></i> Carrito de Compras
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Customer Selection -->
                    <div class="mb-3">
                        <label for="customerSelect" class="form-label">Cliente</label>
                        <select class="form-select" id="customerSelect">
                            <option value="">Cliente General</option>
                            <?php if (isset($customers) && $customers): ?>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= $customer['id'] ?>">
                                        <?= htmlspecialchars($customer['name']) ?>
                                        <?php if ($customer['email']): ?>
                                            - <?= htmlspecialchars($customer['email']) ?>
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Cart Items -->
                    <div class="cart-items" id="cartItems">
                        <div class="empty-cart">
                            <i class="fa fa-shopping-cart"></i>
                            <p>Carrito vacío</p>
                        </div>
                    </div>

                    <!-- Cart Summary -->
                    <div class="cart-summary" id="cartSummary" style="display: none;">
                        <div class="row">
                            <div class="col">Subtotal:</div>
                            <div class="col text-end" id="subtotal">$0.00</div>
                        </div>
                        <div class="row">
                            <div class="col">Descuento:</div>
                            <div class="col text-end">
                                <input type="number" class="form-control form-control-sm text-end" 
                                       id="discountPercent" min="0" max="100" value="0" step="0.01">
                                <small>%</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">IVA (<?= $tax_rate ?? 16 ?>%):</div>
                            <div class="col text-end" id="tax">$0.00</div>
                        </div>
                        <hr>
                        <div class="row fw-bold">
                            <div class="col">Total:</div>
                            <div class="col text-end" id="total">$0.00</div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3" id="paymentSection" style="display: none;">
                        <label for="paymentMethod" class="form-label">Método de Pago</label>
                        <select class="form-select" id="paymentMethod">
                            <option value="cash">Efectivo</option>
                            <option value="card">Tarjeta</option>
                            <option value="transfer">Transferencia</option>
                            <option value="mixed">Mixto</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-success" id="processBtn" style="display: none;">
                            <i class="fa fa-check"></i> Procesar Venta
                        </button>
                        <button class="btn btn-warning" id="clearBtn" style="display: none;">
                            <i class="fa fa-trash"></i> Limpiar Carrito
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles for POS -->
<style>
.pos-container {
    padding: 20px;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    max-height: 500px;
    overflow-y: auto;
}

.product-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.product-card:hover {
    border-color: #007bff;
    box-shadow: 0 2px 5px rgba(0,123,255,0.2);
}

.product-image {
    text-align: center;
    margin-bottom: 10px;
}

.product-image img {
    width: 100%;
    max-width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
}

.no-image {
    width: 80px;
    height: 80px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    border-radius: 4px;
    color: #6c757d;
}

.product-info h6 {
    font-size: 14px;
    margin-bottom: 5px;
    font-weight: 600;
}

.product-code {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 5px;
}

.product-price {
    font-size: 16px;
    font-weight: bold;
    color: #28a745;
    margin-bottom: 5px;
}

.product-stock {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 0;
}

.cart-items {
    max-height: 300px;
    overflow-y: auto;
    margin-bottom: 15px;
}

.empty-cart {
    text-align: center;
    color: #6c757d;
    padding: 40px 20px;
}

.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.cart-item-info {
    flex: 1;
}

.cart-item-name {
    font-weight: 600;
    font-size: 14px;
}

.cart-item-price {
    color: #6c757d;
    font-size: 12px;
}

.cart-item-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.quantity-control {
    display: flex;
    align-items: center;
    gap: 5px;
}

.quantity-btn {
    width: 25px;
    height: 25px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 3px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-input {
    width: 50px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 3px;
    padding: 2px;
}

.cart-summary {
    border-top: 1px solid #eee;
    padding-top: 15px;
}

.cart-summary .row {
    margin-bottom: 8px;
}

.no-products {
    text-align: center;
    color: #6c757d;
    padding: 40px 20px;
    grid-column: 1 / -1;
}
</style>

<!-- POS JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cart = [];
    const taxRate = <?= $tax_rate ?? 16 ?>;
    
    // Product search functionality
    const productSearch = document.getElementById('productSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const productsGrid = document.getElementById('productsGrid');
    
    // Cart elements
    const cartItems = document.getElementById('cartItems');
    const cartSummary = document.getElementById('cartSummary');
    const paymentSection = document.getElementById('paymentSection');
    const processBtn = document.getElementById('processBtn');
    const clearBtn = document.getElementById('clearBtn');
    
    // Add product to cart
    productsGrid.addEventListener('click', function(e) {
        const productCard = e.target.closest('.product-card');
        if (!productCard) return;
        
        const productId = parseInt(productCard.dataset.productId);
        const productName = productCard.dataset.productName;
        const productPrice = parseFloat(productCard.dataset.productPrice);
        const productStock = parseInt(productCard.dataset.productStock);
        
        addToCart(productId, productName, productPrice, productStock);
    });
    
    function addToCart(id, name, price, stock) {
        const existingItem = cart.find(item => item.id === id);
        
        if (existingItem) {
            if (existingItem.quantity < stock) {
                existingItem.quantity++;
            } else {
                alert('No hay suficiente stock disponible');
                return;
            }
        } else {
            cart.push({
                id: id,
                name: name,
                price: price,
                quantity: 1,
                stock: stock
            });
        }
        
        updateCartDisplay();
    }
    
    function updateCartDisplay() {
        if (cart.length === 0) {
            cartItems.innerHTML = '<div class="empty-cart"><i class="fa fa-shopping-cart"></i><p>Carrito vacío</p></div>';
            cartSummary.style.display = 'none';
            paymentSection.style.display = 'none';
            processBtn.style.display = 'none';
            clearBtn.style.display = 'none';
            return;
        }
        
        let html = '';
        cart.forEach((item, index) => {
            html += `
                <div class="cart-item">
                    <div class="cart-item-info">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">${formatCurrency(item.price)}</div>
                    </div>
                    <div class="cart-item-controls">
                        <div class="quantity-control">
                            <button class="quantity-btn" onclick="changeQuantity(${index}, -1)">-</button>
                            <input type="number" class="quantity-input" value="${item.quantity}" 
                                   onchange="setQuantity(${index}, this.value)" min="1" max="${item.stock}">
                            <button class="quantity-btn" onclick="changeQuantity(${index}, 1)">+</button>
                        </div>
                        <button class="btn btn-sm btn-danger" onclick="removeItem(${index})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        
        cartItems.innerHTML = html;
        updateCartSummary();
        
        cartSummary.style.display = 'block';
        paymentSection.style.display = 'block';
        processBtn.style.display = 'block';
        clearBtn.style.display = 'block';
    }
    
    function updateCartSummary() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const discountPercent = parseFloat(document.getElementById('discountPercent').value) || 0;
        const discountAmount = subtotal * (discountPercent / 100);
        const subtotalAfterDiscount = subtotal - discountAmount;
        const taxAmount = subtotalAfterDiscount * (taxRate / 100);
        const total = subtotalAfterDiscount + taxAmount;
        
        document.getElementById('subtotal').textContent = formatCurrency(subtotal);
        document.getElementById('tax').textContent = formatCurrency(taxAmount);
        document.getElementById('total').textContent = formatCurrency(total);
    }
    
    window.changeQuantity = function(index, change) {
        const item = cart[index];
        const newQuantity = item.quantity + change;
        
        if (newQuantity > 0 && newQuantity <= item.stock) {
            item.quantity = newQuantity;
            updateCartDisplay();
        }
    };
    
    window.setQuantity = function(index, value) {
        const item = cart[index];
        const quantity = parseInt(value);
        
        if (quantity > 0 && quantity <= item.stock) {
            item.quantity = quantity;
            updateCartDisplay();
        }
    };
    
    window.removeItem = function(index) {
        cart.splice(index, 1);
        updateCartDisplay();
    };
    
    // Discount change handler
    document.getElementById('discountPercent').addEventListener('input', updateCartSummary);
    
    // Clear cart
    clearBtn.addEventListener('click', function() {
        if (confirm('¿Está seguro de que desea limpiar el carrito?')) {
            cart.length = 0;
            updateCartDisplay();
        }
    });
    
    // Process sale
    processBtn.addEventListener('click', function() {
        if (cart.length === 0) {
            alert('El carrito está vacío');
            return;
        }
        
        const saleData = {
            items: cart.map(item => ({
                product_id: item.id,
                quantity: item.quantity,
                price: item.price
            })),
            customer_id: document.getElementById('customerSelect').value || null,
            payment_method: document.getElementById('paymentMethod').value,
            discount: parseFloat(document.getElementById('discountPercent').value) || 0
        };
        
        // Show loading
        processBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Procesando...';
        processBtn.disabled = true;
        
        fetch('<?= SITE_URL ?>pos/process-sale', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(saleData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Venta procesada exitosamente!\nNúmero: ${data.sale_number}\nTotal: ${formatCurrency(data.total)}`);
                cart.length = 0;
                updateCartDisplay();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error al procesar la venta: ' + error.message);
        })
        .finally(() => {
            processBtn.innerHTML = '<i class="fa fa-check"></i> Procesar Venta';
            processBtn.disabled = false;
        });
    });
    
    function formatCurrency(amount) {
        return '$' + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }
});
</script>