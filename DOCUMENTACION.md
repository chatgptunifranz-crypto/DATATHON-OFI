# Documentación del Sistema

## Descripción General

Este sistema es una aplicación web desarrollada en Laravel orientada a la gestión policial. Permite la administración de usuarios, órdenes del día, aprobaciones, registros, antecedentes y reparticiones. El sistema implementa roles y permisos para controlar el acceso a las diferentes funcionalidades, y cuenta con un sistema de backup automático que se ejecuta en cada operación CRUD relevante.

### Sistema de Roles y Permisos

Para la gestión de roles y permisos utilizamos el paquete [spatie/laravel-permission](https://spatie.be/docs/laravel-permission/v6/introduction), que permite asignar roles y permisos a los usuarios de manera flexible y segura. La configuración se encuentra en `config/permission.php` y las migraciones correspondientes están en `database/migrations/`.

- Los modelos principales involucrados son `User`, `Spatie\Permission\Models\Role` y `Spatie\Permission\Models\Permission`.
- Los roles y permisos se asignan en los seeders y mediante middleware en las rutas.

### Módulos principales
- **Usuarios**: Gestión de usuarios y roles (administrador, comandante, sargento, policía, etc.).
- **Órdenes del Día**: Creación, listado, búsqueda y estadísticas de órdenes del día.
- **Aprobaciones**: Gestión de aprobaciones sobre las órdenes del día.
- **Registros**: CRUD de registros policiales y sistema de backups automáticos en formato CSV.
- **Antecedentes**: CRUD de antecedentes policiales, búsqueda por CI.
- **Reparticiones**: CRUD de reparticiones policiales.

### Seguridad
- Autenticación de usuarios.
- Control de acceso por roles y permisos usando middleware.

### Sistema de Backup
- El sistema realiza backups automáticos en cada operación CRUD relevante.
- Los backups se pueden listar, descargar y eliminar desde la interfaz de registros.

## Rutas principales

Las rutas principales del sistema están definidas en `routes/web.php` y utilizan controladores tipo resource para los CRUD:

```
Route::resource('usuarios', UsuarioController::class)->middleware('can:manage-users');
Route::resource('ordenes', OrdenDelDiaController::class)->middleware('can:manage-ordenes');
Route::resource('aprobaciones', AprobacionDelDiaController::class)->middleware('can:manage-aprobaciones');
Route::resource('registros', RegistroController::class)->middleware('can:manage-registros');
Route::resource('antecedentes', AntecedenteController::class)->middleware('can:manage-antecedentes');
Route::resource('reparticiones', ReparticionController::class);
```

Además, existen rutas específicas para funcionalidades adicionales como búsqueda, estadísticas y gestión de backups.

## ¿Cómo crear un nuevo CRUD desde 0?

1. **Crear el modelo**:
   ```
   php artisan make:model NombreModelo -m
   ```
   Esto crea el modelo y la migración.

2. **Definir la migración** en `database/migrations` y ejecutar:
   ```
   php artisan migrate
   ```

3. **Crear el controlador resource**:
   ```
   php artisan make:controller NombreModeloController --resource
   ```

4. **Definir la ruta resource** en `routes/web.php`:
   ```php
   Route::resource('nombremodelos', NombreModeloController::class);
   ```

5. **Crear las vistas Blade** en `resources/views/nombremodelos/` (index, create, edit, show, etc.).

6. **Agregar permisos y middleware** si es necesario.

7. **(Opcional) Agregar observer para backups automáticos** si el CRUD requiere respaldo.

---

> Para más detalles, consulta la documentación de Laravel o revisa los controladores y modelos existentes en este proyecto.
