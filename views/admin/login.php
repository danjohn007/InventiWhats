<!-- Login Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h3 class="mb-0">
                            <i class="fas fa-user-shield me-2"></i>
                            Acceso Administrativo
                        </h3>
                        <small>Sistema InventiWhats</small>
                    </div>
                    
                    <div class="card-body p-5">
                        <form method="POST" action="<?php echo SITE_URL; ?>admin/login" id="loginForm">
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="username" 
                                           name="username" 
                                           required 
                                           placeholder="Ingrese su usuario"
                                           autocomplete="username">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           required 
                                           placeholder="Ingrese su contraseña"
                                           autocomplete="current-password">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Iniciar Sesión
                                </button>
                            </div>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <small class="text-muted">
                                <strong>Usuarios de Demo:</strong><br>
                                Admin: admin / password<br>
                                Manager: manager1 / password<br>
                                Cajero: cashier1 / password
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Info Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h4 class="mb-4">Niveles de Acceso</h4>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-user-cog fa-2x text-danger mb-3"></i>
                                <h6>Administrador</h6>
                                <small class="text-muted">
                                    Acceso completo al sistema, configuración global
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-user-tie fa-2x text-warning mb-3"></i>
                                <h6>Gerente</h6>
                                <small class="text-muted">
                                    Gestión de sucursal, reportes y personal
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-cash-register fa-2x text-success mb-3"></i>
                                <h6>Cajero</h6>
                                <small class="text-muted">
                                    Punto de venta y operaciones básicas
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
    
    // Form validation
    const form = document.getElementById('loginForm');
    form.addEventListener('submit', function(e) {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        
        if (!username || !password) {
            e.preventDefault();
            alert('Por favor complete todos los campos');
            return false;
        }
    });
});
</script>