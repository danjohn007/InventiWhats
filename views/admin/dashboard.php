<!-- Dashboard Header -->
<div class="bg-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="mb-0">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </h1>
                <p class="mb-0 opacity-75">Panel de Control Administrativo</p>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="opacity-75">
                    Bienvenido, <strong><?php echo htmlspecialchars($user); ?></strong>
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Admin Navigation -->
<div class="bg-light border-bottom">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light p-0">
            <div class="container-fluid px-0">
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="<?php echo SITE_URL; ?>admin">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>admin/products">
                                <i class="fas fa-boxes me-1"></i>Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>admin/inventory">
                                <i class="fas fa-warehouse me-1"></i>Inventario
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>admin/sales">
                                <i class="fas fa-shopping-cart me-1"></i>Ventas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>admin/customers">
                                <i class="fas fa-users me-1"></i>Clientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>admin/reports">
                                <i class="fas fa-chart-bar me-1"></i>Reportes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>pos">
                                <i class="fas fa-cash-register me-1"></i>POS
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>

<!-- Statistics Cards -->
<div class="container my-4">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $error; ?>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card dashboard-card border-0 shadow h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h2 class="text-primary fw-bold"><?php echo $stats['total_products']; ?></h2>
                                <p class="text-muted mb-0">Productos Activos</p>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-boxes fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card dashboard-card danger border-0 shadow h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h2 class="text-danger fw-bold"><?php echo $stats['low_stock']; ?></h2>
                                <p class="text-muted mb-0">Stock Bajo</p>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card dashboard-card success border-0 shadow h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h2 class="text-success fw-bold"><?php echo $stats['today_sales']; ?></h2>
                                <p class="text-muted mb-0">Ventas Hoy</p>
                                <small class="text-success">
                                    <?php echo formatCurrency($stats['today_amount']); ?>
                                </small>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-shopping-cart fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card dashboard-card info border-0 shadow h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h2 class="text-info fw-bold"><?php echo $stats['active_branches']; ?></h2>
                                <p class="text-muted mb-0">Sucursales</p>
                            </div>
                            <div class="ms-3">
                                <i class="fas fa-building fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Quick Actions -->
<div class="container mb-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-2 mb-3">
                            <a href="<?php echo SITE_URL; ?>pos" class="btn btn-success btn-lg d-block">
                                <i class="fas fa-cash-register fa-2x mb-2"></i>
                                <br>Punto de Venta
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="<?php echo SITE_URL; ?>admin/products" class="btn btn-primary btn-lg d-block">
                                <i class="fas fa-boxes fa-2x mb-2"></i>
                                <br>Productos
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="<?php echo SITE_URL; ?>admin/inventory" class="btn btn-info btn-lg d-block">
                                <i class="fas fa-warehouse fa-2x mb-2"></i>
                                <br>Inventario
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="<?php echo SITE_URL; ?>admin/sales" class="btn btn-warning btn-lg d-block">
                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                                <br>Ventas
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="<?php echo SITE_URL; ?>admin/customers" class="btn btn-secondary btn-lg d-block">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <br>Clientes
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="<?php echo SITE_URL; ?>admin/reports" class="btn btn-dark btn-lg d-block">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <br>Reportes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Top Products -->
<div class="container">
    <div class="row">
        <!-- Recent Sales Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-area me-2"></i>Ventas de los Últimos 7 Días
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($stats['recent_sales'])): ?>
                        <canvas id="salesChart" height="300"></canvas>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay datos de ventas recientes</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Top Products -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-star me-2"></i>Productos Más Vendidos
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($stats['top_products'])): ?>
                        <?php foreach ($stats['top_products'] as $index => $product): ?>
                            <div class="d-flex align-items-center mb-3">
                                <div class="badge bg-primary me-3"><?php echo $index + 1; ?></div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold"><?php echo htmlspecialchars($product['name']); ?></div>
                                    <small class="text-muted">
                                        <?php echo $product['quantity_sold']; ?> vendidos - 
                                        <?php echo formatCurrency($product['total_sales']); ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-box fa-2x text-muted mb-2"></i>
                            <p class="text-muted small">No hay datos de productos</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    <?php if (!empty($stats['recent_sales'])): ?>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesData = <?php echo json_encode(array_reverse($stats['recent_sales'])); ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('es-MX', { 
                    month: 'short', 
                    day: 'numeric' 
                });
            }),
            datasets: [{
                label: 'Ventas',
                data: salesData.map(item => item.count),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Monto',
                data: salesData.map(item => item.amount),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Número de Ventas'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Monto ($)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    });
    <?php endif; ?>
});
</script>