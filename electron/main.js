const { app, BrowserWindow, ipcMain } = require('electron');
const path = require('path');
const sqlite3 = require('sqlite3');
const { open } = require('sqlite');

let mainWindow;
let splashWindow;
let db;

async function initializeDatabase() {
    try {
        db = await open({
            filename: path.join(__dirname, '../database/database.sqlite'),
            driver: sqlite3.Database
        });
        console.log('Conexión a la base de datos establecida');
    } catch (error) {
        console.error('Error al conectar con la base de datos:', error);
    }
}

function createWindow() {
    // Create the splash screen window
    splashWindow = new BrowserWindow({
        width: 500,
        height: 300,
        frame: false,
        transparent: true,
        alwaysOnTop: true,
        webPreferences: {
            nodeIntegration: true,
            contextIsolation: false
        }
    });

    // Load the splash screen HTML file
    splashWindow.loadFile(path.join(__dirname, 'app/splash/index.html'));

    // Create the main window
    mainWindow = new BrowserWindow({
        width: 1200,
        height: 800,
        show: false,
        webPreferences: {
            nodeIntegration: true,
            contextIsolation: true,
            preload: path.join(__dirname, 'preload.js')
        },
        icon: path.join(__dirname, '../resources/views/assets/Logo_Final_v2.png')
    });

    // Cargar la aplicación Laravel
    mainWindow.loadURL('http://localhost:8000');

    mainWindow.removeMenu();

    // Manejar eventos de la ventana
    ipcMain.on('minimize-window', () => {
        mainWindow.minimize();
    });

    ipcMain.on('maximize-window', () => {
        if (mainWindow.isMaximized()) {
            mainWindow.unmaximize();
        } else {
            mainWindow.maximize();
        }
    });

    ipcMain.on('close-window', () => {
        mainWindow.close();
    });

    // Once the main window content is loaded, show it and close the splash screen
    mainWindow.once('ready-to-show', () => {
        splashWindow.destroy();
        mainWindow.maximize();
        mainWindow.show();
    });
}

app.whenReady().then(async () => {
    if (process.platform === 'win32') {
      app.setAppUserModelId(app.getName());
    }
    await initializeDatabase();
    createWindow();

    app.on('activate', () => {
        if (BrowserWindow.getAllWindows().length === 0) {
            createWindow();
        }
    });
});

app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') {
        app.quit();
    }
});

// Manejar consultas a la base de datos
ipcMain.handle('query-database', async (event, query, params = []) => {
    try {
        const result = await db.all(query, params);
        return { success: true, data: result };
    } catch (error) {
        console.error('Error en la consulta:', error);
        return { success: false, error: error.message };
    }
});

 