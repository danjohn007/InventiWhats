<!-- Products Header -->
<div class="bg-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="mb-0">
                    <i class="fas fa-boxes me-2"></i>Gestión de Productos
                </h1>
                <p class="mb-0 opacity-75">Administrar catálogo de productos</p>
            </div>
            <div class="col-md-6 text-md-end">
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="fas fa-plus me-2"></i>Nuevo Producto
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Products Content -->
<div class="container my-4">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $error; ?>
        </div>
    <?php else: ?>
        
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label for="categoryFilter" class="form-label">Filtrar por Categoría:</label>
                        <select class="form-select" id="categoryFilter">
                            <option value="">Todas las categorías</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="statusFilter" class="form-label">Estado:</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">Todos</option>
                            <option value="active">Activos</option>
                            <option value="inactive">Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="searchProduct" class="form-label">Buscar:</label>
                        <input type="text" class="form-control" id="searchProduct" 
                               placeholder="Nombre, SKU o código de barras...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="productsTable">
                        <thead class="table-light">
                            <tr>
                                <th>SKU</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Estado</th>
                                <th width="120">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr data-category="<?php echo $product['category_id']; ?>" 
                                data-status="<?php echo $product['status']; ?>">
                                <td>
                                    <span class="fw-bold"><?php echo htmlspecialchars($product['code']); ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-bold">
                                                <?php echo htmlspecialchars($product['name']); ?>
                                            </div>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($product['description']); ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo htmlspecialchars($product['category_name'] ?? 'Sin categoría'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo formatCurrency($product['retail_price']); ?></strong><br>
                                        <small class="text-muted">
                                            Costo: <?php echo formatCurrency($product['cost_price']); ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $stock_class = $product['total_stock'] <= $product['min_stock'] ? 'text-danger' : 'text-success';
                                    ?>
                                    <span class="fw-bold <?php echo $stock_class; ?>">
                                        <?php echo $product['total_stock']; ?>
                                    </span>
                                    <br>
                                    <small class="text-muted">Mín: <?php echo $product['min_stock']; ?></small>
                                </td>
                                <td>
                                    <?php if ($product['status'] === 'active'): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-info" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Nuevo Producto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="productForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="productName" class="form-label">Nombre del Producto *</label>
                                <input type="text" class="form-control" id="productName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="productSku" class="form-label">SKU *</label>
                                <input type="text" class="form-control" id="productSku" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="productDescription" class="form-label">Descripción</label>
                                <textarea class="form-control" id="productDescription" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="productPrice" class="form-label">Precio de Venta *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="productPrice" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="productCost" class="form-label">Costo *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="productCost" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="productCategory" class="form-label">Categoría</label>
                                <select class="form-select" id="productCategory">
                                    <option value="">Seleccionar categoría</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="productMinStock" class="form-label">Stock Mínimo</label>
                                <input type="number" class="form-control" id="productMinStock" value="0" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="productBarcode" class="form-label">Código de Barras</label>
                                <input type="text" class="form-control" id="productBarcode">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="productStatus" class="form-label">Estado</label>
                                <select class="form-select" id="productStatus">
                                    <option value="active">Activo</option>
                                    <option value="inactive">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchProduct');
    const tableRows = document.querySelectorAll('#productsTable tbody tr');
    
    function filterProducts() {
        const categoryValue = categoryFilter.value;
        const statusValue = statusFilter.value;
        const searchValue = searchInput.value.toLowerCase();
        
        tableRows.forEach(row => {
            const category = row.dataset.category;
            const status = row.dataset.status;
            const text = row.textContent.toLowerCase();
            
            const categoryMatch = !categoryValue || category === categoryValue;
            const statusMatch = !statusValue || status === statusValue;
            const searchMatch = !searchValue || text.includes(searchValue);
            
            row.style.display = categoryMatch && statusMatch && searchMatch ? '' : 'none';
        });
    }
    
    categoryFilter.addEventListener('change', filterProducts);
    statusFilter.addEventListener('change', filterProducts);
    searchInput.addEventListener('input', filterProducts);
    
    // Form submission
    document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Funcionalidad de guardar producto pendiente de implementar');
    });
});
</script>