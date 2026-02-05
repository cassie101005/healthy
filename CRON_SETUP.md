# Configuración del Sistema de Notificaciones Push - Healthy

## 📋 Requisitos Previos

Antes de configurar el sistema de notificaciones, asegúrate de tener:

1. ✅ Columnas `fcm_token` en las tablas `tbl_usuarios` y `tbl_medicos`
2. ✅ Columna `recordatorio_enviado` en la tabla `tbl_citas`
3. ✅ Firebase Cloud Messaging configurado en tu proyecto
4. ✅ Server Key de Firebase

---

## 🗄️ Paso 1: Actualizar la Base de Datos

Si aún no has ejecutado el script SQL, corre estos comandos en tu base de datos:

```sql
-- Agregar columna fcm_token a la tabla de usuarios
ALTER TABLE tbl_usuarios 
ADD COLUMN fcm_token VARCHAR(255) NULL 
COMMENT 'Token de Firebase Cloud Messaging para notificaciones push';

-- Agregar columna fcm_token a la tabla de médicos
ALTER TABLE tbl_medicos 
ADD COLUMN fcm_token VARCHAR(255) NULL 
COMMENT 'Token de Firebase Cloud Messaging para notificaciones push';

-- Agregar columna para controlar recordatorios enviados
ALTER TABLE tbl_citas
ADD COLUMN recordatorio_enviado TINYINT(1) DEFAULT 0
COMMENT 'Indica si ya se envió el recordatorio de 15 minutos';

-- Crear índices para mejorar el rendimiento
CREATE INDEX idx_usuarios_fcm_token ON tbl_usuarios(fcm_token);
CREATE INDEX idx_medicos_fcm_token ON tbl_medicos(fcm_token);
CREATE INDEX idx_citas_recordatorio ON tbl_citas(recordatorio_enviado);
```

---

## 🔑 Paso 2: Configurar Firebase Server Key

1. Ve a [Firebase Console](https://console.firebase.google.com/)
2. Selecciona tu proyecto "Healthy" (healthy-c83ca)
3. Ve a **Project Settings** (⚙️ icono de configuración)
4. Selecciona la pestaña **Cloud Messaging**
5. Copia el **Server Key** (o genera un nuevo token OAuth 2.0)

6. Abre el archivo `modelo/notificaciones/enviar_notificacion.php`
7. Busca la línea que dice:
   ```php
   $serverKey = 'YOUR_FIREBASE_SERVER_KEY_HERE';
   ```
8. Reemplázala con tu Server Key:
   ```php
   $serverKey = 'AAAA...tu_server_key_aqui';
   ```

---

## ⏰ Paso 3: Configurar CRON Job (Recordatorios de Citas)

El script `verificar_citas_proximas.php` debe ejecutarse **cada minuto** para verificar citas próximas.

### Opción A: CRON en Linux/Mac

1. Abre el crontab:
   ```bash
   crontab -e
   ```

2. Agrega esta línea (ajusta la ruta según tu instalación):
   ```
   * * * * * php /ruta/completa/a/hel_2.0/modelo/notificaciones/verificar_citas_proximas.php >> /var/log/cron_citas.log 2>&1
   ```

   **Ejemplo con WAMP:**
   ```
   * * * * * C:\wamp64\bin\php\php8.2.0\php.exe C:\wamp64\www\hel_2.0\modelo\notificaciones\verificar_citas_proximas.php >> C:\wamp64\logs\cron_citas.log 2>&1
   ```

3. Guarda y cierra el editor

### Opción B: Task Scheduler en Windows

1. Abre **Task Scheduler** (Programador de tareas)
2. Clic en **Create Basic Task** (Crear tarea básica)
3. Nombre: "Healthy - Recordatorios de Citas"
4. Trigger: **Daily** (Diario)
5. Start time: 00:00 (medianoche)
6. Action: **Start a program**
   - Program/script: `C:\wamp64\bin\php\php8.2.0\php.exe`
   - Add arguments: `C:\wamp64\www\hel_2.0\modelo\notificaciones\verificar_citas_proximas.php`
7. En **Settings**, marca:
   - ✅ Run task as soon as possible after a scheduled start is missed
   - ✅ Stop the task if it runs longer than: 3 hours
8. Edita la tarea y ve a **Triggers**
9. Edita el trigger y marca **Repeat task every: 1 minute**
10. Duration: **Indefinitely**
11. Guarda

### Opción C: Script PHP que se ejecuta en cada carga (No recomendado para producción)

Si no puedes configurar CRON, puedes agregar esto al final de `sistema/index.php`:

```php
<?php
// Solo ejecutar cada 60 segundos
$ultimaEjecucion = $_SESSION['ultima_verificacion_citas'] ?? 0;
if (time() - $ultimaEjecucion > 60) {
    include_once "../modelo/notificaciones/verificar_citas_proximas.php";
    $_SESSION['ultima_verificacion_citas'] = time();
}
?>
```

---

## 🧪 Paso 4: Probar el Sistema

### Probar Notificaciones de Chat

1. Inicia sesión como un paciente
2. Abre el chat de una cita
3. Envía un mensaje
4. Verifica que el médico reciba una notificación push

### Probar Notificaciones de Citas

1. Registra una nueva cita
2. Verifica que tanto el paciente como el médico reciban notificaciones

### Probar Recordatorios de Citas

1. Crea una cita para dentro de 15 minutos
2. Espera a que el CRON se ejecute
3. Verifica que ambos (paciente y médico) reciban el recordatorio

### Verificar Tokens en la Base de Datos

```sql
-- Ver tokens de usuarios
SELECT id, vNombre, fcm_token FROM tbl_usuarios WHERE fcm_token IS NOT NULL;

-- Ver tokens de médicos
SELECT id, vNombre, fcm_token FROM tbl_medicos WHERE fcm_token IS NOT NULL;
```

---

## 🔍 Solución de Problemas

### Las notificaciones no llegan

1. **Verifica que el token se esté guardando:**
   - Abre la consola del navegador
   - Busca el mensaje "TOKEN FCM:"
   - Verifica en la base de datos que el token esté guardado

2. **Verifica el Server Key:**
   - Asegúrate de que el Server Key en `enviar_notificacion.php` sea correcto
   - Prueba con un token de prueba desde Firebase Console

3. **Revisa los logs de PHP:**
   - Windows: `C:\wamp64\logs\php_error.log`
   - Linux: `/var/log/apache2/error.log` o `/var/log/php-fpm/error.log`

### El CRON no se ejecuta

1. **Verifica que el CRON esté configurado:**
   ```bash
   crontab -l  # Linux/Mac
   ```

2. **Revisa los logs del CRON:**
   ```bash
   tail -f /var/log/cron_citas.log
   ```

3. **Ejecuta manualmente el script:**
   ```bash
   php /ruta/a/verificar_citas_proximas.php
   ```

### Permisos de notificaciones no se solicitan

1. Verifica que el navegador soporte notificaciones
2. Limpia la caché y cookies del sitio
3. Revisa que el service worker esté registrado:
   - Abre DevTools > Application > Service Workers

---

## 📝 Notas Importantes

- Los tokens FCM pueden expirar o cambiar, el sistema los actualiza automáticamente
- Las notificaciones solo funcionan si el usuario ha dado permiso
- El service worker debe estar registrado para recibir notificaciones en segundo plano
- Los recordatorios se envían una sola vez por cita (gracias a `recordatorio_enviado`)

---

## 🎉 ¡Listo!

Tu sistema de notificaciones push está configurado y funcionando. Los usuarios recibirán notificaciones cuando:

✅ Reciban un nuevo mensaje en el chat  
✅ Se registre una nueva cita  
✅ Una cita esté próxima (15 minutos antes)
