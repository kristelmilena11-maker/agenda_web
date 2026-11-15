# Aplicación Web - Agenda Personal de Contactos

## Descripción del Proyecto
Sistema web desarrollado en PHP y MySQL que permite a los usuarios registrarse, iniciar sesión de forma segura y gestionar una agenda personal de contactos de manera totalmente privada e individual.

## Tecnologías Utilizadas
- **PHP** (Lógica de servidor y PDO para conexión a base de datos).
- **MySQL / MariaDB** (Almacenamiento de datos mediante XAMPP).
- **HTML5 y CSS3** (Diseño de interfaces de usuario).

## Requisitos para su ejecución
- Tener instalado **XAMPP** (o un entorno local con PHP y Apache).
- Navegador web moderno (Chrome, Edge, Firefox).

## Pasos para instalar y ejecutar la aplicación
1. Coloca la carpeta completa del proyecto en el directorio raíz de tu servidor local (`C:\xampp\htdocs\agenda_web\`).
2. Enciende los servicios de **Apache** y **MySQL** desde el panel de control de XAMPP.
3. Abre phpMyAdmin (`http://localhost/phpmyadmin/`), crea una base de datos llamada `agenda_db` y ejecuta el script SQL para crear las tablas `usuarios` y `contactos`.
4. Abre tu navegador y accede a la ruta: `http://localhost/agenda_web/`
