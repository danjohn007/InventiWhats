<!-- Customers Header -->
<div class="bg-secondary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="mb-0">
                    <i class="fas fa-users me-2"></i>Gestión de Clientes
                </h1>
                <p class="mb-0 opacity-75">Base de datos de clientes y CRM</p>
            </div>
            <div class="col-md-6 text-md-end">
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="fas fa-user-plus me-2"></i>Nuevo Cliente
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Customer Statistics -->
<div class="container my-4">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $error; ?>
        </div>
    <?php else: ?>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card bg-primary text-white border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h2 class="fw-bold"><?php echo $stats['total_customers']; ?></h2>
                                <p class="mb-0">Total Clientes</p>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-users fa-2x opacity-75"></i>
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
                                <h2 class="fw-bold"><?php echo $stats['active_customers']; ?></h2>
                                <p class="mb-0">Clientes Activos</p>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-user-check fa-2x opacity-75"></i>
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
                                <h2 class="fw-bold"><?php echo $stats['new_this_month']; ?></h2>
                                <p class="mb-0">Nuevos este Mes</p>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-user-plus fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label for="statusFilter" class="form-label">Estado:</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">Todos</option>
                            <option value="active">Activos</option>
                            <option value="inactive">Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="purchaseFilter" class="form-label">Actividad:</label>
                        <select class="form-select" id="purchaseFilter">
                            <option value="">Todos</option>
                            <option value="with_purchases">Con compras</option>
                            <option value="no_purchases">Sin compras</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="dateFilter" class="form-label">Registrado:</label>
                        <select class="form-select" id="dateFilter">
                            <option value="">Cualquier fecha</option>
                            <option value="last_month">Último mes</option>
                            <option value="last_3_months">Últimos 3 meses</option>
                            <option value="last_year">Último año</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="searchCustomer" class="form-label">Buscar:</label>
                        <input type="text" class="form-control" id="searchCustomer" 
                               placeholder="Nombre, email, teléfono...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="customersTable">
                        <thead class="table-light">
                            <tr>
                                <th>Cliente</th>
                                <th>Contacto</th>
                                <th>Compras</th>
                                <th>Total Gastado</th>
                                <th>Última Compra</th>
                                <th>Estado</th>
                                <th width="120">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $customer): ?>
                            <tr data-status="<?php echo $customer['status']; ?>" 
                                data-purchases="<?php echo $customer['total_purchases'] > 0 ? 'with_purchases' : 'no_purchases'; ?>"
                                data-created="<?php echo $customer['created_at']; ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">
                                                <?php echo htmlspecialchars($customer['name']); ?>
                                            </div>
                                            <?php if ($customer['tax_id']): ?>
                                                <small class="text-muted">
                                                    RFC: <?php echo htmlspecialchars($customer['tax_id']); ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <?php if ($customer['email']): ?>
                                            <i class="fas fa-envelope me-1 text-muted"></i>
                                            <?php echo htmlspecialchars($customer['email']); ?><br>
                                        <?php endif; ?>
                                        <?php if ($customer['phone']): ?>
                                            <i class="fas fa-phone me-1 text-muted"></i>
                                            <?php echo htmlspecialchars($customer['phone']); ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <span class="badge bg-primary fs-6">
                                            <?php echo $customer['total_purchases']; ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-success">
                                        <?php echo formatCurrency($customer['total_spent']); ?>
                                    </strong>
                                </td>
                                <td>
                                    <?php if ($customer['last_purchase']): ?>
                                        <?php echo date('d/m/Y', strtotime($customer['last_purchase'])); ?>
                                    <?php else: ?>
                                        <em class="text-muted">Nunca</em>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($customer['status'] === 'active'): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" 
                                                title="Editar Cliente"
                                                onclick="editCustomer(<?php echo $customer['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-info" 
                                                title="Ver Historial"
                                                onclick="viewCustomerHistory(<?php echo $customer['id']; ?>)">
                                            <i class="fas fa-history"></i>
                                        </button>
                                        <button class="btn btn-outline-success" 
                                                title="Nueva Venta"
                                                onclick="newSaleForCustomer(<?php echo $customer['id']; ?>)">
                                            <i class="fas fa-shopping-cart"></i>
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

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Nuevo Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="customerForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customerName" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control" id="customerName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customerEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="customerEmail">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customerPhone" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="customerPhone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customerTaxId" class="form-label">RFC</label>
                                <input type="text" class="form-control" id="customerTaxId" 
                                       placeholder="XAXX010101000">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="customerAddress" class="form-label">Dirección</label>
                                <textarea class="form-control" id="customerAddress" rows="3" 
                                          placeholder="Dirección completa del cliente..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customerBirthDate" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="customerBirthDate">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customerStatus" class="form-label">Estado</label>
                                <select class="form-select" id="customerStatus">
                                    <option value="active">Activo</option>
                                    <option value="inactive">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="customerNotes" class="form-label">Notas</label>
                                <textarea class="form-control" id="customerNotes" rows="3" 
                                          placeholder="Información adicional sobre el cliente..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Customer History Modal -->
<div class="modal fade" id="customerHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-history me-2"></i>Historial del Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="customerHistoryContent">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                        <p class="text-muted mt-2">Cargando historial...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(45deg, #007bff, #6610f2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const statusFilter = document.getElementById('statusFilter');
    const purchaseFilter = document.getElementById('purchaseFilter');
    const dateFilter = document.getElementById('dateFilter');
    const searchInput = document.getElementById('searchCustomer');
    const tableRows = document.querySelectorAll('#customersTable tbody tr');
    
    function filterCustomers() {
        const statusValue = statusFilter.value;
        const purchaseValue = purchaseFilter.value;
        const searchValue = searchInput.value.toLowerCase();
        
        tableRows.forEach(row => {
            const status = row.dataset.status;
            const purchases = row.dataset.purchases;
            const text = row.textContent.toLowerCase();
            
            const statusMatch = !statusValue || status === statusValue;
            const purchaseMatch = !purchaseValue || purchases === purchaseValue;
            const searchMatch = !searchValue || text.includes(searchValue);
            
            row.style.display = statusMatch && purchaseMatch && searchMatch ? '' : 'none';
        });
    }
    
    statusFilter.addEventListener('change', filterCustomers);
    purchaseFilter.addEventListener('change', filterCustomers);
    searchInput.addEventListener('input', filterCustomers);
    
    // Form submission
    document.getElementById('customerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Funcionalidad de guardar cliente pendiente de implementar');
    });
});

function editCustomer(customerId) {
    alert(`Editar cliente ID: ${customerId} - Funcionalidad pendiente de implementar`);
}

function viewCustomerHistory(customerId) {
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('customerHistoryModal'));
    modal.show();
    
    // In a real implementation, this would fetch customer history via AJAX
    setTimeout(() => {
        document.getElementById('customerHistoryContent').innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Historial del cliente ID: ${customerId} - Funcionalidad pendiente de implementar
            </div>
        `;
    }, 1000);
}

function newSaleForCustomer(customerId) {
    alert(`Crear nueva venta para cliente ID: ${customerId} - Redirigir a POS`);
    // In a real implementation: window.location.href = `${SITE_URL}pos?customer=${customerId}`;
}
</script>