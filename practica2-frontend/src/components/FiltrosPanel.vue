<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

// Recibimos el objeto "filtros" desde tu CatalogoView
const props = defineProps({
  filtros: {
    type: Object,
    required: true
  }
})

const categorias = ref([])
const busquedaLocal = ref(props.filtros.busqueda || '')

// Cargamos las categorías desde la API 
onMounted(async () => {
  try {
    const { data } = await axios.get('http://localhost:8000/api/categorias')
    categorias.value = data.data || data
  } catch (error) {
    console.error("Error al cargar categorías", error)
  }
})

// Lógica de Debounce (espera 300ms antes de buscar) 
let timeoutId
watch(busquedaLocal, (nuevoValor) => {
  clearTimeout(timeoutId)
  timeoutId = setTimeout(() => {
    props.filtros.busqueda = nuevoValor
    props.filtros.pagina = 1 // Si buscas algo nuevo, te regresa a la página 1
  }, 300)
})

// Función para reiniciar todos los valores 
const limpiarFiltros = () => {
  busquedaLocal.value = ''
  props.filtros.busqueda = ''
  props.filtros.categoria_id = ''
  props.filtros.precio_min = ''
  props.filtros.precio_max = ''
  props.filtros.pagina = 1
  props.filtros.orden = ''
}
</script>

<template>
  <div class="panel-filtros">
    <h3>Filtros de Búsqueda</h3>

    <div class="grupo-filtro">
      <label>Buscar producto</label>
      <input type="text" v-model="busquedaLocal" placeholder="Ej. Lámpara..." class="input-base" />
    </div>

    <div class="grupo-filtro">
      <label>Categoría</label>
      <select v-model="props.filtros.categoria_id" @change="props.filtros.pagina = 1" class="input-base">
        <option value="">Todas las categorías</option>
        <option v-for="cat in categorias" :key="cat.id" :value="cat.id">
          {{ cat.nombre }}
        </option>
      </select>
    </div>

    <div class="grupo-filtro precios-grid">
      <div>
        <label>Mínimo ($)</label>
        <input type="number" v-model="props.filtros.precio_min" @change="props.filtros.pagina = 1" placeholder="0" class="input-base" />
      </div>
      <div>
        <label>Máximo ($)</label>
        <input type="number" v-model="props.filtros.precio_max" @change="props.filtros.pagina = 1" placeholder="1000" class="input-base" />
      </div>
    </div>

    <div class="grupo-filtro">
      <label>Ordenar por</label>
      <select v-model="props.filtros.orden" @change="props.filtros.pagina = 1" class="input-base">
        <option value="">Más relevantes</option>
        <option value="nombre_asc">Nombre: A-Z</option>
        <option value="precio_asc">Precio: Menor a Mayor</option>
        <option value="precio_desc">Precio: Mayor a Menor</option>
      </select>
    </div>

    <button @click="limpiarFiltros" class="btn-limpiar">
      🧹 Limpiar Filtros
    </button>
  </div>
</template>

<style scoped>
.panel-filtros { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-top: 4px solid #e83e8c; height: fit-content; }
h3 { color: #1f2937; margin-top: 0; margin-bottom: 1.5rem; border-bottom: 2px solid #fbcfe8; padding-bottom: 0.5rem; }
.grupo-filtro { margin-bottom: 1.2rem; }
.precios-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
label { display: block; font-weight: bold; margin-bottom: 0.5rem; color: #4b5563; font-size: 0.9rem; }
.input-base { width: 100%; padding: 0.6rem; border: 2px solid #e2e8f0; border-radius: 6px; box-sizing: border-box; outline: none; transition: border-color 0.3s; }
.input-base:focus { border-color: #e83e8c; }
.btn-limpiar { width: 100%; background: #f1f5f9; color: #64748b; border: 2px solid #e2e8f0; padding: 0.8rem; border-radius: 6px; font-weight: bold; cursor: pointer; transition: all 0.3s; margin-top: 1rem; }
.btn-limpiar:hover { background: #e2e8f0; color: #1f2937; border-color: #cbd5e1; }
</style>