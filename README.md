# 📚 BiblioTech · Sistema Integral de Gestión Bibliotecaria

> **Versión Laravel 12 + Tailwind CSS v3**
> Plataforma moderna, reactiva y modular diseñada para la administración integral de bibliotecas, préstamos de ejemplares, reservas de estudiantes y control financiero de multas.

---

## 🌟 Características Principales

1. **Vitrina 3D Interactiva de Libros (Hero 3D Showcase Cards):**
   - Portada pública con diseño cálido y de alto impacto visual (paleta Morado Vino `#6B1C52`, Dorado Mostaza `#D59B34` y Crema `#FDFBF2`).
   - Presentación de libros destacados mediante una **Vitrina 3D Interactiva con perspectiva tridimensional**, tarjetas superpuestas, halo ambiental y controles interactivos para ciclar títulos.
   - Insignias flotantes en *Glassmorphism* que reflejan disponibilidad en tiempo real e información clave del ejemplar.
   - Botón de acción principal **"Comenzar Ahora"** con acceso inmediato al portal de autenticación y exploración directa del catálogo.

2. **Catálogo de Libros con Selector de Cuadrícula Adaptable:**
   - Visualización dinámica conmutador de **Vista Grande (3 libros por fila)** o **Modo Compacto (5 por fila)** con persistencia local (`localStorage`).
   - Búsqueda en tiempo real por título, autor, editorial e ISBN.
   - Filtros por materia/categoría y disponibilidad de existencias.
   - Notificaciones y badges visuales para estudiantes sobre ejemplares ya reservados o en posesión.

3. **Panel de Configuración Dinámica (`/configuracion`):**
   - Ajuste de parámetros del sistema en tiempo real sin modificar código:
     - **Préstamos:** Plazo estándar en días (ej. 14 días) y límite máximo de préstamos activos por usuario.
     - **Reservas:** Límite de reservas simultáneas y horas de vigencia antes de su expiración automática.
     - **Multas y Sanciones:** Tarifa diaria de mora en moneda local, días de gracia de tolerancia y opción de bloqueo automático para usuarios morosos.

4. **Ciclo Completo de Préstamos y Cobranza Automatizada:**
   - Descuento atómico de existencias al autorizar el préstamo.
   - Detección de préstamos vencidos en cada interacción del sistema.
   - Devolución en un clic con reposición automática al inventario.
   - Cálculo auditado de moras y módulo de cobranza para marcar multas como `pagadas` o `condonadas`.

5. **Seguridad y Control de Acceso (RBAC):**
   - **Administrador:** Control total de catálogo, usuarios, préstamos, sanciones, configuración del sistema y reportes.
   - **Bibliotecario:** Operación en mostrador: recepción y entrega de libros, confirmación de reservas y cobro de multas en caja.
   - **Estudiante:** Consulta de catálogo público, seguimiento de sus préstamos/multas personales y reserva de libros.
   - **Footers Seguros:** Protección de información sensible en los pies de página (sin divulgación de versiones internas ni esquemas de base de datos).
   - **Regla del último Administrador:** Protección contra eliminación, bloqueo o despojo de rol del último administrador activo del sistema.

---

## 🚀 Requisitos y Configuración Rápida

### Requisitos Previos
- **PHP:** 8.2 o superior (probado con PHP 8.2 y 8.3) con extensiones `pdo_mysql`, `curl`, `mbstring`, `fileinfo`.
- **Composer:** v2.x
- **Node.js & npm:** Node 18+ (para compilar assets con Vite).
- **Base de Datos:** MySQL / MariaDB con la base de datos `db_libros` activa.

### 1. Clonar el repositorio
```bash
git clone https://github.com/marxcuadros24-png/BIBLIOTECH_LARAVEL.git
cd BIBLIOTECH_LARAVEL
```

### 2. Instalar dependencias
```bash
composer install
npm install
```

### 3. Configuración del archivo de entorno (`.env`)
Copia `.env.example` a `.env` y genera la clave:
```bash
cp .env.example .env
php artisan key:generate
```

Asegúrate de configurar tu conexión a MySQL en `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_libros
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
CACHE_STORE=file
```

### 4. Compilar estilos y scripts con Vite
```bash
npm run build
```

---

## 💻 Comandos de Ejecución

### Para desarrollo local:
```bash
php artisan serve --port=8000
```
Ingresa desde tu navegador a:
👉 **`http://localhost:8000`**

### Para acceso en red local (mismo Wi-Fi / LAN desde celular u otra PC):
```bash
php artisan serve --host 0.0.0.0 --port 8000
```
Ingresa desde cualquier dispositivo conectado a tu red mediante tu IP:
👉 **`http://<TU_DIRECCION_IP>:8000`**

---

## 🔑 Cuentas de Acceso Predefinidas

Todas las cuentas de prueba existentes en `db_libros` tienen como contraseña: **`password`**

| Rol | Correo Electrónico | Contraseña | Capacidades |
|---|---|---|---|
| **Administrador** | `admin@bibliotech.test` | `password` | Control total, reportes, usuarios y configuración. |
| **Bibliotecario** | `biblio@bibliotech.test` | `password` | Mostrador: préstamos, devoluciones, cobranza y reservas. |
| **Estudiante** | `carlos@bibliotech.test` | `password` | Consulta de catálogo, historial propio y reservas. |
| **Estudiante** | `ana@bibliotech.test` | `password` | Consulta de catálogo, historial propio y reservas. |

---

## 📂 Estructura del Proyecto

```
BIBLIOTECH_LARAVEL/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Inicio de sesión y registro
│   │   │   ├── CatalogoController.php      # Explorador público de catálogo
│   │   │   ├── CategoriaController.php     # Gestión de categorías
│   │   │   ├── ConfiguracionController.php # Parámetros dinámicos del sistema
│   │   │   ├── DashboardController.php     # Panel y reportes
│   │   │   ├── InicioController.php        # Portada con vitrina 3D
│   │   │   ├── LibroController.php         # Inventario y libros
│   │   │   ├── MultaController.php         # Cobranza y condonación
│   │   │   ├── PrestamoController.php      # Préstamos y devoluciones
│   │   │   ├── ReservaController.php       # Gestión de reservas
│   │   │   └── UsuarioController.php       # Gestión de usuarios y roles
│   │   └── Middleware/
│   │       ├── CheckRole.php               # Validación por rol
│   │       └── CheckPermission.php         # Matriz de permisos
│   └── Models/
│       ├── Categoria.php
│       ├── Configuracion.php
│       ├── Libro.php
│       ├── Multa.php
│       ├── Prestamo.php
│       ├── Reserva.php
│       └── User.php                        # Mapeado a la tabla `usuarios`
├── database/
│   └── db_libros.sql                       # Dump SQL completo de la base de datos
├── resources/
│   ├── css/
│   │   └── app.css                         # Estilos personalizados y animaciones 3D
│   ├── js/
│   │   └── app.js                          # Alpine.js inicializador
│   └── views/
│       ├── auth/                           # Login y Registro
│       ├── catalogo/                       # Catálogo con selector de vistas
│       ├── configuracion/                  # Panel de configuración administrativa
│       ├── dashboard/                      # Panel principal
│       ├── layouts/                        # Plantillas app.blade y guest.blade
│       ├── libros/                         # Vistas de gestión de libros
│       ├── multas/                         # Módulo de multas y mora
│       ├── prestamos/                      # Registro y devolución de préstamos
│       ├── reservas/                       # Gestión de apartados
│       ├── usuarios/                       # Gestión de usuarios
│       └── inicio.blade.php                # Hero con vitrina 3D interactiva
├── routes/
│   └── web.php                             # Enrutamiento de la aplicación
└── README.md                               # Documentación oficial
```

---

## 📄 Licencia

Este proyecto es de código abierto bajo la licencia [MIT](LICENSE).