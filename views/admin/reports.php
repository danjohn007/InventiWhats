<!-- Reports Header -->
<div class="bg-dark text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Reportes y Análisis
                </h1>
                <p class="mb-0 opacity-75">Inteligencia de negocio y análisis de datos</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="btn-group">
                    <button class="btn btn-light" onclick="exportReport('pdf')">
                        <i class="fas fa-file-pdf me-2"></i>PDF
                    </button>
                    <button class="btn btn-light" onclick="exportReport('excel')">
                        <i class="fas fa-file-excel me-2"></i>Excel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reports Content -->
<div class="container my-4">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $error; ?>
        </div>
    <?php else: ?>
        
        <!-- Report Navigation Tabs -->
        <div class="card mb-4">
            <div class="card-body">
                <ul class="nav nav-pills nav-fill" id="reportTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="sales-tab" data-bs-toggle="pill" 
                                data-bs-target="#sales-reports" type="button" role="tab">
                            <i class="fas fa-chart-line me-2"></i>Ventas
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="products-tab" data-bs-toggle="pill" 
                                data-bs-target="#products-reports" type="button" role="tab">
                            <i class="fas fa-boxes me-2"></i>Productos
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="branches-tab" data-bs-toggle="pill" 
                                data-bs-target="#branches-reports" type="button" role="tab">
                            <i class="fas fa-building me-2"></i>Sucursales
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="inventory-tab" data-bs-toggle="pill" 
                                data-bs-target="#inventory-reports" type="button" role="tab">
                            <i class="fas fa-warehouse me-2"></i>Inventario
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Report Content -->
        <div class="tab-content" id="reportTabsContent">
            
            <!-- Sales Reports Tab -->
            <div class="tab-pane fade show active" id="sales-reports" role="tabpanel">
                
                <!-- Monthly Sales Chart -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-area me-2"></i>Ventas Mensuales (Últimos 12 Meses)
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($monthly_sales)): ?>
                                    <canvas id="monthlySalesChart" height="300"></canvas>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No hay datos de ventas disponibles</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Summary Cards -->
                <?php if (!empty($monthly_sales)): ?>
                <div class="row mb-4">
                    <?php 
                        $current_month = $monthly_sales[0] ?? ['total_sales' => 0, 'total_amount' => 0, 'average_sale' => 0];
                        $total_sales_year = array_sum(array_column($monthly_sales, 'total_sales'));
                        $total_amount_year = array_sum(array_column($monthly_sales, 'total_amount'));
                    ?>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-primary text-white border-0">
                            <div class="card-body text-center">
                                <h3><?php echo $current_month['total_sales']; ?></h3>
                                <p class="mb-0">Ventas Este Mes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-success text-white border-0">
                            <div class="card-body text-center">
                                <h3><?php echo formatCurrency($current_month['total_amount']); ?></h3>
                                <p class="mb-0">Ingresos Este Mes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-info text-white border-0">
                            <div class="card-body text-center">
                                <h3><?php echo $total_sales_year; ?></h3>
                                <p class="mb-0">Ventas Anuales</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card bg-warning text-white border-0">
                            <div class="card-body text-center">
                                <h3><?php echo formatCurrency($current_month['average_sale']); ?></h3>
                                <p class="mb-0">Ticket Promedio</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            <!-- Products Reports Tab -->
            <div class="tab-pane fade" id="products-reports" role="tabpanel">
                
                <!-- Top Selling Products -->
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-trophy me-2"></i>Productos Más Vendidos (Últimos 30 Días)
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($top_products)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Posición</th>
                                                    <th>Producto</th>
                                                    <th>SKU</th>
                                                    <th>Cantidad Vendida</th>
                                                    <th>Ingresos</th>
                                                    <th>Precio Promedio</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($top_products as $index => $product): ?>
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-primary">
                                                            #<?php echo $index + 1; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                                    </td>
                                                    <td>
                                                        <code><?php echo htmlspecialchars($product['code']); ?></code>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-primary">
                                                            <?php echo $product['total_quantity']; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-success">
                                                            <?php echo formatCurrency($product['total_revenue']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php echo formatCurrency($product['avg_price']); ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-boxes fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No hay datos de productos disponibles</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-pie me-2"></i>Distribución de Ventas
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($top_products)): ?>
                                    <canvas id="productsChart" height="250"></canvas>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-chart-pie fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">Sin datos</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Branches Reports Tab -->
            <div class="tab-pane fade" id="branches-reports" role="tabpanel">
                
                <!-- Branch Performance -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-building me-2"></i>Rendimiento por Sucursal (Últimos 30 Días)
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($branch_performance)): ?>
                                    <div class="row">
                                        <?php foreach ($branch_performance as $branch): ?>
                                        <div class="col-lg-6 col-xl-4 mb-4">
                                            <div class="card border">
                                                <div class="card-body text-center">
                                                    <h5 class="card-title">
                                                        <?php echo htmlspecialchars($branch['branch_name']); ?>
                                                    </h5>
                                                    <div class="row text-center">
                                                        <div class="col-4">
                                                            <h4 class="text-primary">
                                                                <?php echo $branch['total_sales'] ?: '0'; ?>
                                                            </h4>
                                                            <small class="text-muted">Ventas</small>
                                                        </div>
                                                        <div class="col-4">
                                                            <h4 class="text-success">
                                                                <?php echo formatCurrency($branch['total_revenue'] ?: 0); ?>
                                                            </h4>
                                                            <small class="text-muted">Ingresos</small>
                                                        </div>
                                                        <div class="col-4">
                                                            <h4 class="text-info">
                                                                <?php echo formatCurrency($branch['avg_sale_value'] ?: 0); ?>
                                                            </h4>
                                                            <small class="text-muted">Promedio</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- Branch Comparison Chart -->
                                    <div class="mt-4">
                                        <canvas id="branchChart" height="200"></canvas>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-building fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No hay datos de sucursales disponibles</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Inventory Reports Tab -->
            <div class="tab-pane fade" id="inventory-reports" role="tabpanel">
                
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>Reportes de Inventario
                            </h6>
                            <p class="mb-0">
                                Esta sección incluiría reportes de rotación de inventario, productos con stock bajo,
                                valor del inventario por sucursal, productos obsoletos, etc.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    <?php endif; ?>
</div>

<!-- Print Report Modal -->
<div class="modal fade" id="printReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-print me-2"></i>Imprimir Reporte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="reportTitle" class="form-label">Título del Reporte</label>
                    <input type="text" class="form-control" id="reportTitle" value="Reporte de InventiWhats">
                </div>
                <div class="mb-3">
                    <label for="reportDateRange" class="form-label">Período</label>
                    <input type="text" class="form-control" id="reportDateRange" readonly>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="includeCharts" checked>
                        <label class="form-check-label" for="includeCharts">
                            Incluir gráficas
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="generateReport()">
                    <i class="fas fa-file-pdf me-2"></i>Generar PDF
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Monthly Sales Chart
    <?php if (!empty($monthly_sales)): ?>
    const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
    const monthlySalesData = <?php echo json_encode(array_reverse($monthly_sales)); ?>;
    
    new Chart(monthlySalesCtx, {
        type: 'bar',
        data: {
            labels: monthlySalesData.map(item => `${item.month_name} ${item.year}`),
            datasets: [{
                label: 'Número de Ventas',
                data: monthlySalesData.map(item => item.total_sales),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                yAxisID: 'y'
            }, {
                label: 'Monto Total',
                data: monthlySalesData.map(item => item.total_amount),
                type: 'line',
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
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
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });
    <?php endif; ?>
    
    // Top Products Pie Chart
    <?php if (!empty($top_products)): ?>
    const productsCtx = document.getElementById('productsChart').getContext('2d');
    const topProductsData = <?php echo json_encode(array_slice($top_products, 0, 5)); ?>;
    
    new Chart(productsCtx, {
        type: 'doughnut',
        data: {
            labels: topProductsData.map(item => item.name),
            datasets: [{
                data: topProductsData.map(item => item.total_quantity),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    <?php endif; ?>
    
    // Branch Performance Chart
    <?php if (!empty($branch_performance)): ?>
    const branchCtx = document.getElementById('branchChart').getContext('2d');
    const branchData = <?php echo json_encode($branch_performance); ?>;
    
    new Chart(branchCtx, {
        type: 'bar',
        data: {
            labels: branchData.map(item => item.branch_name),
            datasets: [{
                label: 'Total Ingresos',
                data: branchData.map(item => item.total_revenue || 0),
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    <?php endif; ?>
    
});

function exportReport(format) {
    alert(`Exportar reporte en formato ${format.toUpperCase()} - Funcionalidad pendiente de implementar`);
}

function generateReport() {
    alert('Generar reporte PDF - Funcionalidad pendiente de implementar');
}
</script>