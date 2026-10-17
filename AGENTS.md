# 🤖 BIBLIOTECH · AGENT QUICK CONTEXT & ARCHITECTURE GUIDE

> **AI / CLI ASSISTANT DIRECTIVE:**
> Lee este archivo antes de realizar cualquier cambio o análisis. Contiene el mapa mental completo, reglas de negocio, esquema de base de datos, credenciales y convenciones para evitar re-escanear todo el código fuente.

---

## 1. 📌 Información Esencial del Proyecto
- **Nombre:** BiblioTech (Sistema Integral de Gestión Bibliotecaria).
- **Stack:**
  - **Framework Backend:** Laravel 12 (PHP 8.3.33).
  - **Frontend / Styling:** Tailwind CSS v3 (v3.4.19) con PostCSS + Autoprefixer (`@tailwindcss/forms`).
  - **Interactivity:** Alpine.js v3.
  - **Asset Bundler:** Vite 8.
  - **Base de Datos:** MySQL (Nombre de la base de datos: `db_libros`).
- **Ruta de PHP en este entorno Windows:**
  `C:\Users\MARX\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe`
- **Ubicación del Proyecto:**
  `C:\Users\MARX\Desktop\Nueva carpeta\Bibliotech-Laravel`

---

## 2. 🗄️ Base de Datos (`db_libros`) y Mapeo Eloquent

La base de datos MySQL local `db_libros` contiene datos de prueba reales pre-cargados.

| Tabla MySQL | Modelo Eloquent | Clave Primaria | Campos Clave / Particularidades |
|---|---|---|---|
| `usuarios` | `App\Models\User` | `id` | `nombre`, `email`, `password_hash` (usar `getAuthPassword()`), `rol` (`administrador`, `bibliotecario`, `estudiante`), `estado` (`activo`, `bloqueado`), `remember_token`. |
| `categorias` | `App\Models\Categoria` | `id` | `nombre` (único), `descripcion`. Relación `hasMany(Libro)`. |
| `libros` | `App\Models\Libro` | `id` | `titulo`, `autor`, `isbn`, `id_categoria`, `editorial`, `anio`, `portada_url`, `sinopsis`, `stock_total`, `stock_disponible`. |
| `prestamos` | `App\Models\Prestamo` | `id` | `id_usuario`, `id_libro`, `id_reserva`, `fecha_prestamo`, `fecha_devolucion_esperada`, `fecha_devolucion_real`, `estado` (`activo`, `vencido`, `devuelto`, `cancelado`), `notas`. |
| `multas` | `App\Models\Multa` | `id` | `id_prestamo`, `id_usuario`, `monto`, `dias_mora`, `tarifa_diaria`, `estado` (`pendiente`, `pagada`, `condonada`), `fecha_generacion`, `fecha_pago`, `motivo`. |
| `reservas` | `App\Models\Reserva` | `id` | `id_usuario`, `id_libro`, `fecha_reserva`, `fecha_expiracion`, `estado` (`pendiente`, `confirmada`, `expirada`, `cancelada`). |
| `configuraciones` | `App\Models\Configuracion` | `id` | Almacena parámetros dinámicos del sistema (`clave`, `valor`, `descripcion`). Métodos estáticos: `Configuracion::get($k, $default)`, `Configuracion::set($k, $v)`, `Configuracion::todas()`. |

---

## 3. ⚙️ Parámetros Dinámicos (`configuraciones`)

Modificables por el Administrador desde `/configuracion` (sin tocar código):
1. `dias_prestamo` (default `14`): Plazo en días asignado automáticamente al crear préstamos.
2. `max_prestamos_activos` (default `3`): Límite de libros en posesión por usuario.
3. `max_reservas_activas` (default `3`): Límite de reservas simultáneas.
4. `horas_vigencia_reserva` (default `48`): Horas para recoger antes de expirar.
5. `multa_por_dia` (default `0.20`): Tarifa en S/ cobrada por cada día de mora.
6. `dias_gracia` (default `0`): Días de tolerancia antes de empezar el cobro de mora.
7. `bloquear_morosos` (default `1`): Impide a usuarios con multas pendientes solicitar nuevos préstamos o reservas.

---

## 4. 🔐 Autenticación, Roles y Credenciales de Prueba

Todas las cuentas existentes en `db_libros` tienen como contraseña: `password`.

| Rol | Correo de Prueba | Contraseña | Capacidades |
|---|---|---|---|
| **Administrador** | `admin@bibliotech.test` | `password` | Acceso total: CRUD libros, categorías, préstamos, multas, usuarios, **configuración** y reporte financiero. |
| **Bibliotecario** | `biblio@bibliotech.test` | `password` | Operativo de mostrador: consulta de libros y categorías (solo lectura), registro y devolución de préstamos, cobro de multas en caja (sin condonación) y confirmación de reservas. |
| **Estudiante** | `carlos@bibliotech.test` | `password` | Consulta de catálogo, ver únicamente sus propios préstamos/multas y crear reservas. |

### Reglas de Integridad Críticas:
- **Regla del último Administrador:** El sistema **prohíbe** eliminar, cambiar el rol o bloquear al último administrador activo (`UsuarioController@update`, `destroy`, `toggleEstado`).
- **Protección de sesión activa:** Un usuario no puede auto-eliminarse ni auto-bloquearse.
- **Protección de préstamos:** No se puede eliminar a un usuario con préstamos históricos (se ofrece bloquearlo).
- **Protección de libros:** No se puede eliminar un libro con préstamos activos o pendientes.
- **Flujo Operativo Guiado por Pedidos de Estudiantes:**
  - El préstamo se genera automáticamente desde `/reservas` al pulsar **"Confirmar y Entregar Libro"** (evitando la creación manual arbitraria).
  - Botón **"Confirmar que devolvieron el libro"** habilitado en `/reservas` y `/prestamos` para que el personal recepcione el ejemplar y reponga el stock en un solo clic.
  - Notificaciones inmediatas en la tarjeta/ficha del libro en el catálogo cuando el estudiante aparta un ejemplar.

---

## 5. 🎨 Diseño Visual y Componentes Destacados

- **Paleta de Identidad BiblioTech:**
  - Primario / Header / Botones clave: Morado vino `#6B1C52` (Tailwind: `brand-800`).
  - Acento / Llamados a la acción: Dorado Mostaza `#D59B34` (Tailwind: `accent-400`).
  - Fondo general: Crema cálido `#FDFBF2` (Tailwind: `cream-100`).
  - Texto principal: `#3B2A33` (Tailwind: `cream-900`).
- **Efecto Visual Especial: "Ramillete de Rosas" (`inicio.blade.php`):**
  - Ubicado en la columna derecha de la sección Hero.
  - Muestra 5 libros en abanico 3D simulando pétalos de un ramillete floral (`bouquet-petal-1` a `5`).
  - Posee una animación continua de balanceo suave (`animate-bouquet`).
  - Al pasar el cursor (`hover`), el ramillete florece abriéndose aún más, y cualquier libro sobre el que se pose el mouse se eleva hacia el frente en 3D con halo dorado y sombra profunda.

---

## 6. 🚀 Comandos Rápidos de Trabajo

```powershell
# Rutas para el entorno actual (en PowerShell)
$phpDir = "C:\Users\MARX\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe"
$env:Path = "$phpDir;" + $env:Path

# Levantar servidor Laravel
php artisan serve --port=8000

# Compilar assets con Vite y Tailwind v3
npm run build

# Modo desarrollo con recarga en vivo
npm run dev

# Listar rutas
php artisan route:list
```