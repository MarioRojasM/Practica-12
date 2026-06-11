// src/plugins/echo.js
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

const echo = new Echo({
    broadcaster:    'reverb',
    key:            import.meta.env.VITE_REVERB_APP_KEY,
    wsHost:         import.meta.env.VITE_REVERB_HOST,
    wsPort:         import.meta.env.VITE_REVERB_PORT,
    wssPort:        import.meta.env.VITE_REVERB_PORT,
    forceTLS:       false,
    enabledTransports: ['ws'],
    authEndpoint:   '/broadcasting/auth', // esta es la ruta de Laravel que autoriza la conexión
    auth: {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
        }
    }
})

export default echo