# InventiWhats - Sistema de Control de Inventarios Global con POS por Sucursal

Un sistema completo de gestión de inventarios con punto de venta integrado, desarrollado en PHP puro con MySQL y Bootstrap 5.

![InventiWhats](https://img.shields.io/badge/Version-1.0.0-blue)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.1-purple)

## 🚀 Características Principales

### 📊 Administración Global
- Gestión centralizada de múltiples sucursales
- Control de usuarios y roles (Admin, Manager, Cajero)
- Configuración de impuestos, promociones y parámetros globales
- Reportes consolidados de inventarios, ventas y utilidades

### 📦 Control de Inventario
- Alta, baja y edición de productos con códigos únicos
- Control de stock por sucursal y global en tiempo real
- Sistema de kardex y alertas de stock mínimo/máximo
- Gestión de lotes y fechas de caducidad
- Categorización de productos

### 🛒 Compras y Proveedores
- Órdenes de compra y recepción
- Registro y seguimiento de proveedores
- Actualización automática de existencias
- Historial de compras y costos

### 💰 Punto de Venta (POS)
- Interfaz rápida y eficiente para ventas
- Múltiples métodos de pago (efectivo, tarjeta, transferencia, mixto)
- Sistema de descuentos y promociones
- Devoluciones, cancelaciones
- Corte de caja por usuario y turno

### 👥 Clientes y Programa de Lealtad
- Registro de clientes con historial de compras
- Sistema de puntos de lealtad automático
- Canje de recompensas y descuentos
- Seguimiento de compras y preferencias

### 🌐 Sitio Web Público
- Consulta de inventario disponible por sucursal
- Búsqueda por producto, categoría o código
- Precios de menudeo y mayoreo visibles
- Integración con programa de lealtad

## 📋 Requisitos del Sistema

### Servidor Web
- **Apache** 2.4+ con mod_rewrite habilitado
- **PHP** 7.4+ (recomendado PHP 8.0+)
- **MySQL** 5.7+ o **MariaDB** 10.3+

### Extensiones PHP Requeridas
- PDO MySQL
- JSON
- Session
- Hash
- OpenSSL (opcional, para mayor seguridad)

### Navegadores Soportados
- Chrome/Edge 90+
- Firefox 88+
- Safari 14+

## 🛠️ Instalación

### 1. Descargar el Sistema
```bash
git clone https://github.com/danjohn007/InventiWhats.git
cd InventiWhats
```

### 2. Configurar el Servidor Web

#### Apache (.htaccess incluido)
Asegúrate de que mod_rewrite esté habilitado:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Configura el DocumentRoot o copia los archivos a `/var/www/html/`

#### Nginx (opcional)
```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /ruta/a/inventiwhats;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?url=$uri&$args;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### 3. Configurar la Base de Datos

#### Crear la Base de Datos
```sql
CREATE DATABASE inventiwhats CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Ejecutar el Schema
```bash
mysql -u tu_usuario -p inventiwhats < sql/schema.sql
```

#### Insertar Datos de Ejemplo
```bash
mysql -u tu_usuario -p inventiwhats < sql/sample_data.sql
```

### 4. Configurar la Conexión

Edita el archivo `config/config.php` con tus credenciales:
```php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'inventiwhats');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### 5. Configurar Permisos
```bash
sudo chown -R www-data:www-data /ruta/a/inventiwhats
sudo chmod -R 755 /ruta/a/inventiwhats
sudo chmod -R 777 /ruta/a/inventiwhats/uploads  # Si existe
```

## 🔧 Configuración Inicial

### 1. Verificar Instalación
Visita: `http://tu-sitio.com/test-connection`

Esta página verificará:
- ✅ Versión de PHP
- ✅ Conexión a base de datos
- ✅ Estructura de archivos
- ✅ Permisos de escritura
- ✅ Configuración de URLs

### 2. Acceder al Sistema
- **URL Principal:** `http://tu-sitio.com/`
- **Admin Panel:** `http://tu-sitio.com/admin/login`
- **Inventario Público:** `http://tu-sitio.com/inventario`
- **POS:** `http://tu-sitio.com/pos`

### 3. Usuarios Predeterminados

| Usuario | Contraseña | Rol | Descripción |
|---------|------------|-----|-------------|
| `admin` | `password` | Administrador | Acceso completo al sistema |
| `manager1` | `password` | Gerente | Gestión de Sucursal Centro |
| `cashier1` | `password` | Cajero | POS Sucursal Centro |

**⚠️ IMPORTANTE:** Cambia las contraseñas después del primer login.

## 📱 Uso del Sistema

### Panel de Administración
1. Accede con usuario administrador
2. Configura sucursales y usuarios
3. Carga tu catálogo de productos
4. Configura proveedores
5. Establece inventarios iniciales

### Punto de Venta (POS)
1. Accede con usuario cajero
2. Busca productos por nombre o código
3. Agrega productos al carrito
4. Selecciona cliente (opcional)
5. Aplica descuentos si es necesario
6. Procesa el pago

### Inventario Público
- Los clientes pueden consultar productos disponibles
- Ver precios de menudeo y mayoreo
- Consultar stock por sucursal
- Filtrar por categorías

## 🔧 Configuración Avanzada

### Variables de Entorno
Puedes configurar variables adicionales en `config/config.php`:

```php
// Configuración de Aplicación
define('APP_DEBUG', false);  // Producción: false
define('SESSION_TIMEOUT', 3600);  // 1 hora
define('ITEMS_PER_PAGE', 20);
define('MAX_UPLOAD_SIZE', 5242880);  // 5MB

// Configuración de Seguridad
define('HASH_SALT', 'tu-salt-personalizado');
```

### URLs Amigables
El sistema detecta automáticamente la URL base. Si tienes problemas:

1. Verifica que mod_rewrite esté habilitado
2. Confirma que el archivo `.htaccess` esté presente
3. Revisa permisos del directorio

### Base de Datos
La estructura incluye:
- **12 tablas principales** para gestión completa
- **Índices optimizados** para rendimiento
- **Claves foráneas** para integridad referencial
- **Triggers automáticos** para auditoría

## 🔒 Seguridad

### Medidas Implementadas
- ✅ Hash de contraseñas con `password_hash()`
- ✅ Protección contra SQL Injection (PDO)
- ✅ Sanitización de inputs
- ✅ Validación de sesiones
- ✅ Control de acceso por roles
- ✅ Protección de archivos sensibles

### Recomendaciones Adicionales
1. Usa HTTPS en producción
2. Configura backups automáticos
3. Mantén PHP y MySQL actualizados
4. Limita acceso a archivos de configuración
5. Monitorea logs de errores

## 📊 Estructura del Proyecto

```
InventiWhats/
├── assets/
│   ├── css/style.css          # Estilos personalizados
│   └── js/app.js              # JavaScript principal
├── config/
│   └── config.php             # Configuración del sistema
├── controllers/               # Controladores MVC
│   ├── HomeController.php
│   ├── AdminController.php
│   ├── POSController.php
│   └── PublicController.php
├── core/                      # Framework MVC básico
│   ├── Router.php
│   ├── Controller.php
│   └── Model.php
├── models/                    # Modelos de datos
│   ├── User.php
│   └── Product.php
├── sql/                       # Scripts de base de datos
│   ├── schema.sql
│   └── sample_data.sql
├── views/                     # Vistas HTML
│   ├── layouts/main.php
│   ├── home/
│   ├── admin/
│   └── public/
├── .htaccess                  # Configuración Apache
├── index.php                  # Punto de entrada
└── README.md
```

## 🚀 Funcionalidades Futuras

### Versión 1.1 (Próximamente)
- [ ] Facturación electrónica (CFDI México)
- [ ] API REST para integración
- [ ] App móvil nativa
- [ ] Reportes avanzados con gráficas
- [ ] Integración con pagos en línea

### Versión 1.2
- [ ] Sistema de compras en línea
- [ ] Integración con redes sociales
- [ ] Sistema de reservas
- [ ] Análisis predictivo de inventarios

## 🐛 Solución de Problemas

### Error de Conexión a BD
```
Database connection failed
```
**Solución:** Verifica credenciales en `config/config.php`

### Error 404 en URLs
**Solución:** 
1. Verifica mod_rewrite: `apache2ctl -M | grep rewrite`
2. Revisa el archivo `.htaccess`
3. Configura AllowOverride All

### Problemas de Permisos
```
Permission denied
```
**Solución:**
```bash
sudo chown -R www-data:www-data /ruta/proyecto
sudo chmod -R 755 /ruta/proyecto
```

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE.md](LICENSE.md) para detalles.

## 📞 Soporte

- **Email:** soporte@inventiwhats.com
- **Documentación:** [Wiki del proyecto](https://github.com/danjohn007/InventiWhats/wiki)
- **Issues:** [GitHub Issues](https://github.com/danjohn007/InventiWhats/issues)

## 👨‍💻 Autor

**Sistema InventiWhats**
- Website: https://inventiwhats.com
- GitHub: [@danjohn007](https://github.com/danjohn007)

---

⭐ **¡Dale una estrella al proyecto si te fue útil!** ⭐
