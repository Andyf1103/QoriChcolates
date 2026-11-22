# Qori Chocolates

Sistema web para Qori Chocolates construido con Laravel 11. Incluye un catálogo público de productos y un panel administrativo para gestionar categorías, productos, inventario, ventas, reportes y auditoría.

## Características principales

### Sitio público

- Página de inicio con presentación de la empresa.
- Páginas de nosotros, proceso de elaboración y contacto.
- Catálogo público con productos disponibles y categorías.
- Ficha individual de cada producto.
- Carrito de compras con validación de cantidades y disponibilidad de stock.
- Formulario de contacto con límite de solicitudes.
- Mapa de sitio XML.

### Panel administrativo

- Inicio de sesión exclusivo para usuarios administradores.
- Diseño adaptable para escritorio y dispositivos móviles.
- Menú lateral colapsable y modo claro/oscuro.
- Gestión de categorías y productos.
- Control de inventario e historial de movimientos.
- Punto de venta con cálculo de cambio.
- Registro, consulta y anulación de ventas.
- Reportes filtrables y generación de PDF.
- Registro detallado de acciones administrativas.

## Tecnologías

- PHP 8.2 o superior
- Laravel 11
- Blade
- Alpine.js 3
- Vite 5
- Tailwind CSS
- MySql
- DomPDF para reportes en PDF
- PHPUnit para pruebas

## Módulos del sistema

| Módulo | Funciones |
|---|---|
| Dashboard | Resume productos totales, categorías, stock bajo, productos agotados y valor del inventario. También muestra los productos con poco stock. |
| Categorías | Permite crear, buscar, editar y ordenar categorías. Incluye papelera y restauración; no permite eliminar una categoría asociada a productos. |
| Productos | Permite crear, buscar, filtrar, editar y enviar productos a la papelera. La edición incluye imagen, estado activo, categoría y datos comerciales. |
| Inventario | Actualiza el stock con un motivo obligatorio y conserva el stock anterior y el nuevo en el historial de movimientos. |
| Ventas | Gestiona un punto de venta con efectivo o QR, cálculo de cambio, comprobante, consulta de ventas y anulación con reversión del stock. |
| Reportes | Filtra ventas por número, método de pago, estado y fechas. Permite descargar un reporte PDF de los resultados. |
| Auditoría | Registra cambios sobre categorías, productos y ventas. Permite filtrar por acción o entidad y consultar los datos antes y después de cada cambio. |
| Sitio público | Presenta la empresa, el proceso productivo, el catálogo, los productos disponibles y un formulario de contacto. |

## Requisitos

- PHP 8.2 o superior
- Composer
- Node.js y npm
- Extensiones PHP habituales de Laravel
- XAMPP, Laragon o PHP integrado
- SQLite habilitado en PHP

## Instalación en Windows con XAMPP

1. Clone el repositorio y entre al directorio del proyecto:

```bash
git clone URL_DE_TU_REPOSITORIO
cd qori-chocolates
```

2. Instale las dependencias PHP:

```bash
composer install
```

3. Instale las dependencias JavaScript:

```bash
npm install
```

4. Cree el archivo de entorno:

```bash
copy .env.example .env
```

En PowerShell puede usar:

```powershell
Copy-Item .env.example .env
```

5. Genere la clave de la aplicación:

```bash
php artisan key:generate
```

6. Configure la base de datos SQLite. El proyecto incluye `database/database.sqlite`; si no existe, créelo:

```bash
type nul > database\database.sqlite
```

En PowerShell:

```powershell
New-Item database/database.sqlite -ItemType File
```

Asegúrese de que `.env` contenga:

```env
DB_CONNECTION=sqlite
FILESYSTEM_DISK=public
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

7. Ejecute migraciones y datos iniciales:

```bash
php artisan migrate --seed
```

Este comando crea las tablas, el usuario administrador y el catálogo inicial.

8. Cree el enlace público para las imágenes:

```bash
php artisan storage:link
```

Las imágenes administradas por el sistema se almacenan en `storage/app/public/productos`.

9. Compile los recursos frontend:

Para desarrollo:

```bash
npm run dev
```

Para producción:

```bash
npm run build
```

10. Inicie el servidor:

```bash
php artisan serve
```

También puede abrir el proyecto desde Apache/XAMPP. Cuando use el servidor integrado, acceda a `http://127.0.0.1:8000`.

## Acceso administrativo

Ingrese a:

```text
http://127.0.0.1:8000/admin/login
```

Credenciales creadas por el seeder:

- Correo: `admin@qorichocolates.com`
- Contraseña: `Admin123!`

Por seguridad, cambie estas credenciales antes de publicar el sistema.

## Uso de la aplicación

### Sitio público

- `/`: página de inicio.
- `/catalogo`: catálogo de productos disponibles.
- `/nosotros`: información de la empresa.
- `/nuestro-proceso`: proceso de elaboración.
- `/contacto`: formulario de consultas.

### Administración

- `/admin/dashboard`: resumen general.
- `/admin/categorias`: gestión de categorías.
- `/admin/productos`: gestión de productos e inventario.
- `/admin/ventas/pos`: punto de venta.
- `/admin/ventas`: listado y consulta de ventas.
- `/admin/reportes/ventas`: reportes y descarga PDF.
- `/admin/auditoria`: historial de operaciones administrativas.

Todas las rutas bajo `/admin` requieren autenticación y una cuenta con permiso de administrador.

## Reglas de inventario y ventas

- Un producto con 1 a 5 unidades se considera con stock bajo.
- Un producto con 0 unidades se considera agotado.
- No se permite vender una cantidad superior al stock disponible.
- Los productos inactivos no se pueden vender ni aparecen en el catálogo público.
- Las ventas pueden pagarse en efectivo o mediante QR.
- Una venta anulada devuelve las unidades vendidas al inventario.
- Los movimientos de stock registran cantidad, stock anterior, stock nuevo y motivo.

## Comandos útiles

```bash
php artisan migrate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan optimize:clear
php artisan route:list
php artisan view:cache
npm run dev
npm run build
```

Advertencia: `php artisan migrate:fresh --seed` elimina los datos existentes.

## Pruebas

Ejecute el formateador de Laravel y todas las pruebas:

```bash
vendor/bin/pint
php artisan test
```

Puede ejecutar pruebas específicas con:

```bash
php artisan test --filter=AdminInventoryTest
```

## Estructura relevante

```text
app/Http/Controllers/Admin/  Controladores del panel administrativo
app/Http/Controllers/Public/ Controlador del sitio público
app/Models/                  Modelos de categorías, productos, ventas y auditoría
database/migrations/         Migraciones de la base de datos
database/seeders/            Datos iniciales y usuario administrador
resources/views/public/      Plantillas del sitio público
resources/views/admin/       Plantillas del panel administrativo
resources/css/               Estilos de la aplicación
resources/js/                JavaScript y Alpine.js
routes/web.php               Rutas públicas y administrativas
```

## Licencia

Este proyecto utiliza el framework Laravel, distribuido bajo la licencia MIT.
