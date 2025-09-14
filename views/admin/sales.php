<!-- Sales Header -->
<div class="bg-warning text-dark py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Gestión de Ventas
                </h1>
                <p class="mb-0 opacity-75">Análisis y seguimiento de ventas</p>
            </div>
            <div class="col-md-6 text-md-end">
                <button class="btn btn-dark" onclick="exportSales()">
                    <i class="fas fa-download me-2"></i>Exportar Datos
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Sales Summary -->
<div class="container my-4">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $error; ?>
        </div>
    <?php else: ?>
        
        <!-- Today's Summary Cards -->
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card bg-primary text-white border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h2 class="fw-bold"><?php echo $today_summary['count']; ?></h2>
                                <p class="mb-0">Ventas de Hoy</p>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card bg-success text-white border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h2 class="fw-bold"><?php echo formatCurrency($today_summary['amount']); ?></h2>
                                <p class="mb-0">Ingresos de Hoy</p>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card bg-info text-white border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h2 class="fw-bold"><?php echo formatCurrency($today_summary['tax_amount']); ?></h2>
                                <p class="mb-0">Impuestos Recaudados</p>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-receipt fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales by Status -->
        <?php if (!empty($status_summary)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-pie me-2"></i>Ventas por Estado (Hoy)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($status_summary as $status): ?>
                            <div class="col-md-3 text-center mb-3">
                                <div class="border rounded p-3">
                                    <?php 
                                        $badge_class = '';
                                        switch($status['status']) {
                                            case 'completed': $badge_class = 'bg-success'; break;
                                            case 'pending': $badge_class = 'bg-warning'; break;
                                            case 'cancelled': $badge_class = 'bg-danger'; break;
                                            default: $badge_class = 'bg-secondary';
                                        }
                                    ?>
                                    <span class="badge <?php echo $badge_class; ?> mb-2">
                                        <?php echo ucfirst($status['status']); ?>
                                    </span>
                                    <br>
                                    <strong><?php echo $status['count']; ?></strong> ventas<br>
                                    <small class="text-muted"><?php echo formatCurrency($status['amount']); ?></small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label for="dateRange" class="form-label">Rango de Fechas:</label>
                        <select class="form-select" id="dateRange">
                            <option value="today">Hoy</option>
                            <option value="yesterday">Ayer</option>
                            <option value="week">Esta Semana</option>
                            <option value="month">Este Mes</option>
                            <option value="custom">Personalizado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="statusFilter" class="form-label">Estado:</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">Todos</option>
                            <option value="completed">Completadas</option>
                            <option value="pending">Pendientes</option>
                            <option value="cancelled">Canceladas</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="branchFilter" class="form-label">Sucursal:</label>
                        <select class="form-select" id="branchFilter">
                            <option value="">Todas</option>
                            <!-- Branch options would be populated from PHP -->
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="searchSale" class="form-label">Buscar:</label>
                        <input type="text" class="form-control" id="searchSale" 
                               placeholder="ID, cliente...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-list me-2"></i>Últimas Ventas
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="salesTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID Venta</th>
                                <th>Fecha/Hora</th>
                                <th>Cliente</th>
                                <th>Sucursal</th>
                                <th>Vendedor</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th width="120">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sales as $sale): ?>
                            <tr data-status="<?php echo $sale['status']; ?>">
                                <td>
                                    <strong>#<?php echo $sale['id']; ?></strong>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y H:i', strtotime($sale['sale_date'])); ?>
                                </td>
                                <td>
                                    <?php if ($sale['customer_name']): ?>
                                        <div>
                                            <strong><?php echo htmlspecialchars($sale['customer_name']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($sale['customer_email'] ?? 'Sin email'); ?></small>
                                        </div>
                                    <?php else: ?>
                                        <em class="text-muted">Cliente ocasional</em>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo htmlspecialchars($sale['branch_name'] ?? 'Sin sucursal'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($sale['user_name'] ?? 'N/A'); ?>
                                </td>
                                <td>
                                    <strong><?php echo formatCurrency($sale['total']); ?></strong><br>
                                    <small class="text-muted">
                                        Subtotal: <?php echo formatCurrency($sale['subtotal']); ?><br>
                                        Impuesto: <?php echo formatCurrency($sale['tax']); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php 
                                        $badge_class = '';
                                        switch($sale['status']) {
                                            case 'completed': $badge_class = 'bg-success'; break;
                                            case 'pending': $badge_class = 'bg-warning'; break;
                                            case 'cancelled': $badge_class = 'bg-danger'; break;
                                            default: $badge_class = 'bg-secondary';
                                        }
                                    ?>
                                    <span class="badge <?php echo $badge_class; ?>">
                                        <?php echo ucfirst($sale['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" 
                                                title="Ver Detalles"
                                                onclick="viewSaleDetails(<?php echo $sale['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-info" 
                                                title="Imprimir Ticket"
                                                onclick="printTicket(<?php echo $sale['id']; ?>)">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <?php if ($sale['status'] === 'pending'): ?>
                                        <button class="btn btn-outline-danger" 
                                                title="Cancelar Venta"
                                                onclick="cancelSale(<?php echo $sale['id']; ?>)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php endif; ?>
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

<!-- Sale Details Modal -->
<div class="modal fade" id="saleDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-receipt me-2"></i>Detalles de Venta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="saleDetailsContent">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                        <p class="text-muted mt-2">Cargando detalles...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="printTicket()">
                    <i class="fas fa-print me-2"></i>Imprimir Ticket
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchSale');
    const tableRows = document.querySelectorAll('#salesTable tbody tr');
    
    function filterSales() {
        const statusValue = statusFilter.value;
        const searchValue = searchInput.value.toLowerCase();
        
        tableRows.forEach(row => {
            const status = row.dataset.status;
            const text = row.textContent.toLowerCase();
            
            const statusMatch = !statusValue || status === statusValue;
            const searchMatch = !searchValue || text.includes(searchValue);
            
            row.style.display = statusMatch && searchMatch ? '' : 'none';
        });
    }
    
    statusFilter.addEventListener('change', filterSales);
    searchInput.addEventListener('input', filterSales);
});

function viewSaleDetails(saleId) {
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('saleDetailsModal'));
    modal.show();
    
    // In a real implementation, this would fetch sale details via AJAX
    setTimeout(() => {
        document.getElementById('saleDetailsContent').innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Detalles de la venta #${saleId} - Funcionalidad pendiente de implementar
            </div>
        `;
    }, 1000);
}

function printTicket(saleId) {
    alert(`Imprimir ticket de venta #${saleId} - Funcionalidad pendiente de implementar`);
}

function cancelSale(saleId) {
    if (confirm('¿Está seguro de que desea cancelar esta venta?')) {
        alert(`Cancelar venta #${saleId} - Funcionalidad pendiente de implementar`);
    }
}

function exportSales() {
    alert('Exportar datos de ventas - Funcionalidad pendiente de implementar');
}
</script>