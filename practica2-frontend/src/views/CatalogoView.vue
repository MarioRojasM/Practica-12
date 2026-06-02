<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const categorias = ref([])
const categoriaActiva = ref(null)
const productos = ref([])

onMounted(async () => {
  try {
    const { data } = await axios.get('http://localhost:8000/api/categorias')
    categorias.value = data.data
  } catch (error) {
    console.error("Error al cargar las categorías:", error)
  }
})

const filtrarPorCategoria = async (cat) => {
  categoriaActiva.value = cat
  try {
    const { data } = await axios.get(`http://localhost:8000/api/categorias/${cat.id}/productos`)
    productos.value = data.data
  } catch (error) {
    console.error("Error al cargar los productos:", error)
  }
}

// Una función súper sencilla para el botón por ahora
const agregarAlCarrito = (producto) => {
  alert(`Agregaste "${producto.nombre}" al carrito 🛒`)
}
</script>

<template>
  <div class="catalogo">
    <h2>Catálogo por Categorías</h2>

    <div class="tabs-container">
      <button 
        v-for="cat in categorias" 
        :key="cat.id"
        @click="filtrarPorCategoria(cat)"
        class="tab-btn"
        :class="{ 'activo': categoriaActiva?.id === cat.id }"
      >
        {{ cat.nombre }}
      </button>
    </div>

    <div class="grid-productos" v-if="productos.length > 0">
      <div v-for="producto in productos" :key="producto.id" class="card">
        
        <div class="placeholder-img">📷 Sin imagen</div>

        <h3>{{ producto.nombre }}</h3>
        <p class="precio">${{ producto.precio }}</p>
        
        <button @click="agregarAlCarrito(producto)" class="btn-primary">
          Agregar al carrito
        </button>
      </div>
    </div>
    
    <div class="sin-resultados" v-else>
      <p>Selecciona una categoría para ver sus productos 👆</p>
    </div>
  </div>
</template>

<style scoped>
h2 { color: #333; margin-bottom: 1.5rem; border-left: 4px solid #e83e8c; padding-left: 10px; }
.tabs-container { display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; }
.tab-btn { padding: 0.8rem 1.5rem; border: 2px solid #e2e8f0; background: white; border-radius: 20px; font-weight: bold; cursor: pointer; transition: all 0.3s; color: #64748b; }
.tab-btn:hover { border-color: #e83e8c; color: #e83e8c; }
.tab-btn.activo { background: #e83e8c; border-color: #e83e8c; color: white; }
.grid-productos { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 2rem; }
.card { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #f8f9fa; text-align: center; }
.placeholder-img { width: 100%; height: 150px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; border-radius: 8px; margin-bottom: 1rem; color: #94a3b8; font-weight: bold; }
.card h3 { margin-top: 0; color: #1f2937; font-size: 1.1rem; }
.precio { font-size: 1.5rem; font-weight: 900; color: #e83e8c; margin: 0 0 1rem 0; }
.btn-primary { background-color: #e83e8c; color: white; border: none; padding: 0.8rem; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; transition: background 0.3s; }
.btn-primary:hover { background-color: #d2337d; }
.sin-resultados { text-align: center; padding: 3rem; background: #fff5f8; border-radius: 8px; color: #e83e8c; font-weight: bold; }
</style>