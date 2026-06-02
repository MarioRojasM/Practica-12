<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '' // Laravel pide este campo exacto para confirmar
})
const errorMsg = ref('')

const procesarRegistro = async () => {
  try {
    errorMsg.value = ''
    await auth.register(form.value)
  } catch (error) {
    errorMsg.value = 'Error al registrar. Verifica que el correo no exista y las contraseñas coincidan.'
  }
}
</script>

<template>
  <div class="card">
    <h2>Crear Cuenta Nueva</h2>
    
    <div v-if="errorMsg" class="error-msg">
      {{ errorMsg }}
    </div>

    <form @submit.prevent="procesarRegistro">
      <div class="form-group">
        <label>Nombre Completo:</label>
        <input type="text" v-model="form.name" required placeholder="Ej: Juan Pérez">
      </div>

      <div class="form-group">
        <label>Correo Electrónico:</label>
        <input type="email" v-model="form.email" required placeholder="tu@email.com">
      </div>
      
      <div class="form-group">
        <label>Contraseña:</label>
        <input type="password" v-model="form.password" required minlength="8" placeholder="Mínimo 8 caracteres">
      </div>

      <div class="form-group">
        <label>Confirmar Contraseña:</label>
        <input type="password" v-model="form.password_confirmation" required placeholder="Repite tu contraseña">
      </div>
      
      <button type="submit" class="btn-submit">Registrarme</button>
    </form>
  </div>
</template>

<style scoped>
h2 { margin-top: 0; color: #1f2937; text-align: center; margin-bottom: 1.5rem;}
</style>