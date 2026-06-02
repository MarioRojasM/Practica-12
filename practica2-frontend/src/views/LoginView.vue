<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const email = ref('')
const password = ref('')
const errorMsg = ref('')

const procesarLogin = async () => {
  try {
    errorMsg.value = ''
    // Ejecutamos la acción login de Pinia
    await auth.login({ email: email.value, password: password.value })
  } catch (error) {
    // Si Laravel nos responde con 401 (Credenciales incorrectas), lo mostramos
    errorMsg.value = error.response?.data?.message || 'Error al iniciar sesión. Revisa tus credenciales.'
  }
}
</script>

<template>
  <div class="card">
    <h2>Iniciar Sesión</h2>
    
    <div v-if="errorMsg" class="error-msg">
      {{ errorMsg }}
    </div>

    <form @submit.prevent="procesarLogin">
      <div class="form-group">
        <label>Correo Electrónico:</label>
        <input type="email" v-model="email" required placeholder="tu@email.com">
      </div>
      
      <div class="form-group">
        <label>Contraseña:</label>
        <input type="password" v-model="password" required placeholder="********">
      </div>
      
      <button type="submit" class="btn-submit">Entrar</button>
    </form>
  </div>
</template>

<style scoped>
h2 { margin-top: 0; color: #1f2937; text-align: center; margin-bottom: 1.5rem;}
</style>