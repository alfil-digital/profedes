/**
 * Opciones
 */
CREATE TABLE opciones
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255),
  descripcion VARCHAR(255),
  icono VARCHAR(255),
  orden INT,
  estado INT DEFAULT 1,
  usuario_abm VARCHAR(255)
);


/**
 * Items
 */
CREATE TABLE items
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  descripcion VARCHAR(255),
  enlace VARCHAR(255),
  opcion_id INT,
  orden INT,
  estado INT DEFAULT 1,
  usuario_abm VARCHAR(255),
  FOREIGN KEY (opcion_id) REFERENCES opciones(id)
);



/**
 * Grupos
 */
CREATE TABLE grupos
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  descripcion VARCHAR(255),
  estado INT DEFAULT 1,  
  usuario_abm VARCHAR(255)
);

INSERT INTO grupos (id,descripcion,usuario_abm) VALUES (1,'Administrador', 'admin');

/**
 * Usuarios
 */
CREATE TABLE usuarios
(
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

INSERT INTO usuarios (usuario,nombre_apellido,grupo_id,clave, usuario_abm) VALUES ('admin','admin',1,'$2y$10$SFTR98e2ru4O4Kf8HexwAOG9jeH9kO9Fc0JDaSHiIFbMrN.Vv19Vi', 'admin');

/**
 * Grupo - Items
 */
CREATE TABLE grupos_items
(
  grupo_id INT NOT NULL,
  item_id INT NOT NULL,
  usuario_abm VARCHAR(255),
  PRIMARY KEY (grupo_id, item_id),
  FOREIGN KEY (grupo_id) REFERENCES grupos(id),
  FOREIGN KEY (item_id) REFERENCES items(id)
);

/**
 * Grupo - Opciones
 */
CREATE TABLE grupos_opciones
(
  grupo_id INT NOT NULL,
  opcion_id INT NOT NULL,
  usuario_abm VARCHAR(255),
  PRIMARY KEY (grupo_id, opcion_id),
  FOREIGN KEY (grupo_id) REFERENCES grupos(id),
  FOREIGN KEY (opcion_id) REFERENCES opciones(id)
);

INSERT INTO opciones VALUES (1, 'Tablas Maestras', 'Administrar', 'fas fa-cog', 1, 1, 'admin');
INSERT INTO items VALUES (1, 'Usuarios', 'administracion/usuarios', 1, 1, 1, 'admin');
INSERT INTO items VALUES (2, 'Items', 'administracion/items', 1, 3, 1, 'admin');
INSERT INTO items VALUES (3, 'Grupos', 'administracion/grupos', 1, 4, 1, 'admin');
INSERT INTO items VALUES (4, 'Opciones de Grupos', 'administracion/opciones_grupos', 1, 5, 1, 'admin');
INSERT INTO items VALUES (5, 'Items de Grupos', 'administracion/items_grupos', 1, 6, 1, 'admin');
INSERT INTO items VALUES (6, 'Opciones', 'administracion/opciones', 1, 2, 1, 'admin');


INSERT INTO grupos_opciones VALUES (1,1);
INSERT INTO grupos_items VALUES(1,1);
INSERT INTO grupos_items VALUES(1,2);
INSERT INTO grupos_items VALUES(1,3);
INSERT INTO grupos_items VALUES(1,4);
INSERT INTO grupos_items VALUES(1,5);
INSERT INTO grupos_items VALUES(1,6);