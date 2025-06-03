const { contextBridge, ipcRenderer } = require('electron');

// Exponer funciones seguras al proceso de renderizado
contextBridge.exposeInMainWorld('electronAPI', {
    minimizeWindow: () => ipcRenderer.send('minimize-window'),
    maximizeWindow: () => ipcRenderer.send('maximize-window'),
    closeWindow: () => ipcRenderer.send('close-window'),
    
    queryDatabase: (query, params) => ipcRenderer.invoke('query-database', query, params)
}); 