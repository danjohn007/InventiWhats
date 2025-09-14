<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-boxes me-3"></i>InventiWhats
                </h1>
                <p class="lead mb-4">
                    Sistema de Control de Inventarios Global con POS por Sucursal. 
                    Gestiona tu inventario, ventas y clientes de manera centralizada.
                </p>
                <div class="d-flex gap-3">
                    <a href="<?php echo SITE_URL; ?>inventario" class="btn btn-light btn-lg">
                        <i class="fas fa-search me-2"></i>Ver Inventario
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/login" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Acceso Admin
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-store display-1 text-white-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<?php if (!isset($db_error)): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-building fa-3x text-primary mb-3"></i>
                        <h3 class="fw-bold text-primary"><?php echo $branches_count; ?></h3>
                        <p class="text-muted mb-0">Sucursales Activas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-boxes fa-3x text-success mb-3"></i>
                        <h3 class="fw-bold text-success"><?php echo $products_count; ?></h3>
                        <p class="text-muted mb-0">Productos Disponibles</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-dollar-sign fa-3x text-warning mb-3"></i>
                        <h3 class="fw-bold text-warning"><?php echo formatCurrency($inventory_value); ?></h3>
                        <p class="text-muted mb-0">Valor del Inventario</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart fa-3x text-info mb-3"></i>
                        <h3 class="fw-bold text-info"><?php echo $recent_sales; ?></h3>
                        <p class="text-muted mb-0">Ventas (30 días)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-3">Características Principales</h2>
                <p class="lead text-muted">Todo lo que necesitas para gestionar tu negocio</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-globe fa-3x text-primary mb-3"></i>
                        <h4>Control Global</h4>
                        <p class="text-muted">
                            Administra múltiples sucursales desde un solo lugar. 
                            Vista consolidada de inventario y ventas.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-cash-register fa-3x text-success mb-3"></i>
                        <h4>Punto de Venta</h4>
                        <p class="text-muted">
                            POS completo con múltiples métodos de pago, 
                            facturación electrónica y control de caja.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                        <h4>Reportes y Analytics</h4>
                        <p class="text-muted">
                            Reportes detallados de ventas, inventario y 
                            análisis de rendimiento por sucursal.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x text-warning mb-3"></i>
                        <h4>Programa de Lealtad</h4>
                        <p class="text-muted">
                            Sistema completo de puntos, recompensas y 
                            descuentos para fidelizar clientes.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-warehouse fa-3x text-danger mb-3"></i>
                        <h4>Gestión de Inventario</h4>
                        <p class="text-muted">
                            Control de stock en tiempo real, alertas de 
                            mínimos y máximos, lotes y fechas de vencimiento.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-mobile-alt fa-3x text-secondary mb-3"></i>
                        <h4>Acceso Web</h4>
                        <p class="text-muted">
                            Interfaz responsiva accesible desde cualquier 
                            dispositivo. Consulta de inventario público.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-12">
                <h2 class="display-5 fw-bold mb-3">¿Listo para comenzar?</h2>
                <p class="lead mb-4">
                    Configura tu sistema de inventario y comienza a gestionar tu negocio de manera profesional.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="<?php echo SITE_URL; ?>test-connection" class="btn btn-light btn-lg">
                        <i class="fas fa-cog me-2"></i>Test del Sistema
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/login" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-rocket me-2"></i>Comenzar Ahora
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>