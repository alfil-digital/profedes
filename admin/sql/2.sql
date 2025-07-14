-- Tabla estados
CREATE TABLE estados (
    id INT PRIMARY KEY AUTO_INCREMENT,
    descripcion VARCHAR(100) NOT NULL,
    label VARCHAR(100) NOT NULL,
    usuario_abm VARCHAR(255),
    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Inserciones de datos para Estados
INSERT INTO estados (descripcion, label, usuario_abm) VALUES ('Activo', 'success', 'admin');
INSERT INTO estados (descripcion, label, usuario_abm) VALUES ('Inactivo', 'secondary', 'admin');
INSERT INTO estados (descripcion, label, usuario_abm) VALUES ('Suspendido', 'warning', 'admin');
INSERT INTO estados (descripcion, label, usuario_abm) VALUES ('Baja', 'danger', 'admin');


-- tabla titulos
CREATE TABLE titulos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    descripcion VARCHAR(100) NOT NULL,
    usuario_abm VARCHAR(255),
    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Inserciones de datos para Titulos
INSERT INTO titulos (descripcion, validez, usuario_abm) VALUES ('Licenciado en Sistemas', 'admin');
INSERT INTO titulos (descripcion, usuario_abm) VALUES ('Ingeniero Civil', 'admin');


-- tabla matriculas
CREATE TABLE matriculas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    descripcion VARCHAR(100) NOT NULL,
    usuario_abm VARCHAR(255),
    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Inserciones de datos para Matriculas
INSERT INTO matriculas (descripcion, usuario_abm) VALUES ('123456', 'admin');


-- Tabla profesionales
CREATE TABLE profesionales (
    id INT PRIMARY KEY AUTO_INCREMENT,
    mail VARCHAR(100),
    cuit VARCHAR(20),
    persona_id INT NOT NULL,
    titulo_id INT,
    matricula_id INT,
    estado_id INT,
    observaciones TEXT,
    domicilio VARCHAR(200),
    usuario_abm VARCHAR(255),
    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_baja TIMESTAMP NULL,
    FOREIGN KEY (persona_id) REFERENCES personas(id),
    FOREIGN KEY (localidad_id) REFERENCES localidades(id),
    FOREIGN KEY (titulo_id) REFERENCES titulos(id),
    FOREIGN KEY (estado_id) REFERENCES estados(id),
    FOREIGN KEY (matricula_id) REFERENCES matriculas(id)
);


-- Inserciones de datos para profesionales y Entidades
INSERT INTO profesionales (persona_id, titulo_id, matricula_id, estado_id,localidad_id) VALUES (1,1,1,1,1);

-- agrego el items "Profesionales" 
INSERT INTO items (descripcion,enlace,opcion_id,orden,estado,usuario_abm) VALUES ('Profesionales','estructura/profesionales',2,1,1,'admin');

-- Asignar Permisos de Grupos a Items (Submenús)
INSERT INTO grupos_items (grupo_id, item_id, usuario_abm) VALUES (1, 10, 'admin');