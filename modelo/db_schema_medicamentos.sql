-- c:\wamp64\www\hel_2.0\modelo\db_schema_medicamentos.sql

CREATE TABLE IF NOT EXISTS tbl_medicamentos (
    idMedicamento INT AUTO_INCREMENT PRIMARY KEY,
    idPaciente INT NOT NULL,
    vNombre VARCHAR(255) NOT NULL,
    vDosis VARCHAR(100),
    vFrecuencia VARCHAR(100),
    dFechaInicio DATE,
    dFechaFin DATE,
    dFechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idPaciente) REFERENCES tbl_pacientes(idPaciente) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
