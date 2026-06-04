<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const productos = ref([])

onMounted(async () => {
  try {
    // Hacemos la petición a la API (Vitest va a interceptar esto y nos dará el "Mouse")
    const response = await axios.get('/api/productos')
    
    // Guardamos los datos. El mock de tu prueba envía { data: { data: [...] } }
    productos.value = response.data.data || response.data
  } catch (error) {
    console.error("Error al cargar productos:", error)
  }
})
</script>

<template>
  <div class="productos-lista">
    <h2>Catálogo de Productos</h2>
    
    <p v-if="productos.length === 0">Cargando productos...</p>
    
    <div class="grid" v-else>
      <div v-for="producto in productos" :key="producto.id" class="card">
        <h3>{{ producto.nombre }}</h3>
        <p>${{ producto.precio }}</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.productos-lista { padding: 2rem; }
.grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); }
.card { border: 1px solid #e2e8f0; padding: 1rem; border-radius: 8px; text-align: center; }
h3 { color: #1f2937; margin-bottom: 0.5rem; }
p { color: #e83e8c; font-weight: bold; }
</style>