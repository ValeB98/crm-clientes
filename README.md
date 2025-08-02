# CRM de Clientes y Proyectos

Este proyecto es un **sistema CRM básico** (Customer Relationship Management) desarrollado como una práctica personal para mi portafolio y aprendizaje con SQL y PHP.  
Permite gestionar clientes, proyectos y usuarios con diferentes roles (administrador y empleado), todo desde una interfaz sencilla y amigable a la vista.

## Características

- **Autenticación de usuarios** (login y logout).
- **Roles de usuario**:
  - **Admin**: puede agregar, editar y eliminar clientes y proyectos, asignar empleados, definir prioridad y estado.
  - **Empleado**: puede ver sus proyectos asignados y actualizar el estado (no ve los proyectos de otros empleados).
- **Gestión de clientes**:
  - Agregar, editar y eliminar clientes.
- **Gestión de proyectos**:
  - Asignar proyectos a clientes y empleados.
  - Actualizar estado y prioridad.
  - Eliminar proyectos.
- **Dashboard dinámico**:
  - Vista general de proyectos y clientes según el rol.
- **Diseño oscuro** con CSS básico.

## Tecnologías utilizadas

- **PHP** – lógica del lado del servidor.
- **MySQL** – base de datos para almacenar clientes, usuarios y proyectos.
- **HTML** – estructura de las vistas.
- **CSS** – estilos visuales y diseño del sistema.

## Qué aprendí

- Manejo de sesiones en PHP (session_start, $_SESSION).
- Uso de mysqli para consultas y actualizaciones en MySQL.
- Implementación de roles de usuario con permisos distintos.
- Creación y relación de tablas con llaves foráneas.
- Organización de un proyecto backend con vistas HTML y estilos CSS.
- Investigación en la documentación oficial (aprendiendo nuevos términos para poder comprender).
- Manejo de servidores locales y XAMPP.

> **Nota:** Las contraseñas están en texto plano (solo para pruebas).  
En un entorno real lo ideal es usar funciones como `password_hash()` y `password_verify()`.

## Instalación

1. Clonar el repositorio:
    git clone https://github.com/ValeB98/crm-clientes.git

2. Importar la base de datos crm_clientes.sql en tu servidor MySQL.

3. Configurar las credenciales de conexión en los archivos PHP (por defecto root sin contraseña).

4. Ejecutar el proyecto en un servidor local como XAMPP o Laragon.

## Usuarios de prueba

**Admin**
- Correo: admin@empresa.com
- Contraseña: 1234

**Empleados**
- Correo 1: empleado1@empresa.com
- Correo 2: empleado2@empresa.com
- Contraseñas: 1234