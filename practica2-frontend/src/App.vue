<script setup>
import { useAuthStore } from './stores/auth'

// Traemos a Pinia para saber si el usuario está logueado
const auth = useAuthStore()

// Acción del botón de Logout
const hacerLogout = async () => {
  await auth.logout()
}
</script>

<template>
  <div id="app">
    <nav class="navbar">
      <div class="nav-brand">Práctica 11 - Tienda</div>
      
      <div class="nav-links">
        <template v-if="auth.isAuthenticated">
          <router-link to="/carrito" class="nav-link">🛒 Mi Carrito</router-link>
          
          <span class="user-name">Hola, {{ auth.user?.name || 'Usuario' }}</span>
          <button @click="hacerLogout" class="btn-logout">Cerrar Sesión</button>
        </template>
        
        <template v-else>
          <router-link to="/login" class="nav-link">Login</router-link>
          <router-link to="/register" class="nav-link">Registro</router-link>
        </template>
      </div>
    </nav>

    <main class="container">
      <router-view></router-view>
    </main>
  </div>
</template>

<style>
/* ESTILOS GLOBALES - Afectan a toda la aplicación */
body { 
  font-family: Arial, sans-serif; 
  margin: 0; 
  background-color: #f3f4f6; 
}

.container { 
  max-width: 800px; 
  margin: 2rem auto; 
  padding: 0 1rem; 
}

/* Estilos de la barra de navegación */
.navbar { display: flex; justify-content: space-between; align-items: center; background-color: #1f2937; color: white; padding: 1rem 2rem; }
.nav-brand { font-weight: bold; font-size: 1.2rem; }
.nav-links { display: flex; gap: 1rem; align-items: center; }
.nav-link { color: #9ca3af; text-decoration: none; transition: color 0.2s; font-weight: bold;}
.nav-link:hover, .router-link-active { color: white; }
.user-name { margin-right: 1rem; font-weight: bold;}
.btn-logout { background-color: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; font-weight: bold;}
.btn-logout:hover { background-color: #dc2626; }

/* Clases reutilizables para los formularios */
.card { 
  background: white; 
  padding: 2rem; 
  border-radius: 8px; 
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); 
}

.form-group { margin-bottom: 1rem; }
.form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; color: #374151;}
.form-group input { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box; font-size: 1rem;}

.btn-submit { width: 100%; background-color: #3b82f6; color: white; border: none; padding: 0.75rem; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 1rem; font-size: 1rem;}
.btn-submit:hover { background-color: #2563eb; }

.error-msg { color: #dc2626; background-color: #fee2e2; padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem; text-align: center; font-weight: bold;}
</style>