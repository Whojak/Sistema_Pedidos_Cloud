# Sistema de Gestión de Pedidos

Este proyecto es un Sistema de Gestión de Pedidos desarrollado en PHP nativo. El sistema incluye módulos de administración, cliente y repartidor, cada uno con distintas funcionalidades. Además, integra validaciones en JavaScript y utiliza la API de Google Sheets como base de datos. El sistema sigue algunas reglas de la ISO 20027.

## Características

- **Gestión de Usuarios**: Administración de usuarios de distintos tipos (administrador, cliente y repartidor).
- **Login**: Sistema de autenticación para los diferentes usuarios.
- **Validaciones**: Validaciones en el lado del cliente utilizando JavaScript.
- **Integración con Google Sheets**: Utilización de Google Sheets como base de datos.
- **Seguridad**: Las contraseñas están cifradas utilizando `password_hash` de PHP con bcrypt.
- **Recuperación de Contraseñas**: Funcionalidad para recuperar contraseñas mediante correo electrónico.

## Módulos y Credenciales

### Administrador

- **Usuario**: whojak
- **Contraseña**: usuarioXD1

### Cliente

- **Usuario**: Villa12
- **Contraseña**: usuarioXD2

- **Usuario**: naomi123
- **Contraseña**: usuarioXD2

### Repartidor

- **Usuario**: rebeca123
- **Contraseña**: usuarioXD3

## Requisitos

- **PHP**: Versión 7.4 o superior.
- **Composer**: Para la gestión de dependencias.
- **Google API Credentials**: Archivo `credentials.json` para acceder a la API de Google Sheets.
- **Servidor Web**: Apache o Nginx.

## Instalación

1. **Clonar el Repositorio**:
    ```bash
    git clone https://github.com/fundaeh/61.PEDIDOS-Rodrigo_JAVA.Kodigo.git
    
    ```

2. **Instalar Dependencias**:
    ```bash
    composer install
    ```

3. **Configurar Google API Credentials**:
    - Coloca tu archivo `credentials.json` en el directorio `data/`.

4. **Configuración del Servidor Web**:
    - Configura tu servidor web para servir el proyecto. Asegúrate de que el servidor apunte al directorio donde se encuentra el archivo `index.php`.

## Uso

### Login

- Acceder al módulo de Login para registrarte e ingresar a cada modulo del sistema.
- URL: `http://localhost/GestorPedidos/Login/index.php`

- Tener en cuenta que si ocupas otro puerto para correr el servidor web colocarlo.
- URL: `http://localhost:8080/GestorPedidos/Login/index.php`

## Seguridad

- **Contraseñas Cifradas**: Las contraseñas de los usuarios están cifradas utilizando la función `password_hash` de PHP con bcrypt.
- **Recuperación de Contraseñas**: Los usuarios pueden recuperar sus contraseñas mediante el envío de un correo electrónico.
- **Inicio de session**: Cada inicio de session es comprobado correctamente por la base de datos y la contraseña se desencript mediante la funcion `password_verify` .
- **Variable de session**: Se ocupa una variable de session que al momento de cerrar la session destruye la variable para no ingresar mediante url.






