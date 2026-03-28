CREATE DATABASE sistema_reloj;
USE sistema_reloj;

-- Tabla de empleados
CREATE TABLE empleados (
    cedula VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cargo VARCHAR(50)
);

-- Tabla de jornadas (asistencias)
CREATE TABLE asistencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cedula_empleado VARCHAR(20),
    fecha DATE,
    hora_ingreso TIME DEFAULT NULL,
    inicio_refrigerio TIME DEFAULT NULL,
    fin_refrigerio TIME DEFAULT NULL,
    hora_salida TIME DEFAULT NULL,
    FOREIGN KEY (cedula_empleado) REFERENCES empleados(cedula)
);

-- Insertar un empleado de prueba
INSERT INTO empleados (cedula, nombre, cargo) VALUES ('12345', 'Juan Perez', 'Analista');