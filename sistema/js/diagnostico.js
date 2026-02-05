// Diagnóstico Básico de Entorno
(function () {
    console.log("Ejecutando diagnóstico...");

    // 1. Verificar HTTPS
    var isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
    var isHttps = window.location.protocol === 'https:';

    if (!isLocalhost && !isHttps) {
        alert("ERROR CRÍTICO: Estás accediendo por HTTP. Las notificaciones PUSH requieren HTTPS (candadito verde) para funcionar en servidores externos.");
        return;
    }

    // 2. Verificar Soporte de Service Worker
    if (!('serviceWorker' in navigator)) {
        alert("ERROR: Tu navegador no soporta Service Workers. Prueba con Chrome o Firefox actualizado.");
        return;
    }

    // 3. Verificar acceso al archivo SW
    // Intentamos hacer un fetch HEAD al archivo para ver si existe
    var swPath = '../firebase-messaging-sw.js';
    fetch(swPath, { method: 'HEAD' })
        .then(function (response) {
            if (response.ok) {
                console.log("Archivo SW encontrado.");
            } else {
                alert("ERROR: No se encuentra el archivo 'firebase-messaging-sw.js' en la ruta esperada (" + swPath + "). Verifica que el archivo esté en la carpeta raíz del proyecto.");
            }
        })
        .catch(function (err) {
            console.log("No se pudo verificar el archivo SW (posiblemente CORS o red), pero continuamos.", err);
        });

    console.log("Diagnóstico inicial completado. Si no ves errores, el entorno parece correcto.");
})();
