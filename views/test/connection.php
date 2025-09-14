<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Test de Conexión - InventiWhats'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .test-item {
            border-left: 4px solid #dee2e6;
            transition: all 0.3s ease;
        }
        .test-item.success {
            border-left-color: #28a745;
            background-color: #f8fff9;
        }
        .test-item.failed {
            border-left-color: #dc3545;
            background-color: #fff8f8;
        }
        .status-icon.success {
            color: #28a745;
        }
        .status-icon.failed {
            color: #dc3545;
        }
        .system-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .test-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container my-5">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card test-header border-0 shadow">
                    <div class="card-body text-center py-5">
                        <h1 class="display-4 fw-bold mb-3">
                            <i class="fas fa-cogs me-3"></i>InventiWhats
                        </h1>
                        <p class="lead mb-0">Sistema de Control de Inventarios Global con POS por Sucursal</p>
                        <small class="opacity-75">Test de Conexión y Configuración del Sistema</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overall Status -->
        <div class="row mb-4">
            <div class="col-12">
                <?php 
                $overall_status = isset($overall_status) ? $overall_status : false;
                ?>
                <div class="alert <?php echo $overall_status ? 'alert-success' : 'alert-danger'; ?> border-0 shadow" role="alert">
                    <h4 class="alert-heading">
                        <i class="fas <?php echo $overall_status ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> me-2"></i>
                        Estado General del Sistema
                    </h4>
                    <p class="mb-0">
                        <?php if ($overall_status): ?>
                            <strong>¡Excelente!</strong> Todos los tests han pasado correctamente. El sistema está listo para usar.
                        <?php else: ?>
                            <strong>Atención:</strong> Algunos tests han fallado. Revisa los detalles abajo y corrige los problemas antes de usar el sistema.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Test Results -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-check me-2"></i>Resultados de las Pruebas
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php 
                        $tests = isset($tests) ? $tests : [];
                        if (!empty($tests)):
                            foreach ($tests as $test_key => $test): ?>
                        <div class="test-item <?php echo $test['status'] ? 'success' : 'failed'; ?> p-4 border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold"><?php echo $test['name']; ?></h6>
                                    <p class="mb-1 text-muted"><?php echo $test['message']; ?></p>
                                    <small class="text-muted">Requerido: <?php echo $test['required']; ?></small>
                                </div>
                                <div class="ms-3">
                                    <i class="fas <?php echo $test['status'] ? 'fa-check-circle' : 'fa-times-circle'; ?> fa-2x status-icon <?php echo $test['status'] ? 'success' : 'failed'; ?>"></i>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;
                        else: ?>
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            No se ejecutaron pruebas. Accede desde el controlador para ver los resultados.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- System Info -->
            <div class="col-lg-4">
                <div class="card border-0 shadow">
                    <div class="card-header system-info">
                        <h6 class="mb-0 text-white">
                            <i class="fas fa-info-circle me-2"></i>Información del Sistema
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php 
                        $system_info = isset($system_info) ? $system_info : [];
                        if (!empty($system_info)):
                            foreach ($system_info as $key => $value): ?>
                        <div class="mb-3">
                            <small class="text-muted d-block"><?php echo str_replace('_', ' ', ucfirst($key)); ?></small>
                            <span class="fw-bold"><?php echo $value; ?></span>
                        </div>
                        <?php endforeach;
                        else: ?>
                        <div class="text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            Información no disponible
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card border-0 shadow mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-rocket me-2"></i>Acciones Rápidas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?php $site_url = defined('SITE_URL') ? SITE_URL : '/'; ?>
                            <a href="<?php echo $site_url; ?>" class="btn btn-primary">
                                <i class="fas fa-home me-2"></i>Ir al Inicio
                            </a>
                            <a href="<?php echo $site_url; ?>admin/login" class="btn btn-success">
                                <i class="fas fa-sign-in-alt me-2"></i>Admin Login
                            </a>
                            <a href="<?php echo $site_url; ?>inventario" class="btn btn-info">
                                <i class="fas fa-boxes me-2"></i>Inventario Público
                            </a>
                            <button onclick="window.location.reload()" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-2"></i>Recargar Tests
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Installation Notes -->
        <?php if (!$overall_status): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow">
                    <div class="card-header bg-warning">
                        <h6 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>Notas de Instalación
                        </h6>
                    </div>
                    <div class="card-body">
                        <h6>Para solucionar los problemas encontrados:</h6>
                        <ul class="mb-0">
                            <li>Asegúrate de que MySQL esté ejecutándose y las credenciales sean correctas</li>
                            <li>Ejecuta el script <code>sql/schema.sql</code> para crear la base de datos</li>
                            <li>Ejecuta el script <code>sql/sample_data.sql</code> para insertar datos de ejemplo</li>
                            <li>Verifica que el servidor web tenga permisos de escritura en el directorio</li>
                            <li>Confirma que mod_rewrite esté habilitado en Apache</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                <p class="text-muted">
                    <small>
                        InventiWhats v<?php echo defined('APP_VERSION') ? APP_VERSION : '1.0.0'; ?> - 
                        Sistema desarrollado con PHP <?php echo PHP_VERSION; ?> y MySQL
                    </small>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>