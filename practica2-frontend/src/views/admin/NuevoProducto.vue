<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const auth = useAuthStore() // Para mandar el token de Sanctum
const categorias = ref([])

const form = reactive({ 
  nombre: '', 
  precio: '', 
  descripcion: '',
  categoria_id: '' 
})

onMounted(async () => {
  try {
    const { data } = await axios.get('http://localhost:8000/api/categorias')
    categorias.value = data.data
  } catch (error) {
    console.error("Error al cargar categorías:", error)
  }
})

const guardar = async () => {
  try {
    // Mandamos el token en la petición para que Laravel nos deje guardar
    await axios.post('http://localhost:8000/api/productos', form, {
      headers: {
        Authorization: `Bearer ${auth.token}`
      }
    })
    alert('¡Producto guardado exitosamente!')
    router.push('/admin/productos')
  } catch (error) {
    console.error("Error al guardar:", error)
    alert("Hubo un error al intentar guardar.")
  }
}
</script>

<template>
  <div>
    <h2 class="titulo-admin">Agregar Nuevo Producto</h2>
    
    <div class="form-card">
      <form @submit.prevent="guardar">
        <div class="form-group">
          <label>Nombre del Producto</label>
          <input type="text" v-model="form.nombre" required placeholder="Ej. Lámpara de Noche" />
        </div>
        
        <div class="form-group">
          <label>Precio ($)</label>
          <input type="number" v-model="form.precio" required placeholder="0.00" step="0.01" />
        </div>

        <div class="form-group">
          <label>Descripción</label>
          <textarea v-model="form.descripcion" rows="3" placeholder="Detalles del producto..."></textarea>
        </div>

        <div class="form-group">
          <label>Categoría</label>
          <select v-model="form.categoria_id" class="input-select" required>
            <option value="" disabled>Selecciona una categoría</option>
            <option v-for="cat in categorias" :key="cat.id" :value="cat.id">
              {{ cat.nombre }}
            </option>
          </select>
        </div>

        <div class="form-acciones">
          <button type="button" class="btn-cancelar" @click="$router.push('/admin/productos')">Cancelar</button>
          <button type="submit" class="btn-guardar">Guardar Producto</button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.titulo-admin { color: #1f2937; border-bottom: 2px solid #fbcfe8; padding-bottom: 0.5rem; margin-top: 0; margin-bottom: 2rem;}
.form-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); max-width: 500px; border-top: 4px solid #e83e8c;}
.form-group { margin-bottom: 1.5rem; }
label { display: block; font-weight: bold; margin-bottom: 0.5rem; color: #4b5563; }
input[type="text"], input[type="number"], textarea, .input-select { width: 100%; padding: 0.8rem; border: 2px solid #e2e8f0; border-radius: 6px; box-sizing: border-box; outline: none; font-family: inherit; transition: border-color 0.3s;}
input:focus, textarea:focus, .input-select:focus { border-color: #e83e8c; }
.form-acciones { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;}
.btn-cancelar { background: white; border: 1px solid #d1d5db; padding: 0.8rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: bold;}
.btn-guardar { background-color: #e83e8c; color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 6px; font-weight: bold; cursor: pointer;}
</style>