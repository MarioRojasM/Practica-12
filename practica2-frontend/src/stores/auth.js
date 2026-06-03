import { defineStore } from 'pinia'
import api from '../plugins/axios'
import router from '../router'

export const useAuthStore = defineStore('auth', {
  // state: Son nuestras variables globales
 state: () => ({
    user: null,
    token: localStorage.getItem('token') || null, // <-- ¡Ojo con esta coma al final!
    permisos: { crear: false, editar: false, eliminar: false }
  }),
  
  // getters: Funciones que nos dicen cómo está el state
  getters: {
    isAuthenticated: (state) => !!state.token,
  },
  
  // actions: Funciones que modifican el state o hacen peticiones
  actions: {
    async login(credentials) {
      try {
        const respuesta = await api.post('/login', credentials)
        
        this.token = respuesta.data.access_token || respuesta.data.token
        this.user = respuesta.data.user
        localStorage.setItem('token', this.token)
        
        // Pedimos los permisos frescos antes de entrar
        await this.fetchUser()
        
        // Solo redirigimos si el fetchUser fue exitoso (es decir, si el token sigue vivo)
        if (this.token) {
          router.push('/dashboard')
        }
      } catch (error) {
        console.error("Error al iniciar sesión:", error)
        alert("Credenciales incorrectas. Intenta de nuevo.")
      }
    },
    
    async register(data) {
      try {
        const respuesta = await api.post('/register', data)
        
        this.token = respuesta.data.access_token || respuesta.data.token
        this.user = respuesta.data.user
        localStorage.setItem('token', this.token)
        
        await this.fetchUser()
        
        if (this.token) {
          router.push('/dashboard')
        }
      } catch (error) {
        console.error("Error al registrar:", error)
        alert("Hubo un error al crear la cuenta.")
      }
    },
    
    async logout() {
      try {
        // Le mandamos explícitamente el token al backend por si las dudas
        await api.post('/logout', {}, {
          headers: { Authorization: `Bearer ${this.token}` }
        })
      } catch (error) {
        console.error("Error al cerrar sesión", error)
      } finally {
        this.token = null
        this.user = null
        this.permisos = { crear: false, editar: false, eliminar: false }
        localStorage.removeItem('token')
        router.push('/login')
      }
    },
    
    async fetchUser() {
      if (this.token) {
        try {
          // FORZAMOS EL TOKEN EN LA CABECERA (Aquí estaba el error silencioso)
          const respuesta = await api.get('/me', {
            headers: {
              Authorization: `Bearer ${this.token}`
            }
          })
          
          this.user = respuesta.data
          this.permisos = respuesta.data.permisos || { crear: false, editar: false, eliminar: false }
          
        } catch (error) {
          console.error("Fallo al traer el usuario. El token expiró o es inválido.", error)
          this.token = null
          this.user = null
          this.permisos = { crear: false, editar: false, eliminar: false }
          localStorage.removeItem('token')
        }
      }
    }
  }
})