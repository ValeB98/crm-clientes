-- phpmyadmin sql dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- servidor: 127.0.0.1
-- tiempo de generacion: 02-08-2025 a las 06:54:56
-- version del servidor: 10.4.32-mariadb
-- version de php: 8.2.12

set sql_mode = "no_auto_value_on_zero";
start transaction;
set time_zone = "+00:00";

/*!40101 set @old_character_set_client=@@character_set_client */;
/*!40101 set @old_character_set_results=@@character_set_results */;
/*!40101 set @old_collation_connection=@@collation_connection */;
/*!40101 set names utf8mb4 */;

-- base de datos: `crm_clientes`

-- --------------------------------------------------------

-- tabla de clientes, guarda informacion basica de los clientes

create table `clientes` (
  `id` int(11) not null, -- id unico del cliente
  `nombre` varchar(50) not null, -- nombre del cliente
  `correo` varchar(100) default null, -- correo del cliente
  `telefono` varchar(20) default null, -- telefono del cliente
  `empresa` varchar(100) default null, -- nombre de la empresa del cliente
  `ciudad` varchar(50) default null -- ciudad donde esta el cliente
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;

-- datos de ejemplo para la tabla clientes

insert into `clientes` (`id`, `nombre`, `correo`, `telefono`, `empresa`, `ciudad`) values
(9, 'alabama', 'admin@empresa.com', 'aadada', 'pokémon company xd', 'kanto');

-- --------------------------------------------------------

-- tabla de proyectos, cada proyecto pertenece a un cliente y puede estar asignado a un empleado

create table `proyectos` (
  `id` int(11) not null, -- id unico del proyecto
  `cliente_id` int(11) not null, -- relacion con el cliente al que pertenece el proyecto
  `empleado_id` int(11) default null, -- empleado asignado al proyecto
  `nombre` varchar(100) not null, -- nombre del proyecto
  `descripcion` text default null, -- descripcion del proyecto
  `estado` enum('No iniciado','En progreso','Finalizado') default 'No iniciado', -- estado actual del proyecto
  `prioridad` enum('Alta','Media','Baja') default 'Media', -- prioridad del proyecto
  `fecha_inicio` date default null, -- fecha de inicio del proyecto
  `fecha_fin` date default null, -- fecha de finalizacion del proyecto
  `fecha_asignacion` date default curdate(), -- fecha en la que fue asignado el proyecto
  `fecha_limite` date default null -- fecha limite para terminar el proyecto
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;

-- datos de ejemplo para la tabla proyectos

insert into `proyectos` (`id`, `cliente_id`, `empleado_id`, `nombre`, `descripcion`, `estado`, `prioridad`, `fecha_inicio`, `fecha_fin`, `fecha_asignacion`, `fecha_limite`) values
(12, 9, 2, 'adasd', 'asdasd', 'En progreso', 'Media', null, null, '2025-08-01', null);

-- --------------------------------------------------------

-- tabla de usuarios, guarda los administradores y empleados que usan el sistema

create table `usuarios` (
  `id` int(11) not null, -- id unico del usuario
  `nombre` varchar(50) not null, -- nombre del usuario
  `correo` varchar(100) not null, -- correo del usuario (login)
  `contrasena` varchar(255) not null, -- contrasena del usuario
  `rol` enum('admin','empleado') not null -- rol del usuario
) engine=innodb default charset=utf8mb4 collate=utf8mb4_general_ci;

-- datos de ejemplo para la tabla usuarios

insert into `usuarios` (`id`, `nombre`, `correo`, `contrasena`, `rol`) values
(1, 'Alice', 'admin@empresa.com', '1234', 'admin'),
(2, 'Empleado1', 'empleado1@empresa.com', '1234', 'empleado'),
(3, 'Empleado2', 'empleado2@empresa.com', '1234', 'empleado');

-- indices para mejorar las consultas y definir llaves primarias

-- indices de la tabla clientes
alter table `clientes`
  add primary key (`id`);

-- indices de la tabla proyectos
alter table `proyectos`
  add primary key (`id`),
  add key `cliente_id` (`cliente_id`),
  add key `empleado_id` (`empleado_id`);

-- indices de la tabla usuarios
alter table `usuarios`
  add primary key (`id`),
  add unique key `correo` (`correo`);

-- autoincrement de las tablas

alter table `clientes`
  modify `id` int(11) not null auto_increment, auto_increment=10;

alter table `proyectos`
  modify `id` int(11) not null auto_increment, auto_increment=13;

alter table `usuarios`
  modify `id` int(11) not null auto_increment, auto_increment=4;

-- restricciones de integridad referencial (llaves foraneas)

alter table `proyectos`
  add constraint `proyectos_ibfk_1` foreign key (`cliente_id`) references `clientes` (`id`) on delete cascade,
  add constraint `proyectos_ibfk_2` foreign key (`empleado_id`) references `usuarios` (`id`) on delete set null;

commit;

/*!40101 set character_set_client=@old_character_set_client */;
/*!40101 set character_set_results=@old_character_set_results */;
/*!40101 set collation_connection=@old_collation_connection */;