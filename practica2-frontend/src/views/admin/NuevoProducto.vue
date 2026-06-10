<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import { useForm, useField } from 'vee-validate'
import { productoSchema } from '@/schemas/productoSchema'

import InputField from '@/components/InputField.vue'

const router = useRouter()
const auth = useAuthStore()
const categorias = ref([])

const { handleSubmit, errors, resetForm } = useForm({
  validationSchema: productoSchema
})

const { value: nombre } = useField('nombre')
const { value: precio } = useField('precio')
const { value: stock } = useField('stock')
const { value: descripcion } = useField('descripcion')
const { value: categoria_id } = useField('categoria_id')

const erroresServidor = ref({})
onMounted(async () => {
  try {
    // 1. AQUI AGREGAMOS EL GAFETE (TOKEN) PARA QUE LARAVEL NOS DEJE VER LAS CATEGORÍAS
    const { data } = await axios.get('http://localhost:8000/api/categorias', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })
    categorias.value = data.data
  } catch (error) {
    console.error("Error al cargar categorías:", error)
  }
})

const guardar = handleSubmit(async (values) => {
  try {
    await axios.post('http://localhost:8000/api/productos', values, {
      headers: {
        // 2. ASEGURAMOS EL TOKEN DESDE LOCALSTORAGE PARA EVITAR FALLOS AL RECARGAR
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })
    alert('¡Producto guardado exitosamente!')
    resetForm()
    erroresServidor.value = {}
    router.push('/admin/productos')
  } catch (error) {
    if (error.response?.status === 422) {
      erroresServidor.value = error.response.data.errors
    } else {
      console.error("Error al guardar:", error)
      alert("Hubo un error al intentar guardar.")
    }
  }
})
</script>

<template>
  <div>
    <h2 class="titulo-admin">Agregar Nuevo Producto</h2>
    
    <div class="form-card">
      <form @submit.prevent="guardar">
        
        <InputField
          label="Nombre del Producto"
          name="nombre"
          v-model="nombre"
          placeholder="Ej. Lámpara de Noche"
          :error="errors.nombre || erroresServidor.nombre?.[0]"
        />
        
        <InputField
          label="Precio ($)"
          name="precio"
          type="number"
          v-model="precio"
          placeholder="0.00"
          step="0.01"
          :error="errors.precio || erroresServidor.precio?.[0]"
        />

        <InputField
          label="Stock"
          name="stock"
          type="number"
          v-model="stock"
          placeholder="Cantidad disponible"
          :error="errors.stock || erroresServidor.stock?.[0]"
        />

        <div class="form-group">
          <label>Descripción</label>
          <textarea v-model="descripcion" :class="{ 'input-error': errors.descripcion || erroresServidor.descripcion }" rows="3" placeholder="Detalles del producto..."></textarea>
          <span class="error-msg" v-if="errors.descripcion">{{ errors.descripcion }}</span>
          <span class="error-msg" v-if="erroresServidor.descripcion">{{ erroresServidor.descripcion[0] }}</span>
        </div>

        <div class="form-group">
          <label>Categoría</label>
          <select v-model="categoria_id" class="input-select" :class="{ 'input-error': errors.categoria_id || erroresServidor.categoria_id }">
            <option value="" disabled>Selecciona una categoría</option>
            <option v-for="cat in categorias" :key="cat.id" :value="cat.id">
              {{ cat.nombre }}
            </option>
          </select>
          <span class="error-msg" v-if="errors.categoria_id">{{ errors.categoria_id }}</span>
          <span class="error-msg" v-if="erroresServidor.categoria_id">{{ erroresServidor.categoria_id[0] }}</span>
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

/* NUEVOS ESTILOS PARA ERRORES */
.input-error { border-color: #ef4444 !important; background-color: #fef2f2; }
.error-msg { color: #ef4444; font-size: 0.85rem; margin-top: 0.3rem; display: block; font-weight: bold; }

.form-acciones { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;}
.btn-cancelar { background: white; border: 1px solid #d1d5db; padding: 0.8rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: bold;}
.btn-guardar { background-color: #e83e8c; color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 6px; font-weight: bold; cursor: pointer;}
</style>