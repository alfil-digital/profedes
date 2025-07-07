# Sistema de Gestión de Plantillas

Este es un sistema de gestión que permite administrar usuarios, personas, grupos y opciones de menú. El sistema está desarrollado en PHP con MySQL y utiliza Bootstrap para la interfaz de usuario.

## Estructura del Sistema

```
/plantilla
├── core/
│   └── env.php              # Configuración del entorno
├── inc/
│   ├── conexion.php         # Manejo de conexión a la base de datos
│   ├── css/                 # Archivos CSS
│   │   ├── datatables.min.css
│   │   ├── estilo.css
│   │   ├── jquery-confirm.min.css
│   │   └── sb-admin-2.min.css
│   └── js/                  # Archivos JavaScript
│       ├── jquery.js
│       ├── jquery-confirm.js
│       ├── jquery.dataTables.min.js
│       └── sb-admin-2.min.js
├── modulos/
│   └── administracion/      # Módulos administrativos
│       ├── grupos/          # Gestión de grupos
│       ├── items/           # Gestión de items de menú
│       ├── items_grupos/    # Asignación de items a grupos
│       ├── opciones/        # Gestión de opciones de menú
│       ├── opciones_grupos/ # Asignación de opciones a grupos
│       ├── personas/        # Gestión de personas
│       └── usuarios/        # Gestión de usuarios
├── vendor/                  # Librerías de terceros
├── index.php               # Página principal
├── login.php              # Página de inicio de sesión
├── logout.php             # Cierre de sesión
├── menu.php               # Menú principal
├── header.php            # Encabezado
└── footer.php            # Pie de página
```

## Funcionalidades Principales

### Gestión de Usuarios

- Crear, editar y eliminar usuarios
- Asignar usuarios a grupos
- Resetear contraseñas
- Bloquear/Desbloquear usuarios
- Asociar usuarios con personas

### Gestión de Personas

- Crear, editar y eliminar personas
- Almacenar información personal:
  - Nombre y apellido
  - DNI
  - CUIL
  - Teléfono
  - Email
  - Domicilio
  - Localidad
  - Observaciones

### Gestión de Grupos

- Crear, editar y eliminar grupos
- Asignar permisos a grupos
- Administrar accesos a módulos

### Gestión de Menú

- Administrar opciones de menú
- Gestionar items de menú
- Asignar permisos por grupo

## Estructura de la Base de Datos

### Tabla: usuarios

```sql
CREATE TABLE usuarios (
    id int AUTO_INCREMENT PRIMARY KEY,
    usuario varchar(255),
    persona_id int,
    clave varchar(255),
    estado int,
    grupo_id int,
    activo int,
    fecha_alta timestamp,
    fecha_baja timestamp,
    usuario_abm varchar(255)
);
```

### Tabla: personas

```sql
CREATE TABLE personas (
    id int AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(100),
    apellido varchar(100),
    dni varchar(20),
    telefono varchar(20),
    id_localidad int,
    mail varchar(100),
    cuil varchar(20),
    observaciones text,
    domicilio varchar(200)
);
```

### Tabla: grupos

```sql
CREATE TABLE grupos (
    id int AUTO_INCREMENT PRIMARY KEY,
    descripcion varchar(255),
    estado int,
    usuario_abm varchar(255)
);
```

## Requisitos del Sistema

- PHP 7.0 o superior
- MySQL 5.7 o superior
- Servidor web Apache
- Extensiones PHP requeridas:
  - mysqli
  - session
  - json

## Configuración

1. Importar la base de datos desde el archivo SQL proporcionado
2. Configurar los parámetros de conexión en `/core/env.php`
3. Asegurar permisos de escritura en directorios necesarios
4. Configurar el servidor web para apuntar al directorio del proyecto

## Seguridad

- Autenticación requerida para acceder al sistema
- Contraseñas encriptadas con PASSWORD_DEFAULT
- Control de acceso basado en grupos
- Validación de formularios tanto en cliente como servidor
- Protección contra inyección SQL
- Control de sesiones

## Tecnologías Utilizadas

- PHP
- MySQL
- JavaScript/jQuery
- Bootstrap 4
- DataTables
- Font Awesome
- jQuery Confirm

## Notas Adicionales

- El sistema utiliza una estructura modular para facilitar el mantenimiento
- Implementa un sistema de permisos basado en grupos
- Incluye validaciones tanto en el frontend como en el backend
- Utiliza consultas preparadas para prevenir inyección SQL
- Maneja sesiones para control de acceso
- Implementa un sistema de mensajes para feedback al usuario
