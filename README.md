# Agenda de Estudiantes

Aplicativo web en PHP + MySQL para gestionar una agenda de estudiantes.

## Campos

- Nombre
- Identificación
- Teléfono
- Dirección
- Fecha de registro (automática)

## Funciones

- Registrar estudiantes.
- Consultar estudiantes.
- Buscar por nombre, identificación o teléfono.
- Editar registros.
- Eliminar registros.
- Base de datos con 10 registros iniciales.

## Instalación en AlwaysData

1. Crea o utiliza la base de datos `elindall_repoestudiantes`.
2. Abre phpMyAdmin desde AlwaysData.
3. Ejecuta el contenido de `database.sql`.
4. Sube `config.php`, `index.php` y `style.css` a la carpeta pública de tu sitio.
5. Abre la dirección de tu sitio en el navegador.

## Conexión

El archivo `config.php` contiene los datos de conexión proporcionados para este proyecto.

## GitHub

Puedes subir los archivos del proyecto a un repositorio de GitHub y después desplegarlos en AlwaysData. No publiques las credenciales de la base de datos en un repositorio público; para un proyecto real, usa variables de entorno o un archivo de configuración fuera del repositorio.

## Requisitos

- PHP 7.4 o superior.
- MySQL/MariaDB.
- Extensión `mysqli`.
