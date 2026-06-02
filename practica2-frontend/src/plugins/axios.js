
import axios from 'axios'
import router from '../router' // Importamos el router para poder redirigir

// Creamos una instancia configurada de Axios
const api = axios.create({
  baseURL: 'http://localhost:8000/api', // Tu backend de Laravel
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
})

// Interceptor de Petición (Request): Se ejecuta ANTES de enviar algo al servidor
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token')
  // Si tenemos un token guardado, lo pegamos en los Headers
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Interceptor de Respuesta (Response): Se ejecuta al recibir respuesta del servidor
api.interceptors.response.use(
  res => res,
  err => {
    // Si el backend nos responde 401 (No autorizado), limpiamos todo y mandamos al login
    if (err.response?.status === 401) {
      localStorage.removeItem('token')
      router.push('/login')
    }
    return Promise.reject(err)
  }
)

export default api