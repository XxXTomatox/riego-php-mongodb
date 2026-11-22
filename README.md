====================================================================
SISTEMA DE RIEGO INTELIGENTE (PHP VANILLA + MONGODB)
====================================================================

Este proyecto es una migración de un sistema de riego automatizado 
a PHP Vanilla utilizando MongoDB como base de datos NoSQL.

Permite recibir datos de un Arduino (temperatura, humedad de tierra, 
peso del tanque) y controlar una bomba de agua de forma remota a través 
de una interfaz web protegida por login.

--------------------------------------------------------------------
CARACTERÍSTICAS
--------------------------------------------------------------------
- Backend Ligero: Escrito en PHP puro, sin frameworks pesados.
- Base de Datos NoSQL: Utiliza MongoDB para guardar las lecturas de 
  los sensores de forma flexible.
- Frontend Moderno: Interfaz oscura (Dashboard) con actualizaciones 
  en tiempo real usando JavaScript (Fetch API).
- Autenticación: Sistema de login básico con sesiones de PHP y 
  contraseñas encriptadas (Bcrypt).
- API Bidireccional: El Arduino envía datos a la web (POST) y la web 
  envía órdenes de encendido a la bomba (GET).

--------------------------------------------------------------------
REQUISITOS PREVIOS
--------------------------------------------------------------------
Antes de instalar, asegúrate de tener:
1. PHP 8.1 o superior (con las extensiones mongodb y session).
2. Composer (Gestor de paquetes de PHP).
3. MongoDB Community Server (Corriendo localmente en el puerto 27017).

--------------------------------------------------------------------
INSTALACIÓN PASO A PASO
--------------------------------------------------------------------
1. Clonar el repositorio:
   git clone https://github.com/TuUsuario/TuRepositorio.git
   cd riego-mongodb

2. Instalar dependencias de PHP:
   (El proyecto usa la librería oficial de MongoDB para PHP)
   composer install
   (Si es la primera vez, esto creará la carpeta vendor/)

3. Asegurar que MongoDB esté corriendo:
   En macOS (usando Homebrew), inicia el servicio:
   brew services start mongodb/brew/mongodb-community@8.0
   
   Verifica que esté escuchando en el puerto 27017:
   lsof -i :27017

4. Iniciar el servidor web:
   Para desarrollo local, usa el servidor integrado de PHP. Es MUY 
   IMPORTANTE usar 0.0.0.0 para que tu Arduino pueda conectarse a 
   través de la red local:
   php -S 0.0.0.0:8080

5. Acceder al sistema:
   Abre tu navegador en: http://localhost:8080/login.php
   Credenciales por defecto:
   - Correo: admin@riego.com
   - Contraseña: 12345678

--------------------------------------------------------------------
ESTRUCTURA DEL CÓDIGO
--------------------------------------------------------------------
- db.php: Conexión a MongoDB, creación de colecciones e inserción 
  del usuario administrador por defecto.
- login.php: Formulario de inicio de sesión y validación.
- logout.php: Destruye la sesión y redirige al login.
- index.php: Dashboard principal (protegido). Contiene el HTML, CSS 
  y JS que actualiza los datos cada 5 segundos.
- api_save.php: Endpoint que recibe el JSON del Arduino y lo inserta 
  en la colección sensor_data.
- api_latest.php: Devuelve el último registro guardado para que el 
  Dashboard lo muestre.
- api_toggle.php: Cambia el estado de la bomba (encendido/apagado 
  manual) en la colección configuracion.
- api_status.php: El Arduino consulta este archivo para saber si el 
  usuario presionó el botón de encender en la web.

--------------------------------------------------------------------
CONEXIÓN CON EL ARDUINO
--------------------------------------------------------------------
Para que el hardware se comunique con este proyecto, el código del 
Arduino debe apuntar a la IP de tu computadora (no a localhost) y al 
puerto del servidor PHP.

En tu código de Arduino, ajusta las siguientes variables:
- char serverAddress[] = "192.168.1.11"; // IP local de tu Mac
- int port = 8080; // Puerto del servidor PHP
- String apiPath = "/api_save.php"; // Ruta para guardar datos

Y en la función que consulta el estado de la bomba, la ruta debe ser:
- client.get("/api_status.php");

--------------------------------------------------------------------
NOTAS IMPORTANTES
--------------------------------------------------------------------
1. Seguridad: Este código es una versión simplificada para aprendizaje. 
   Para un entorno de producción real, se recomienda usar tokens CSRF, 
   HTTPS y variables de entorno.
2. Firewall: Si el Arduino no puede conectarse (Error HTTP -3), revisa 
   que el firewall de tu Mac permita conexiones al puerto 8080.
3. MongoDB: Las contraseñas de los usuarios se guardan hasheadas con 
   password_hash() de PHP.

--------------------------------------------------------------------
LICENCIA
--------------------------------------------------------------------
Este proyecto es de código abierto y está disponible bajo la licencia MIT.
====================================================================
