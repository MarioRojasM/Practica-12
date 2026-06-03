import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'

// 1. Importamos la directiva que acabas de crear
import { vCan } from './directives/can' 

const app = createApp(App)

// Le decimos a Vue que use Pinia y las Rutas
app.use(createPinia())
app.use(router)

// 2. Registramos la directiva globalmente ANTES del mount
app.directive('can', vCan)

app.mount('#app')