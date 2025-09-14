<!-- Inventory Header -->
<div class="bg-info text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="mb-0">
                    <i class="fas fa-warehouse me-2"></i>Control de Inventario
                </h1>
                <p class="mb-0 opacity-75">Gestión de stock por sucursal</p>
            </div>
            <div class="col-md-6 text-md-end">
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#adjustInventoryModal">
                    <i class="fas fa-plus-minus me-2"></i>Ajustar Inventario
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Content -->
<div class="container my-4">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $error; ?>
        </div>
    <?php else: ?>
        
        <!-- Low Stock Alerts -->
        <?php if (!empty($low_stock)): ?>
        <div class="alert alert-warning">
            <h5 class="alert-heading">
                <i class="fas fa-exclamation-triangle me-2"></i>Alertas de Stock Bajo
            </h5>
            <p>Los siguientes productos están por debajo del stock mínimo:</p>
            <div class="row">
                <?php foreach ($low_stock as $item): ?>
                <div class="col-md-6 col-lg-4 mb-2">
                    <strong><?php echo htmlspecialchars($item['name']); ?></strong> 
                    en <em><?php echo htmlspecialchars($item['branch_name']); ?></em><br>
                    <small class="text-muted">
                        Stock actual: <?php echo $item['quantity']; ?> | 
                        Mínimo: <?php echo $item['min_stock']; ?>
                    </small>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label for="branchFilter" class="form-label">Filtrar por Sucursal:</label>
                        <select class="form-select" id="branchFilter">
                            <option value="">Todas las sucursales</option>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?php echo $branch['id']; ?>">
                                    <?php echo htmlspecialchars($branch['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="stockFilter" class="form-label">Filtrar por Stock:</label>
                        <select class="form-select" id="stockFilter">
                            <option value="">Todos</option>
                            <option value="low">Stock bajo</option>
                            <option value="normal">Stock normal</option>
                            <option value="out">Sin stock</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="searchInventory" class="form-label">Buscar Producto:</label>
                        <input type="text" class="form-control" id="searchInventory" 
                               placeholder="Nombre o SKU...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="inventoryTable">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>SKU</th>
                                <th>Sucursal</th>
                                <th>Stock Actual</th>
                                <th>Stock Mínimo</th>
                                <th>Estado</th>
                                <th>Valor Inventario</th>
                                <th width="120">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inventory as $item): ?>
                            <?php 
                                $stock_status = '';
                                $stock_class = 'text-success';
                                if ($item['quantity'] <= 0) {
                                    $stock_status = 'Sin stock';
                                    $stock_class = 'text-danger';
                                } elseif ($item['quantity'] <= $item['min_stock']) {
                                    $stock_status = 'Stock bajo';
                                    $stock_class = 'text-warning';
                                } else {
                                    $stock_status = 'Stock normal';
                                }
                                $inventory_value = $item['quantity'] * $item['retail_price'];
                            ?>
                            <tr data-branch="<?php echo $item['branch_id']; ?>" 
                                data-stock="<?php echo $item['quantity'] <= 0 ? 'out' : ($item['quantity'] <= $item['min_stock'] ? 'low' : 'normal'); ?>">
                                <td>
                                    <div class="fw-bold">
                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold"><?php echo htmlspecialchars($item['code']); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo htmlspecialchars($item['branch_name']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold <?php echo $stock_class; ?>">
                                        <?php echo $item['quantity']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo $item['min_stock']; ?>
                                </td>
                                <td>
                                    <?php if ($item['quantity'] <= 0): ?>
                                        <span class="badge bg-danger"><?php echo $stock_status; ?></span>
                                    <?php elseif ($item['quantity'] <= $item['min_stock']): ?>
                                        <span class="badge bg-warning"><?php echo $stock_status; ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><?php echo $stock_status; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo formatCurrency($inventory_value); ?></strong>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" 
                                                title="Ajustar Stock"
                                                onclick="adjustStock(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['product_name']); ?>', <?php echo $item['quantity']; ?>)">
                                            <i class="fas fa-plus-minus"></i>
                                        </button>
                                        <button class="btn btn-outline-info" title="Historial">
                                            <i class="fas fa-history"></i>
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

<!-- Adjust Inventory Modal -->
<div class="modal fade" id="adjustInventoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-minus me-2"></i>Ajustar Inventario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="adjustInventoryForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="adjustProductName" class="form-label">Producto</label>
                        <input type="text" class="form-control" id="adjustProductName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="currentStock" class="form-label">Stock Actual</label>
                        <input type="number" class="form-control" id="currentStock" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="adjustmentType" class="form-label">Tipo de Ajuste</label>
                        <select class="form-select" id="adjustmentType" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="add">Aumentar Stock</option>
                            <option value="subtract">Disminuir Stock</option>
                            <option value="set">Establecer Stock Exacto</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="adjustmentQuantity" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="adjustmentQuantity" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="adjustmentReason" class="form-label">Motivo del Ajuste</label>
                        <select class="form-select" id="adjustmentReason" required>
                            <option value="">Seleccionar motivo</option>
                            <option value="purchase">Compra de mercancía</option>
                            <option value="return">Devolución de cliente</option>
                            <option value="damage">Mercancía dañada</option>
                            <option value="theft">Robo/Pérdida</option>
                            <option value="correction">Corrección de inventario</option>
                            <option value="transfer">Transferencia entre sucursales</option>
                            <option value="other">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="adjustmentNotes" class="form-label">Notas (Opcional)</label>
                        <textarea class="form-control" id="adjustmentNotes" rows="3" 
                                  placeholder="Información adicional sobre el ajuste..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Aplicar Ajuste
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const branchFilter = document.getElementById('branchFilter');
    const stockFilter = document.getElementById('stockFilter');
    const searchInput = document.getElementById('searchInventory');
    const tableRows = document.querySelectorAll('#inventoryTable tbody tr');
    
    function filterInventory() {
        const branchValue = branchFilter.value;
        const stockValue = stockFilter.value;
        const searchValue = searchInput.value.toLowerCase();
        
        tableRows.forEach(row => {
            const branch = row.dataset.branch;
            const stock = row.dataset.stock;
            const text = row.textContent.toLowerCase();
            
            const branchMatch = !branchValue || branch === branchValue;
            const stockMatch = !stockValue || stock === stockValue;
            const searchMatch = !searchValue || text.includes(searchValue);
            
            row.style.display = branchMatch && stockMatch && searchMatch ? '' : 'none';
        });
    }
    
    branchFilter.addEventListener('change', filterInventory);
    stockFilter.addEventListener('change', filterInventory);
    searchInput.addEventListener('input', filterInventory);
    
    // Form submission
    document.getElementById('adjustInventoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Funcionalidad de ajuste de inventario pendiente de implementar');
    });
});

function adjustStock(inventoryId, productName, currentStock) {
    document.getElementById('adjustProductName').value = productName;
    document.getElementById('currentStock').value = currentStock;
    
    // Reset form
    document.getElementById('adjustmentType').value = '';
    document.getElementById('adjustmentQuantity').value = '';
    document.getElementById('adjustmentReason').value = '';
    document.getElementById('adjustmentNotes').value = '';
    
    // Show modal
    new bootstrap.Modal(document.getElementById('adjustInventoryModal')).show();
}
</script>