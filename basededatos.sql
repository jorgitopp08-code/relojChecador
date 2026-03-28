CREATE DATABASE IF NOT EXISTS reloj_checador
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE reloj_checador;

CREATE TABLE empleados (
    cedula VARCHAR(20) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    cargo VARCHAR(50) DEFAULT NULL,
    PRIMARY KEY (cedula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE asistencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cedula_empleado VARCHAR(20) NOT NULL,
    fecha DATE NOT NULL,
    hora_ingreso TIME DEFAULT NULL,
    inicio_refrigerio TIME DEFAULT NULL,
    fin_refrigerio TIME DEFAULT NULL,
    hora_salida TIME DEFAULT NULL,
    CONSTRAINT fk_asistencias_empleado
        FOREIGN KEY (cedula_empleado) REFERENCES empleados(cedula)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT uk_asistencia_empleado_fecha UNIQUE (cedula_empleado, fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO empleados (cedula, nombre, cargo) VALUES
('12345', 'Juan Perez', 'Analista'),
('1130616741', 'Jorge Serna', 'Mercaderista');
