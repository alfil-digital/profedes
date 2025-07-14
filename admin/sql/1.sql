CREATE SCHEMA colegio_docedes;
USE colegio_docedes;

/**
 * Grupos (Roles)
 */
CREATE TABLE grupos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255),
    estado INT DEFAULT 1,
    usuario_abm VARCHAR(255)
);

-- Insertar Grupos (Roles)
INSERT INTO grupos (descripcion, usuario_abm) VALUES ('Administrador', 'admin');


/**
 * Opciones (Menús principales)
 */
CREATE TABLE opciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255),
    descripcion VARCHAR(255),
    icono VARCHAR(255),
    orden INT,
    estado INT DEFAULT 1,
    usuario_abm VARCHAR(255)
);

-- Insertar Opciones (Menús Principales)
INSERT INTO opciones (titulo,descripcion,icono,orden,estado,usuario_abm) VALUES ('Tablas Maestras','Administrar','fas fa-cog',1,1,'admin');
INSERT INTO opciones (titulo,descripcion,icono,orden,estado,usuario_abm) VALUES ('Sistema','Estructura','fas fa-cog',2,1,'admin');


/**
 * Items (Submenús)
 */
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255),
    enlace VARCHAR(255),
    opcion_id INT,
    orden INT,
    estado INT DEFAULT 1,
    usuario_abm VARCHAR(255),
    FOREIGN KEY (opcion_id) REFERENCES opciones(id)
);

-- Insertar Items (Submenús)
INSERT INTO items (descripcion,enlace,opcion_id,orden,estado,usuario_abm) VALUES ('Usuarios','administracion/usuarios',1,1,1,'admin');
INSERT INTO items (descripcion,enlace,opcion_id,orden,estado,usuario_abm) VALUES ('Opciones','administracion/opciones',1,2,1,'admin');
INSERT INTO items (descripcion,enlace,opcion_id,orden,estado,usuario_abm) VALUES ('Items','administracion/items',1,3,1,'admin');
INSERT INTO items (descripcion,enlace,opcion_id,orden,estado,usuario_abm) VALUES ('Grupos','administracion/grupos',1,4,1,'admin');
INSERT INTO items (descripcion,enlace,opcion_id,orden,estado,usuario_abm) VALUES ('Opciones de Grupos','administracion/opciones_grupos',1,5,1,'admin');
INSERT INTO items (descripcion,enlace,opcion_id,orden,estado,usuario_abm) VALUES ('Items de Grupos','administracion/items_grupos',1,6,1,'admin');
INSERT INTO items (descripcion,enlace,opcion_id,orden,estado,usuario_abm) VALUES ('Personas','administracion/personas',1,7,1,'admin');
INSERT INTO items (descripcion,enlace,opcion_id,orden,estado,usuario_abm) VALUES ('Localidades','administracion/localidades',1,8,1,'admin');
INSERT INTO items (descripcion,enlace,opcion_id,orden,estado,usuario_abm) VALUES ('Provincias','administracion/provincias',1,9,1,'admin');
 

/**
 * Grupos - Opciones (Permisos de Grupos a Menús)
 */
CREATE TABLE grupos_opciones (
    grupo_id INT NOT NULL,
    opcion_id INT NOT NULL,
    usuario_abm VARCHAR(255),
    PRIMARY KEY (grupo_id, opcion_id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(id),
    FOREIGN KEY (opcion_id) REFERENCES opciones(id)
);

-- Asignar Permisos de Grupos a Opciones (Menús)

INSERT INTO grupos_opciones (grupo_id, opcion_id, usuario_abm) VALUES (1, 1, 'admin'); --adminstracion
INSERT INTO grupos_opciones (grupo_id, opcion_id, usuario_abm) VALUES (1, 2, 'admin'); --estructura

/**
 * Grupos - Items (Permisos de Grupos a Submenús)
 */
CREATE TABLE grupos_items (
    grupo_id INT NOT NULL,
    item_id INT NOT NULL,
    usuario_abm VARCHAR(255),
    PRIMARY KEY (grupo_id, item_id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(id),
    FOREIGN KEY (item_id) REFERENCES items(id)
);


-- Asignar Permisos de Grupos a Items (Submenús)
INSERT INTO grupos_items (grupo_id, item_id, usuario_abm) VALUES (1, 1, 'admin');
INSERT INTO grupos_items (grupo_id, item_id, usuario_abm) VALUES (1, 2, 'admin');
INSERT INTO grupos_items (grupo_id, item_id, usuario_abm) VALUES (1, 3, 'admin');
INSERT INTO grupos_items (grupo_id, item_id, usuario_abm) VALUES (1, 4, 'admin');
INSERT INTO grupos_items (grupo_id, item_id, usuario_abm) VALUES (1, 5, 'admin');
INSERT INTO grupos_items (grupo_id, item_id, usuario_abm) VALUES (1, 6, 'admin');
INSERT INTO grupos_items (grupo_id, item_id, usuario_abm) VALUES (1, 7, 'admin');
INSERT INTO grupos_items (grupo_id, item_id, usuario_abm) VALUES (1, 8, 'admin');
INSERT INTO grupos_items (grupo_id, item_id, usuario_abm) VALUES (1, 9, 'admin');


-- Tabla Paises
CREATE TABLE paises (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserciones de datos para Paises
INSERT INTO paises (nombre, created_at) VALUES ('Argentina', NOW());

-- Tabla Provincias
CREATE TABLE provincias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    pais_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pais_id) REFERENCES paises(id)
);

INSERT INTO provincias (nombre, pais_id, created_at) VALUES ('Buenos Aires', 1, NOW());
INSERT INTO provincias (nombre, pais_id, created_at) VALUES ('Corrientes', 1, NOW());
INSERT INTO provincias (nombre, pais_id, created_at) VALUES ('Misiones', 1, NOW());


-- Tabla Localidades
CREATE TABLE localidades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    provincia_id INT NOT NULL,
    codigo_postal VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (provincia_id) REFERENCES provincias(id)
);

INSERT INTO localidades (nombre, provincia_id, codigo_postal) VALUES ('Posadas', (SELECT id FROM provincias WHERE nombre = 'Misiones'), 'N3300');
INSERT INTO localidades (nombre, provincia_id, codigo_postal) VALUES ('Oberá', (SELECT id FROM provincias WHERE nombre = 'Misiones'), 'N3360');
INSERT INTO localidades (nombre, provincia_id, codigo_postal) VALUES ('Eldorado', (SELECT id FROM provincias WHERE nombre = 'Misiones'), 'N3380');
INSERT INTO localidades (nombre, provincia_id, codigo_postal) VALUES ('Puerto Iguazú', (SELECT id FROM provincias WHERE nombre = 'Misiones'), 'N3370');
INSERT INTO localidades (nombre, provincia_id, codigo_postal) VALUES ('Apóstoles', (SELECT id FROM provincias WHERE nombre = 'Misiones'), 'N3350');
INSERT INTO localidades (nombre, provincia_id, codigo_postal) VALUES ('San Vicente', (SELECT id FROM provincias WHERE nombre = 'Misiones'), 'N3364');
INSERT INTO localidades (nombre, provincia_id, codigo_postal) VALUES ('Leandro N. Alem', (SELECT id FROM provincias WHERE nombre = 'Misiones'), 'N3315');
INSERT INTO localidades (nombre, provincia_id, codigo_postal) VALUES ('Jardín América', (SELECT id FROM provincias WHERE nombre = 'Misiones'), 'N3316');
INSERT INTO localidades (nombre, provincia_id, codigo_postal) VALUES ('Montecarlo', (SELECT id FROM provincias WHERE nombre = 'Misiones'), 'N3384');
INSERT INTO localidades (nombre, provincia_id, codigo_postal) VALUES ('Puerto Rico', (SELECT id FROM provincias WHERE nombre = 'Misiones'), 'N3334');

-- Tabla Personas
CREATE TABLE personas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),
    apellido VARCHAR(100),
    dni VARCHAR(20) UNIQUE,
    telefono VARCHAR(20),
    localidad_id INT,
    mail VARCHAR(100),
    cuil VARCHAR(20),
    observaciones TEXT,
    domicilio VARCHAR(200),
    FOREIGN KEY (localidad_id) REFERENCES localidades(id)
);

-- Inserciones de datos para Personas y Entidades
INSERT INTO personas (nombre, apellido, dni, mail) VALUES ('admin','administrador','11111111','admin@admin.com');

-- Tabla Usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(255) UNIQUE,
    persona_id INT,
    clave VARCHAR(255),
    estado INT DEFAULT 1,
    grupo_id INT,
    activo INT DEFAULT 1,
    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_baja TIMESTAMP NULL,
    usuario_abm VARCHAR(255),
    FOREIGN KEY (grupo_id) REFERENCES grupos(id),
    FOREIGN KEY (persona_id) REFERENCES personas(id)
);

-- Inserciones de datos para Usuarios  (demo)
INSERT INTO usuarios (usuario,persona_id,clave,grupo_id,usuario_abm) VALUES ('admin',1,'$2y$10$TwOSQxoMZ8rq4rN.OlYyc.CWMOtpUYjXZEm.sNYKc9ZkJhiIdeluK',1,'administrador');
