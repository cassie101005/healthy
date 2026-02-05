// ========================================
// SISTEMA DE NOTIFICACIONES TOAST
// Versión simplificada y directa
// ========================================

// Contenedor global para evitar duplicados
let mensajesMostrados = new Set();

// Crear contenedor de toasts
function crearContenedorToast() {
    if (!document.getElementById('toast-container')) {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 350px;
        `;
        document.body.appendChild(container);
    }
}

// Mostrar notificación toast
function mostrarToast(nombreRemitente, mensaje, idCita, fecha, nombrePaciente = null) {
    // Verificar si estamos en la página del chat de esta cita
    const urlParams = new URLSearchParams(window.location.search);
    const currentChatId = urlParams.get('id');

    // Si estamos viendo el chat de esta cita, no mostrar la notificación
    if (currentChatId && currentChatId == idCita) {
        console.log('🔕 Notificación suprimida: ya estás viendo este chat');
        return;
    }

    // Crear ID único
    const messageId = `${idCita}-${fecha}`;

    // Evitar duplicados
    if (mensajesMostrados.has(messageId)) {
        return;
    }
    mensajesMostrados.add(messageId);

    // Asegurar que existe el contenedor
    crearContenedorToast();
    const container = document.getElementById('toast-container');

    // Crear toast
    const toast = document.createElement('div');
    toast.style.cssText = `
        background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        animation: slideIn 0.3s ease-out;
        position: relative;
        overflow: hidden;
    `;

    // Crear el título con información del chat
    let titulo = nombreRemitente;
    if (nombrePaciente) {
        titulo = `${nombreRemitente} - Cita núm. ${idCita}`;
    }

    toast.innerHTML = `
        <div style="font-size: 24px; flex-shrink: 0;">
            <i class="fas fa-comment-dots"></i>
        </div>
        <div style="flex: 1;">
            <div style="font-weight: bold; font-size: 14px; margin-bottom: 3px;">
                ${escapeHtml(titulo)}
            </div>
            <div style="font-size: 13px; opacity: 0.95; line-height: 1.4; max-height: 40px; overflow: hidden;">
                ${escapeHtml(mensaje)}
            </div>
        </div>
        <button onclick="event.stopPropagation(); this.parentElement.remove();" 
                style="background: rgba(255,255,255,0.2); border: none; color: white; width: 24px; height: 24px; border-radius: 50%; cursor: pointer; font-size: 16px; flex-shrink: 0;">
            ×
        </button>
    `;

    // Click para ir al chat
    toast.onclick = function () {
        window.location.href = `chat?id=${idCita}`;
    };

    // Agregar al contenedor
    container.appendChild(toast);

    // Limitar a máximo 5 toasts en pantalla
    const toasts = container.children;
    if (toasts.length > 5) {
        // Eliminar el toast más antiguo (el primero)
        const oldestToast = toasts[0];
        oldestToast.style.animation = 'slideOut 0.3s ease-out forwards';
        setTimeout(() => oldestToast.remove(), 300);
    }

    // Auto-eliminar después de 5 segundos
    setTimeout(() => {
        if (toast.parentElement) { // Verificar que aún existe
            toast.style.animation = 'slideOut 0.3s ease-out forwards';
            setTimeout(() => toast.remove(), 300);
        }
    }, 5000);

    // Limpiar Set si crece mucho
    if (mensajesMostrados.size > 50) {
        const array = Array.from(mensajesMostrados);
        mensajesMostrados.clear();
        array.slice(-50).forEach(id => mensajesMostrados.add(id));
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Agregar animaciones CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Exponer función globalmente
window.mostrarToast = mostrarToast;

console.log('✅ Sistema de notificaciones toast cargado correctamente');
