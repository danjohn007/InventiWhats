<!-- Page Header -->
<section class="bg-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="mb-0">
                    <i class="fas fa-boxes me-2"></i>Inventario Público
                </h1>
                <p class="mb-0 opacity-75">Consulta nuestros productos disponibles</p>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="opacity-75">
                    <i class="fas fa-clock me-1"></i>
                    Actualizado en tiempo real
                </small>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filters -->
<section class="py-4 bg-light">
    <div class="container">
        <form method="GET" action="<?php echo SITE_URL; ?>inventario" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           name="search" 
                           placeholder="Buscar productos..." 
                           value="<?php echo htmlspecialchars($filters['search']); ?>">
                </div>
            </div>
            
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Todas las Categorías</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" 
                                <?php echo $filters['category'] == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-3">
                <select name="branch" class="form-select">
                    <option value="">Todas las Sucursales</option>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?php echo $branch['id']; ?>" 
                                <?php echo $filters['branch'] == $branch['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($branch['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i>Filtrar
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Products Grid -->
<section class="py-5">
    <div class="container">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($products) && !isset($error)): ?>
            <div class="text-center py-5">
                <i class="fas fa-search fa-5x text-muted mb-4"></i>
                <h3 class="text-muted">No se encontraron productos</h3>
                <p class="text-muted">Intenta ajustar tus filtros de búsqueda</p>
                <a href="<?php echo SITE_URL; ?>inventario" class="btn btn-primary">
                    <i class="fas fa-refresh me-2"></i>Ver Todos los Productos
                </a>
            </div>
        <?php else: ?>
            <!-- Results Summary -->
            <div class="row mb-4">
                <div class="col-12">
                    <p class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Mostrando <?php echo count($products); ?> de <?php echo $pagination['total_items']; ?> productos
                        <?php if ($filters['search']): ?>
                            para "<strong><?php echo htmlspecialchars($filters['search']); ?></strong>"
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card product-card h-100">
                            <div class="product-image">
                                <?php if ($product['image']): ?>
                                    <img src="<?php echo SITE_URL . 'uploads/' . $product['image']; ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                         class="img-fluid">
                                <?php else: ?>
                                    <i class="fas fa-box"></i>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body">
                                <h6 class="card-title fw-bold">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </h6>
                                
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-barcode me-1"></i>
                                    <?php echo htmlspecialchars($product['code']); ?>
                                </p>
                                
                                <?php if ($product['description']): ?>
                                    <p class="card-text small text-muted">
                                        <?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>
                                        <?php echo strlen($product['description']) > 100 ? '...' : ''; ?>
                                    </p>
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <div class="price-retail">
                                        <?php echo formatCurrency($product['retail_price']); ?>
                                    </div>
                                    <div class="price-wholesale">
                                        Mayoreo: <?php echo formatCurrency($product['wholesale_price']); ?>
                                    </div>
                                </div>
                                
                                <!-- Stock Information -->
                                <div class="stock-info">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">Stock Global:</small>
                                        <span class="badge <?php 
                                            if ($product['total_stock'] > 50) echo 'bg-success';
                                            elseif ($product['total_stock'] > 10) echo 'bg-warning';
                                            else echo 'bg-danger';
                                        ?>">
                                            <?php echo $product['total_stock']; ?> unidades
                                        </span>
                                    </div>
                                    
                                    <!-- Branch Stock -->
                                    <?php if ($product['branch_inventory']): ?>
                                        <div class="mt-2">
                                            <small class="text-muted d-block">Por Sucursal:</small>
                                            <?php foreach ($product['branch_inventory'] as $branch_name => $quantity): ?>
                                                <?php if ($quantity > 0): ?>
                                                    <div class="d-flex justify-content-between">
                                                        <small><?php echo htmlspecialchars($branch_name); ?>:</small>
                                                        <small class="fw-bold"><?php echo $quantity; ?></small>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <?php if ($product['total_stock'] > 0): ?>
                                    <small class="text-success">
                                        <i class="fas fa-check-circle me-1"></i>Disponible
                                    </small>
                                <?php else: ?>
                                    <small class="text-danger">
                                        <i class="fas fa-times-circle me-1"></i>Sin Stock
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($pagination['total_pages'] > 1): ?>
                <nav aria-label="Navegación de productos" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagination['current_page'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php 
                                    echo SITE_URL . 'inventario?' . http_build_query(array_merge($filters, ['page' => $pagination['current_page'] - 1])); 
                                ?>">
                                    <i class="fas fa-chevron-left"></i> Anterior
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <?php if ($i == $pagination['current_page']): ?>
                                <li class="page-item active">
                                    <span class="page-link"><?php echo $i; ?></span>
                                </li>
                            <?php elseif (abs($i - $pagination['current_page']) <= 2 || $i == 1 || $i == $pagination['total_pages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php 
                                        echo SITE_URL . 'inventario?' . http_build_query(array_merge($filters, ['page' => $i])); 
                                    ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php elseif (abs($i - $pagination['current_page']) == 3): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php 
                                    echo SITE_URL . 'inventario?' . http_build_query(array_merge($filters, ['page' => $pagination['current_page'] + 1])); 
                                ?>">
                                    Siguiente <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Info Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                        <h5>Actualización en Tiempo Real</h5>
                        <p class="text-muted">
                            El inventario se actualiza automáticamente con cada venta y compra
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-map-marker-alt fa-3x text-success mb-3"></i>
                        <h5>Múltiples Sucursales</h5>
                        <p class="text-muted">
                            Consulta la disponibilidad del producto en cada una de nuestras sucursales
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-tags fa-3x text-warning mb-3"></i>
                        <h5>Precios Mayoreo</h5>
                        <p class="text-muted">
                            Visualiza precios de menudeo y mayoreo para hacer la mejor decisión de compra
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>