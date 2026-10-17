# 📚 BiblioTech · Sistema de Gestión Bibliotecaria

> **Versión Laravel 12 + Tailwind CSS v3**
> Migrado completamente desde la versión original en PHP nativo a una arquitectura moderna, reactiva y modular con Laravel, Tailwind CSS v3, Alpine.js y base de datos MySQL (`db_libros`).

---

## 🌟 Características Principales

1. **Catálogo Digital Interactivo con "Ramillete de Rosas" Animado:**
   - Portada pública con diseño cálido y moderno (paleta Morado Vino `#6B1C52`, Dorado `#D59B34` y Crema `#FDFBF2`).
   - Presentación de libros destacados en el Hero con una animación 3D de **Ramillete de Rosas** que se mece suavemente y "florece" al pasar el cursor.
   - Búsqueda en tiempo real por título, autor, editorial e ISBN.
   - Filtros por materia/categoría y disponibilidad de stock.

2. **Panel de Configuración del Sistema (`/configuracion`):**
   - Panel administrativo dedicado para ajustar reglas de negocio en tiempo real sin modificar código:
     - **Préstamos:** Días de plazo estándar (ej. 14 días) y límite máximo de préstamos activos por usuario.
     - **Reservas:** Máximo de reservas simultáneas y horas de vigencia antes de expirar.
     - **Multas y Sanciones:** Tarifa de mora por día (S/ 0.20), días de gracia de tolerancia y bloqueo automático de usuarios con multas pendientes.

3. **Ciclo Completo de Préstamos y Multas Automatizadas:**
   - Préstamo con descuento atómico de existencias en inventario.
   - Sincronización automática de préstamos vencidos en cada carga del sistema (sin necesidad obligatoria de Cron).
   - Devolución de ejemplares con reposición automática al inventario.
   - Cálculo automático de moras y generación de multas auditables con días de retraso y tarifa aplicada.
   - Módulo de cobranza para marcar multas como `pagadas` o `condonadas`.

4. **Sistema de Roles y Matriz de Permisos:**
   - **Administrador:** Control total (libros, categorías, usuarios, préstamos, multas, configuración y reportes de auditoría).
   - **Bibliotecario:** Operación de mostrador: registro y devolución de préstamos, cobro de multas en caja y confirmación de reservas (catálogo en modo consulta).
   - **Estudiante:** Acceso a catálogo, consulta de sus propios préstamos y multas, y reserva de libros.

5. **Reglas de Integridad y Seguridad:**
   - Protección contra eliminación, bloqueo o despojo de rol del **último administrador activo**.
   - Imposibilidad de eliminar la cuenta propia en uso.
   - Protección contra eliminación de usuarios con historial de préstamos (se ofrece bloqueo).
   - Contraseñas cifradas con algoritmo Bcrypt seguro.

---

## 🚀 Requisitos y Configuración Rápida

### Requisitos
- **PHP:** 8.2 o superior (probado con PHP 8.3) con extensiones `pdo_mysql`, `curl`, `mbstring`, `fileinfo`.
- **Node.js & npm:** Node 18+ (con npm).
- **Base de Datos:** MySQL / MariaDB con la base de datos `db_libros` activa en `127.0.0.1:3306`.

### Credenciales de Base de Datos (`.env`)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_libros
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🔑 Cuentas de Acceso Predefinidas

Todas las cuentas existentes en `db_libros` cuentan con la contraseña: **`password`**

| Rol | Correo Electrónico | Contraseña |
|---|---|---|
| **Administrador** | `admin@bibliotech.test` | `password` |
| **Bibliotecario** | `biblio@bibliotech.test` | `password` |
| **Estudiante** | `carlos@bibliotech.test` | `password` |
| **Estudiante** | `ana@bibliotech.test` | `password` |

---

## 💻 Comandos de Ejecución

Para iniciar el entorno en Windows PowerShell:

```powershell
# 1. Asegurar PHP en el PATH si no está global
$phpDir = "C:\Users\MARX\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe"
$env:Path = "$phpDir;" + $env:Path

# 2. Levantar el servidor web de Laravel
php artisan serve --port=8000

# 3. Compilar los estilos de Tailwind CSS v3
npm run build
```

Una vez levantado, ingresa desde tu navegador a:
👉 **`http://localhost:8000`**

---

## 📂 Estructura Principal del Proyecto

```
Bibliotech-Laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Autenticación y registro
│   │   │   ├── CatalogoController.php      # Explorador público de libros
│   │   │   ├── CategoriaController.php     # CRUD de categorías
│   │   │   ├── ConfiguracionController.php # Ajuste de reglas de negocio
│   │   │   ├── DashboardController.php     # Métricas y reportes
│   │   │   ├── InicioController.php        # Portada con Ramillete de libros
│   │   │   ├── LibroController.php         # Inventario y libros
│   │   │   ├── MultaController.php         # Cobranza y condonación
│   │   │   ├── PrestamoController.php      # Entregas y devoluciones
│   │   │   ├── ReservaController.php       # Apartados temporales
│   │   │   └── UsuarioController.php       # Gestión de cuentas y roles
│   │   └── Middleware/
│   │       ├── CheckRole.php               # Filtro por rol
│   │       └── CheckPermission.php         # Filtro por permisos
│   └── Models/
│       ├── Categoria.php
│       ├── Configuracion.php
│       ├── Libro.php
│       ├── Multa.php
│       ├── Prestamo.php
│       ├── Reserva.php
│       └── User.php                        # Mapeado a la tabla `usuarios`
├── config/
│   ├── biblioteca.php                      # Reglas base del sistema
│   └── permisos.php                        # Matriz de permisos por rol
├── resources/
│   ├── css/
│   │   └── app.css                         # Tailwind v3 + animación bouquet
│   ├── js/
│   │   ├── app.js                          # Alpine.js inicializador
│   │   └── bootstrap.js                    # Axios
│   └── views/
│       ├── auth/                           # Login y Registro
│       ├── catalogo/                       # Catálogo con filtros
│       ├── categorias/                     # Vistas CRUD categorías
│       ├── configuracion/                  # Pantalla de configuración del sistema
│       ├── dashboard/                      # Panel admin, estudiante y reporte
│       ├── layouts/                        # Layouts app.blade y guest.blade
│       ├── libros/                         # Vistas CRUD libros
│       ├── multas/                         # Cobranza de multas
│       ├── prestamos/                      # Préstamos y devoluciones
│       ├── reservas/                       # Reservas de libros
│       ├── usuarios/                       # Vistas CRUD usuarios
│       └── inicio.blade.php                # Hero con Ramillete de Rosas
├── routes/
│   └── web.php                             # Rutas protegidas y públicas
├── AGENTS.md                               # Guía y memoria técnica para IAs / CLI
└── README.md                               # Documentación del proyecto
```

---

## 🌸 Animación "Ramillete de Rosas"

La sección Hero de la página de inicio incluye un componente visual exclusivo donde los libros se despliegan en abanico simulando los pétalos de un ramillete:
- **Balanceo orgánico:** Animación continua `@keyframes bouquet-breathe`.
- **Efecto Hover "Floración":** Al pasar el cursor sobre el conjunto, las cartas se abren con mayor amplitud.
- **Enfoque de lectura:** Al posar el mouse sobre un libro específico, este se eleva en 3D al frente con relieve dorado (`scale-1.16`, `z-50`), invitando al usuario a hacer clic para consultar la ficha o solicitarlo.