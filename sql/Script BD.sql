-- Creaci�n de la base de datos
CREATE DATABASE IF NOT EXISTS gestion_datos;
USE gestion_datos;

-- Creaci�n de la tabla Usuarios
CREATE TABLE Usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    correo VARCHAR(255) NOT NULL UNIQUE,
    contrasenna VARCHAR(255) NOT NULL,
    rol VARCHAR(50) NOT NULL
);

-- Creaci�n de la tabla Historicos
CREATE TABLE Historicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha_carga DATETIME NOT NULL DEFAULT current_timestamp(),
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    departamento VARCHAR(100) NOT NULL,
    can_clientes INT NOT NULL,
    editado TINYINT(1) DEFAULT 0,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id)
);

-- Creaci�n de la tabla Proyecciones
CREATE TABLE Proyecciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre_proyeccion VARCHAR(255) NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    departamento VARCHAR(100) NOT NULL,
    can_clientes INT NOT NULL,
    editado TINYINT(1) DEFAULT 0,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id)
);

-- Creaci�n de la tabla Historico_Reportes
CREATE TABLE Historico_Reportes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre_reporte VARCHAR(255) NOT NULL,
    fecha_creado DATETIME NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id)
);
