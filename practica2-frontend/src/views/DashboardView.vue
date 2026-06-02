<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const auth = useAuthStore()

const cerrarSesion = async () => {
  await auth.logout()
  router.push('/login')
}
</script>

<template>
  <div class="dashboard-contenedor">
    <div class="tarjeta-bienvenida">
      <h2>Bienvenido al Dashboard 🛡️</h2>
      <p>Has escapado del cuarto de seguridad. ¿Qué quieres hacer hoy?</p>
      
      <div class="menu-botones">
        <button @click="router.push('/admin/productos')" class="btn-opcion">📋 Ver Productos</button>
        <button @click="router.push('/admin/nuevo')" class="btn-opcion">➕ Nuevo Producto</button>
        <button @click="router.push('/catalogo')" class="btn-opcion">🛍️ Ir al Catálogo</button>
      </div>

      <button @click="cerrarSesion" class="btn-salir">Cerrar Sesión</button>
    </div>
  </div>
</template>

<style scoped>
.dashboard-contenedor { display: flex; justify-content: center; align-items: center; min-height: 80vh; background-color: #f8fafc; }
.tarjeta-bienvenida { background: white; padding: 3rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; border-top: 5px solid #e83e8c; max-width: 500px; width: 100%; }
h2 { color: #1f2937; margin-bottom: 0.5rem; }
p { color: #64748b; margin-bottom: 2rem; }
.menu-botones { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2.5rem; }
.btn-opcion { background-color: white; border: 2px solid #e83e8c; color: #e83e8c; padding: 1rem; border-radius: 8px; font-weight: bold; cursor: pointer; transition: all 0.3s ease; font-size: 1rem; }
.btn-opcion:hover { background-color: #e83e8c; color: white; transform: translateY(-2px); }
.btn-salir { background-color: transparent; border: none; color: #ef4444; text-decoration: underline; cursor: pointer; font-weight: bold; }
.btn-salir:hover { color: #dc2626; }
</style>