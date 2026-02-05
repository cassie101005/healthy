-- ============================================================
-- Script SQL para agregar soporte de notificaciones push
-- Sistema: Healthy - Plataforma de Citas Médicas
-- ============================================================

-- Agregar columna fcm_token a la tabla de usuarios
ALTER TABLE tbl_usuarios 
ADD COLUMN fcm_token VARCHAR(255) NULL 
COMMENT 'Token de Firebase Cloud Messaging para notificaciones push';

-- Agregar columna fcm_token a la tabla de médicos
ALTER TABLE tbl_medicos 
ADD COLUMN fcm_token VARCHAR(255) NULL 
COMMENT 'Token de Firebase Cloud Messaging para notificaciones push';

-- Opcional: Crear índices para mejorar el rendimiento
CREATE INDEX idx_usuarios_fcm_token ON tbl_usuarios(fcm_token);
CREATE INDEX idx_medicos_fcm_token ON tbl_medicos(fcm_token);

-- Verificar que las columnas se crearon correctamente
SELECT 
    'tbl_usuarios' as tabla,
    COLUMN_NAME,
    DATA_TYPE,
    CHARACTER_MAXIMUM_LENGTH
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'tbl_usuarios' AND COLUMN_NAME = 'fcm_token'
UNION ALL
SELECT 
    'tbl_medicos' as tabla,
    COLUMN_NAME,
    DATA_TYPE,
    CHARACTER_MAXIMUM_LENGTH
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'tbl_medicos' AND COLUMN_NAME = 'fcm_token';
