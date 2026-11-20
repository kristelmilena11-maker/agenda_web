# Agenda Web (PHP y MySQL)

## 1. URL pública
http://agenda-kristel.byethost3.com

## 2. Capturas de pantalla
Pendiente agregar capturas de pantalla de la aplicación y el despliegue.

## 3. Tecnologías utilizadas
- PHP (PDO)
- MySQL / MariaDB
- HTML y CSS
- XAMPP y ByetHost (VistaPanel)

## 4. Instrucciones para instalar el proyecto localmente
1. Abrir XAMPP y encender Apache y MySQL.
2. Entrar a http://localhost/phpmyadmin, crear la base de datos agenda_db e importar agenda_db.sql.
3. Copiar los archivos del proyecto dentro de la carpeta htdocs de XAMPP.
4. Configurar conexion.php con localhost, agenda_db, usuario root y contraseña vacía.
5. Abrir el proyecto desde el navegador en localhost.

## 5. Detalle de las mejoras respecto a la versión anterior
- Conexión a la base de datos configurada con PDO y manejo de errores.
- Base de datos exportada e importada en el servidor remoto.
- Aplicación subida y funcionando en el hosting público.