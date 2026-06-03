<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
// 1. Importamos tu tienda de autenticación
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const auth = useAuthStore() // Inicializamos Pinia
const productos = ref([])

onMounted(async () => {
  try {
    const respuesta = await axios.get('http://localhost:8000/api/productos')
    productos.value = respuesta.data.data || respuesta.data
  } catch (error) {
    console.error("Error al cargar los productos:", error)
  }
})

// MAGIA EN VIVO: Función para borrar
const eliminarProducto = async (id) => {
  if (confirm("¿Estás seguro de que quieres eliminar este producto?")) {
    try {
      // 2. Le mandamos el token a Laravel para demostrar que tenemos permiso
      await axios.delete(`http://localhost:8000/api/productos/${id}`, {
        headers: {
          Authorization: `Bearer ${auth.token}`
        }
      })
      
      // MAGIA: Filtramos la lista para quitar el producto borrado
      productos.value = productos.value.filter(producto => producto.id !== id)
      
    } catch (error) {
      console.error("Error al eliminar:", error)
      alert("Hubo un error de permisos o conexión al intentar borrar el producto.")
    }
  }
}
</script>

<template>
  <div class="admin-productos">
    <div class="header-admin">
      <h2 class="titulo-admin">
        Administrar Productos 
        <span v-if="auth.user" class="badge-rol">{{ auth.user.rol }}</span>
      </h2>
      
      <button v-can="'crear'" @click="router.push('/admin/nuevo')" class="btn-nuevo">
        + Nuevo Producto
      </button>
    </div>

    <div class="tabla-contenedor">
      <table class="tabla-productos">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="producto in productos" :key="producto.id">
            <td>{{ producto.id }}</td>
            <td class="font-bold">{{ producto.nombre }}</td>
            <td>
              <span class="badge-categoria">
                {{ producto.categoria ? producto.categoria.nombre : 'Sin categoría' }}
              </span>
            </td>
            <td>${{ producto.precio }}</td>
            <td class="acciones">
              
              <button v-can="'editar'" class="btn-editar">Editar</button>
              
              <button v-can="'eliminar'" @click="eliminarProducto(producto.id)" class="btn-eliminar">Borrar</button>
              
            </td>
          </tr>
          
          <tr v-if="productos.length === 0">
            <td colspan="5" class="sin-datos">No hay productos registrados aún. ¡Agrega el primero!</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.admin-productos { padding: 1rem; }
.header-admin { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 2px solid #fbcfe8; padding-bottom: 1rem; }
.titulo-admin { color: #1f2937; margin: 0; display: flex; align-items: center; gap: 1rem; }

/* Estilo para la etiqueta del rol */
.badge-rol { background-color: #fbcfe8; color: #be185d; font-size: 0.8rem; padding: 0.3rem 0.8rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px; }

.btn-nuevo { background-color: #e83e8c; color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 6px; font-weight: bold; cursor: pointer; transition: background 0.3s; }
.btn-nuevo:hover { background-color: #d2337d; }

.tabla-contenedor { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow-x: auto; border-top: 4px solid #e83e8c; }
.tabla-productos { width: 100%; border-collapse: collapse; text-align: left; }
.tabla-productos th, .tabla-productos td { padding: 1rem; border-bottom: 1px solid #f3f4f6; color: #4b5563; }
.tabla-productos th { background-color: #fdf2f8; color: #e83e8c; font-weight: bold; }
.tabla-productos tbody tr:hover { background-color: #fff1f2; transition: background 0.2s; }

.font-bold { font-weight: bold; color: #1f2937; }
.badge-categoria { background-color: #f1f5f9; padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.85rem; color: #64748b; border: 1px solid #e2e8f0; }

.acciones { display: flex; gap: 0.5rem; }
.btn-editar { background-color: #f59e0b; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 4px; cursor: pointer; font-size: 0.9rem; transition: background 0.2s;}
.btn-editar:hover { background-color: #d97706; }
.btn-eliminar { background-color: #ef4444; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 4px; cursor: pointer; font-size: 0.9rem; transition: background 0.2s;}
.btn-eliminar:hover { background-color: #dc2626; }
.sin-datos { text-align: center; color: #6b7280; font-style: italic; padding: 2rem !important; }
</style>