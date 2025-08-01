import './bootstrap';
import '../css/app.css';
import { app, analytics, db, auth, realtimeDb } from './firebase';

// Hacer Firebase disponible globalmente
window.Firebase = {
    app,
    analytics,
    db,
    auth,
    realtimeDb
};

console.log('Firebase inicializado correctamente:', app);
