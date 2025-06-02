// export-notification.js
class ExportNotification {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Crear el contenedor de notificaciones si no existe
        if (!document.getElementById('export-notifications')) {
            this.container = document.createElement('div');
            this.container.id = 'export-notifications';
            this.container.className = 'export-notifications-container';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('export-notifications');
        }

        // Agregar estilos CSS
        this.addStyles();
    }

    addStyles() {
        if (!document.getElementById('export-notification-styles')) {
            const styles = document.createElement('style');
            styles.id = 'export-notification-styles';
            styles.textContent = `
                .export-notifications-container {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                    max-width: 400px;
                    font-family: 'Nunito', sans-serif;
                }

                .export-notification {
                    background-color: var(--bg-navbar, #ffffff);
                    color: var(--text-dark, #212529);
                    padding: 16px;
                    border-radius: 8px;
                    margin-bottom: 12px;
                    box-shadow: var(--card-shadow, 0 4px 6px rgba(0, 0, 0, 0.05));
                    position: relative;
                    overflow: hidden;
                    transform: translateX(100%);
                    opacity: 0;
                    transition: all 0.3s var(--transition-normal, ease);
                    border-left: 4px solid var(--primary-color, #4361ee);
                }

                .export-notification.show {
                    transform: translateX(0);
                    opacity: 1;
                }

                .export-notification.success {
                    border-left-color: #38c172;
                    background-color: #ffffff;
                    box-shadow: 0 4px 12px rgba(56, 193, 114, 0.12);
                }

                .export-notification.error {
                    border-left-color: #e3342f;
                    background-color: #ffffff;
                    box-shadow: 0 4px 12px rgba(227, 52, 47, 0.12);
                }

                .export-notification.warning {
                    border-left-color: #ffbe0b;
                    background-color: #ffffff;
                    box-shadow: 0 4px 12px rgba(255, 190, 11, 0.12);
                }

                .export-notification.info {
                    border-left-color: var(--primary-color, #4361ee);
                    background-color: #ffffff;
                    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.12);
                }
                
                .export-notification.exporting {
                    border-left-color: var(--secondary-color, #4cc9f0);
                    border-left-width: 4px;
                    background-color: #ffffff;
                    box-shadow: 0 4px 12px rgba(76, 201, 240, 0.12);
                }
                
                .export-notification.exporting .notification-icon {
                    background-color: rgba(76, 201, 240, 0.15);
                    color: var(--secondary-color, #4cc9f0);
                    animation: pulse-icon 1.5s infinite ease-in-out;
                }
                
                @keyframes pulse-icon {
                    0% { transform: scale(1); opacity: 1; }
                    50% { transform: scale(1.1); opacity: 0.9; }
                    100% { transform: scale(1); opacity: 1; }
                }
                
                .export-notification.exporting .notification-title{
                    color: var(--secondary-color, #4cc9f0);
                    font-weight: 600;
                }
                .export-notification.success .notification-title{
                    color: #38c172;
                    font-weight: 600;
                }

                .export-notification.exporting .notification-message {
                    color: var(--text-dark, #212529);
                    font-weight: 500;
                }

                .export-notification.loading {
                    border-left-color: var(--secondary-color, #4cc9f0);
                    border-left-width: 4px;
                    background-color: #ffffff;
                    box-shadow: 0 4px 12px rgba(76, 201, 240, 0.12);
                }
                
                .export-notification.loading .notification-title {
                    color: var(--secondary-color, #4cc9f0);
                    font-weight: 600;
                }
                
                .export-notification.loading .notification-message {
                    color: var(--text-dark, #212529);
                    font-weight: 500;
                }
                
                .export-notification.loading .notification-icon {
                    background-color: rgba(76, 201, 240, 0.15);
                    color: var(--secondary-color, #4cc9f0);
                }

                /* Remover cualquier overlay que pueda estar afectando el loading */
                .export-notification.loading::after,
                .export-notification.loading::before {
                    display: none !important;
                    content: none !important;
                    background: none !important;
                    opacity: 0 !important;
                }

                /* Asegurar que no haya overlays globales afectando loading */
                .loading::after,
                .loading::before {
                    background-color: transparent !important;
                    opacity: 0 !important;
                    display: none !important;
                }

                /* Forzar visibilidad completa para elementos loading */
                .export-notification.loading {
                    background-color: #ffffff !important;
                    opacity: 1 !important;
                    position: relative !important;
                    z-index: 10000 !important;
                }

                .export-notification.loading * {
                    opacity: 1 !important;
                    visibility: visible !important;
                }

                .notification-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 8px;
                }

                .notification-title {
                    font-weight: 600;
                    font-size: 16px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    color: var(--text-dark, #212529);
                }

                .notification-icon {
                    width: 22px;
                    height: 22px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                    background-color: rgba(var(--bs-primary-rgb, 67, 97, 238), 0.1);
                    color: var(--primary-color, #4361ee);
                }

                .notification-icon.success {
                    background-color: rgba(56, 193, 114, 0.1);
                    color: #38c172;
                }

                .notification-icon.error {
                    background-color: rgba(227, 52, 47, 0.1);
                    color: #e3342f;
                }

                .notification-icon.warning {
                    background-color: rgba(255, 190, 11, 0.1);
                    color: #ffbe0b;
                }

                .notification-close {
                    background: none;
                    border: none;
                    color: var(--text-muted, #6c757d);
                    cursor: pointer;
                    padding: 0;
                    width: 24px;
                    height: 24px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                    transition: all 0.2s ease;
                }

                .notification-close:hover {
                    color: var(--text-dark, #212529);
                    background: rgba(0, 0, 0, 0.05);
                }

                .notification-message {
                    font-size: 14px;
                    line-height: 1.5;
                    color: var(--text-dark, #212529);
                    font-weight: 500;
                }

                .notification-progress {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    height: 3px;
                    background: rgba(0, 0, 0, 0.05);
                    overflow: hidden;
                }

                .notification-progress-bar {
                    height: 100%;
                    background: var(--primary-color, #4361ee);
                    width: 0%;
                    transition: width 0.1s ease;
                }

                .notification-spinner-container {
                    width: 24px;
                    height: 24px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background-color: rgba(76, 201, 240, 0.15);
                    border-radius: 50%;
                    box-shadow: 0 0 8px rgba(76, 201, 240, 0.2);
                }

                .notification-spinner {
                    width: 16px;
                    height: 16px;
                    border: 3px solid rgba(76, 201, 240, 0.3);
                    border-top: 3px solid var(--secondary-color, #4cc9f0);
                    border-radius: 50%;
                    animation: spin 0.8s linear infinite;
                }

                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                @media (max-width: 480px) {
                    .export-notifications-container {
                        left: 10px;
                        right: 10px;
                        max-width: none;
                    }
                }
            `;
            document.head.appendChild(styles);
        }
    }

    show(options = {}) {
        const {
            type = 'info',
            title = 'Exportación',
            message = 'Procesando...',
            duration = 5000,
            showProgress = false,
            closable = true
        } = options;

        const notification = document.createElement('div');
        notification.className = `export-notification ${type}`;
        
        const iconMap = {
            success: '<i class="bi bi-check-circle-fill"></i>',
            error: '<i class="bi bi-x-circle-fill"></i>',
            warning: '<i class="bi bi-exclamation-triangle-fill"></i>',
            info: '<i class="bi bi-info-circle-fill"></i>',
            loading: '<div class="notification-spinner-container"><div class="notification-spinner"></div></div>',
            exporting: '<i class="bi bi-arrow-down-circle-fill"></i>'
        };

        notification.innerHTML = `
            <div class="notification-header">
                <div class="notification-title">
                    <span class="notification-icon ${type}">${iconMap[type] || iconMap.info}</span>
                    ${title}
                </div>
                ${closable ? '<button class="notification-close" type="button"><i class="bi bi-x"></i></button>' : ''}
            </div>
            <div class="notification-message">${message}</div>
            ${showProgress ? '<div class="notification-progress"><div class="notification-progress-bar"></div></div>' : ''}
        `;

        this.container.appendChild(notification);

        // Mostrar notificación con animación
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        // Configurar cierre automático
        let autoCloseTimer;
        if (duration > 0) {
            autoCloseTimer = setTimeout(() => {
                this.hide(notification);
            }, duration);
        }

        // Configurar botón de cierre
        const closeBtn = notification.querySelector('.notification-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                if (autoCloseTimer) clearTimeout(autoCloseTimer);
                this.hide(notification);
            });
        }

        return {
            element: notification,
            update: (newOptions) => this.updateNotification(notification, newOptions),
            hide: () => this.hide(notification),
            updateProgress: (percent) => this.updateProgress(notification, percent)
        };
    }

    updateNotification(notification, options) {
        const { title, message, type } = options;
        
        if (title) {
            const titleElement = notification.querySelector('.notification-title');
            if (titleElement) {
                const iconMap = {
                    success: '<i class="bi bi-check-circle-fill"></i>',
                    error: '<i class="bi bi-x-circle-fill"></i>',
                    warning: '<i class="bi bi-exclamation-triangle-fill"></i>',
                    info: '<i class="bi bi-info-circle-fill"></i>',
                    loading: '<div class="notification-spinner-container"><div class="notification-spinner"></div></div>'
                };
                titleElement.innerHTML = `
                    <span class="notification-icon ${type}">${iconMap[type] || iconMap.info}</span>
                    ${title}
                `;
            }
        }
        
        if (message) {
            const messageElement = notification.querySelector('.notification-message');
            if (messageElement) {
                messageElement.textContent = message;
            }
        }

        if (type) {
            notification.className = `export-notification ${type} show`;
        }
    }

    updateProgress(notification, percent) {
        const progressBar = notification.querySelector('.notification-progress-bar');
        if (progressBar) {
            progressBar.style.width = `${Math.min(100, Math.max(0, percent))}%`;
        }
    }

    hide(notification) {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 400);
    }

    // Métodos de conveniencia
    success(title, message, duration = 4000) {
        return this.show({
            type: 'success',
            title,
            message,
            duration
        });
    }

    error(title, message, duration = 6000) {
        return this.show({
            type: 'error',
            title,
            message,
            duration
        });
    }

    warning(title, message, duration = 5000) {
        return this.show({
            type: 'warning',
            title,
            message,
            duration
        });
    }

    info(title, message, duration = 4000) {
        return this.show({
            type: 'info',
            title,
            message,
            duration
        });
    }

    loading(title, message) {
        return this.show({
            type: 'loading',
            title,
            message,
            duration: 0,
            closable: false,
            showProgress: true
        });
    }
}

// Crear instancia global
window.ExportNotification = new ExportNotification();

// Función global para manejar exportaciones
window.handleExport = async function(url, filename, type, method = 'GET', formData = null, isElectron = false) {
    try {
        // Mostrar notificación de carga
        const notification = window.ExportNotification.show({
            type: 'loading',
            title: 'Exportando...',
            message: isElectron 
                ? 'Por favor, confirma dónde guardar el archivo cuando se te solicite.' 
                : 'Preparando la exportación...',
            duration: isElectron ? 15000 : 0,
            closable: true
        });

        // Configurar opciones de la petición
        const options = {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': type === 'pdf' ? 'application/pdf' : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            }
        };

        // Si hay FormData, agregarlo al cuerpo de la petición
        if (formData) {
            options.body = formData;
        }

        // Realizar la petición
        const response = await fetch(url, options);
        
        if (!response.ok) {
            throw new Error('Error en la exportación');
        }

        // Obtener el blob de la respuesta
        const blob = await response.blob();
        
        // Obtener el nombre de archivo del header Content-Disposition
        let serverFilename = '';
        const contentDisposition = response.headers.get('Content-Disposition');
        if (contentDisposition) {
            const filenameRegex = /filename[^;=\n]*=((['"]{1})(.*?)\2|([^;\n]*))/i;
            const matches = filenameRegex.exec(contentDisposition);
            
            if (matches !== null && matches.length > 1) {
                serverFilename = matches[3] || matches[4] || '';
                serverFilename = decodeURIComponent(serverFilename.replace(/['"]*/g, ''));
            }
        }
        
        // Crear URL del blob
        const blobUrl = window.URL.createObjectURL(blob);
        
        // Crear enlace temporal y hacer clic
        const link = document.createElement('a');
        link.href = blobUrl;
        
        // Usar el nombre del archivo del servidor si está disponible
        if (serverFilename) {
            link.download = serverFilename;
        } else {
            const extension = type === 'pdf' ? 'pdf' : 'xlsx';
            link.download = `${filename}.${extension}`;
        }
        
        document.body.appendChild(link);
        link.click();
        
        // Limpiar
        document.body.removeChild(link);
        window.URL.revokeObjectURL(blobUrl);

        if (isElectron) {
            // En Electron, esperar a que el usuario vuelva a la aplicación
            const focusHandler = function() {
                notification.update({
                    type: 'success',
                    title: '¡Exportación exitosa!',
                    message: `El archivo ${serverFilename || `${filename}.${type}`} se ha guardado correctamente.`
                });
                setTimeout(() => {
                    notification.hide();
                }, 4000);
                window.removeEventListener('focus', focusHandler);
            };
            
            window.addEventListener('focus', focusHandler);
            
            // Eliminar el listener después de un tiempo razonable
            setTimeout(() => {
                window.removeEventListener('focus', focusHandler);
            }, 60000);
        } else {
            // En navegador web estándar
            notification.update({
                type: 'success',
                title: '¡Exportación exitosa!',
                message: `El archivo ${serverFilename || `${filename}.${type}`} se ha descargado correctamente.`
            });
            setTimeout(() => {
                notification.hide();
            }, 4000);
        }

    } catch (error) {
        console.error('Error en la exportación:', error);
        
        window.ExportNotification.show({
            type: 'error',
            title: 'Error en la exportación',
            message: 'No se pudo completar la exportación. Por favor, intente nuevamente.',
            duration: 6000
        });
    }
};