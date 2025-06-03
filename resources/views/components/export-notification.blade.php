{{-- resources/views/components/export-notification.blade.php --}}
@if(session('export_notification'))
    @php
        $notification = session('export_notification');
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.ExportNotification) {
                const notification = @json($notification);
                
                // Mostrar notificación
                window.ExportNotification.show({
                    type: notification.type || 'info',
                    title: notification.title || 'Exportación',
                    message: notification.message || 'Proceso completado',
                    duration: notification.type === 'error' ? 8000 : 5000,
                    closable: true
                });
            }
        });
    </script>
@endif

{{-- Script para manejar exportaciones con notificaciones --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar clicks en enlaces de exportación para mostrar notificación
    document.querySelectorAll('a[href*="export"], a[href*="pdf"], a[href*="excel"]').forEach(function(element) {
        // Solo manejamos manualmente si tiene atributos data-export
        if (element.hasAttribute('data-export')) {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                
                const url = this.href || this.getAttribute('data-href');
                const type = this.getAttribute('data-export');
                const filename = this.getAttribute('data-filename');
                
                if (url) {
                    window.handleExport(url, filename, type);
                }
            });
        } else {
            // Para los enlaces sin atributos, solo mostrar notificación sin interferir con la descarga
            element.addEventListener('click', function() {
                // Mostrar notificación de inicio de descarga
                if (window.ExportNotification) {
                    // Detectar si es posible que estemos en Electron (donde se requiere confirmación)
                    const isElectron = window.navigator.userAgent.toLowerCase().indexOf('electron') > -1;
                    
                    // Guardar la URL para detectar cuando se complete la descarga
                    const exportUrl = this.href;
                    
                    const notification = window.ExportNotification.show({
                        type: 'loading',
                        title: 'Exportando...',
                        message: isElectron 
                            ? 'Por favor, confirma dónde guardar el archivo cuando se te solicite.' 
                            : 'Preparando la exportación. El archivo se descargará automáticamente.',
                        duration: isElectron ? 15000 : 3000,
                        closable: true
                    });
                    
                    // Detectar cuando se completa la descarga
                    if (isElectron) {
                        // En Electron, usar un evento de focus para detectar cuando el usuario vuelve a la aplicación
                        // después de guardar el archivo
                        const focusHandler = function() {
                            // Primero ocultar la notificación de exportación
                            notification.hide();
                            
                            // Mostrar notificación de éxito cuando el usuario vuelve a la aplicación
                            setTimeout(() => {
                                window.ExportNotification.show({
                                    type: 'success',
                                    title: '¡Exportación exitosa!',
                                    message: 'El archivo se ha guardado correctamente.',
                                    duration: 4000
                                });
                                // Eliminar el event listener después de usarlo
                                window.removeEventListener('focus', focusHandler);
                            }, 500);
                        };
                        
                        // Agregar el event listener para detectar cuando el usuario vuelve a la aplicación
                        window.addEventListener('focus', focusHandler);
                        
                        // También eliminar el listener después de un tiempo razonable si no se activa
                        setTimeout(() => {
                            window.removeEventListener('focus', focusHandler);
                        }, 60000); // 1 minuto máximo de espera
                    } else {
                        // En navegadores web estándar, mostrar notificación de éxito después de un tiempo
                        setTimeout(() => {
                            // Primero ocultar la notificación de exportación
                            notification.hide();
                            
                            // Luego mostrar la notificación de éxito
                            setTimeout(() => {
                                window.ExportNotification.show({
                                    type: 'success',
                                    title: '¡Exportación exitosa!',
                                    message: 'El archivo se ha descargado correctamente.',
                                    duration: 4000
                                });
                            }, 300);
                        }, 3000);
                    }
                }
            });
        }
    });

    // Manejar formularios de exportación
    document.querySelectorAll('form[data-export-form]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const type = form.getAttribute('data-export-type') || 'excel';
            const filename = form.getAttribute('data-filename') || 'exportacion';
            const actionUrl = form.action;
            
            // Construir URL con parámetros del formulario
            const formData = new FormData(form);
            const params = new URLSearchParams();
            
            for (let [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }
            
            const fullUrl = actionUrl + '?' + params.toString();
            window.handleExport(fullUrl, filename, type);
        });
    });

    // Exportar a Excel
    exportExcelBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (materiaSelect.value && docenteSelect.value && mesSelect.value && añoSelect.value) {
            exportExcelMateria.value = materiaSelect.value;
            exportExcelDocente.value = docenteSelect.value;
            exportExcelMes.value = mesSelect.value;
            exportExcelAño.value = añoSelect.value;
            
            // Usar el sistema de notificaciones con POST
            const formData = new FormData(exportExcelForm);
            const url = exportExcelForm.action;
            
            window.handleExport(
                url,
                'asistencia_excel',
                'xlsx',
                'POST',
                formData
            );
        }
    });
});
</script>

{{-- Estilos adicionales si no se incluye el archivo JS completo --}}
<style>
.export-btn-loading {
    pointer-events: none;
    opacity: 0;
    position: relative;
    transition: all var(--transition-fast, 0.15s ease);
    background-color: #e8f7ff !important;
    border-color: var(--secondary-color, #4cc9f0) !important;
    color: var(--secondary-color, #4cc9f0) !important;
}

.export-btn-loading .btn-label {
    visibility: hidden;
    opacity: 0;
}

.export-btn-loading::after {
    content: '';
    position: absolute;
    width: 18px;
    height: 18px;
    margin: auto;
    border: 2.5px solid rgba(255, 255, 255, 0.2);
    border-top-color: currentColor;
    border-radius: 50%;
    animation: exportBtnSpin 0.8s linear infinite;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

/* Estilos específicos para botones con diferentes colores de fondo */
.export-btn-loading.btn-light::after,
.export-btn-loading.btn-white::after,
.export-btn-loading.btn-outline-primary::after,
.export-btn-loading.btn-outline-secondary::after,
.export-btn-loading.btn-outline-success::after,
.export-btn-loading.btn-outline-danger::after,
.export-btn-loading.btn-outline-warning::after,
.export-btn-loading.btn-outline-info::after {
    border: 2px solid rgba(0, 0, 0, 0.1);
    border-top-color: var(--text-dark, #212529);
}

@keyframes exportBtnSpin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Estilos para spinner dentro de dropdown items */
.dropdown-item.export-btn-loading::after {
    width: 14px;
    height: 14px;
    right: 16px;
    left: auto;
    transform: translateY(-50%);
}

.dropdown-item.export-btn-loading {
    padding-right: 40px;
}
</style>