<script setup>
import { useAuthStore } from '../stores/auth'

// Traemos a Pinia para saber si el usuario está logueado
const auth = useAuthStore()

// Acción del botón de Logout
const hacerLogout = async () => {
  await auth.logout()
}
</script>

<template>
  <nav class="navbar">
    <div class="nav-brand">Práctica 02 - Auth</div>
    
    <div class="nav-links">
      <!-- Si el usuario TIENE token (sesión iniciada) -->
      <template v-if="auth.isAuthenticated">
        <span class="user-name">Hola, {{ auth.user?.name || 'Usuario' }}</span>
        <button @click="hacerLogout" class="btn-logout">Cerrar Sesión</button>
      </template>
      
      <!-- Si el usuario NO tiene token (invitado) -->
      <template v-else>
        <router-link to="/login" class="nav-link">Login</router-link>
        <router-link to="/register" class="nav-link">Registro</router-link>
      </template>
    </div>
  </nav>
</template>

<style scoped>
.navbar { display: flex; justify-content: space-between; align-items: center; background-color: #1f2937; color: white; padding: 1rem 2rem; }
.nav-brand { font-weight: bold; font-size: 1.2rem; }
.nav-links { display: flex; gap: 1rem; align-items: center; }
.nav-link { color: #9ca3af; text-decoration: none; transition: color 0.2s; font-weight: bold;}
.nav-link:hover, .router-link-active { color: white; }
.user-name { margin-right: 1rem; font-weight: bold;}
.btn-logout { background-color: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; font-weight: bold;}
.btn-logout:hover { background-color: #dc2626; }
</style>