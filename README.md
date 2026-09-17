# RepoAlumnos

Aplicación PHP + MySQL para gestionar alumnos.

## Características

- Alta, edición y eliminación de alumnos
- Campos: nombre, identificación, teléfono y dirección de residencia
- Búsqueda por cualquier campo
- Creación automática de la base de datos y de la tabla `alumnos` si no existen
- Interfaz responsive y limpia

## Requisitos

- PHP 7.4 o superior (con extensión PDO MySQL)
- Acceso a la base de datos MySQL indicada

## Credenciales de base de datos (ya configuradas)

| Parámetro | Valor                          |
|-----------|--------------------------------|
| Host      | mysql-mario99.alwaysdata.net   |
| Usuario   | mario99                        |
| Clave     | luis1009                       |
| Base de datos | mario99_alumnos            |

## Instalación

1. Sube la carpeta `repoalumnos` a tu servidor web (o colócala en la carpeta pública de tu hosting).
2. Asegúrate de que PHP tenga habilitada la extensión `pdo_mysql`.
3. Abre en el navegador la URL correspondiente a `index.php`.

La aplicación creará automáticamente la base de datos y la tabla la primera vez que se ejecute.

## Estructura de archivos

```
repoalumnos/
├── config.php    # Conexión y creación de tablas
├── index.php     # Interfaz principal (CRUD)
├── style.css     # Estilos
└── README.md     # Este archivo
```

## Tabla `alumnos`

| Campo            | Tipo         | Descripción              |
|------------------|--------------|--------------------------|
| id               | INT (PK)     | Auto-incremental         |
| nombre           | VARCHAR(150) | Nombre completo          |
| identificacion   | VARCHAR(50)  | Único (cédula/DNI/etc.)  |
| telefono         | VARCHAR(30)  | Teléfono de contacto     |
| direccion        | TEXT         | Dirección de residencia  |
| fecha_registro   | TIMESTAMP    | Fecha de alta automática |
