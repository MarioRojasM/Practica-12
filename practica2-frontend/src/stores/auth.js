
import { defineStore } from 'pinia'
import api from '../plugins/axios'
import router from '../router'

export const useAuthStore = defineStore('auth', {
  // state: Son nuestras variables globales
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
  }),
  
  // getters: Funciones que nos dicen cómo está el state
  getters: {
    isAuthenticated: (state) => !!state.token,
  },
  
  // actions: Funciones que modifican el state o hacen peticiones
  actions: {
    async login(credentials) {
      const respuesta = await api.post('/login', credentials)
      
      // Guardamos en Pinia y en LocalStorage
      this.token = respuesta.data.token
      this.user = respuesta.data.user
      localStorage.setItem('token', this.token)
      
      // Redirigimos al área protegida
      router.push('/dashboard')
    },
    
    async register(data) {
      const respuesta = await api.post('/register', data)
      
      this.token = respuesta.data.token
      this.user = respuesta.data.user
      localStorage.setItem('token', this.token)
      
      router.push('/dashboard')
    },
    
    async logout() {
      try {
        // Le avisamos al backend que destruya el token
        await api.post('/logout')
      } catch (error) {
        console.error("Error al cerrar sesión", error)
      } finally {
        // Limpiamos los datos locales sin importar si falló el backend
        this.token = null
        this.user = null
        localStorage.removeItem('token')
        router.push('/login')
      }
    },
    
    // Función extra útil para cargar los datos del usuario al refrescar la página
    async fetchUser() {
      if (this.token) {
        try {
          const respuesta = await api.get('/me')
          this.user = respuesta.data
        } catch (error) {
          this.token = null
          localStorage.removeItem('token')
        }
      }
    }
  }
})