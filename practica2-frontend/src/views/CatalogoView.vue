<script setup>
import { ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { useFiltros } from '../composables/useFiltros'
import PaginacionNav from '../components/PaginacionNav.vue'
import FiltrosPanel from '../components/FiltrosPanel.vue'

const route = useRoute()
const { filtros } = useFiltros()

const resultado = ref({ data: [], meta: {} })
const cargando = ref(false)

const cargarProductos = async () => {
  cargando.value = true
  try {
    // Si tienes ordenamiento (ej. "precio_desc"), lo separamos para Laravel
    let orden = undefined
    let dir = undefined
    if (filtros.orden) {
      const partes = filtros.orden.split('_')
      orden = partes[0]
      dir = partes[1]
    }

    const { data } = await axios.get('http://localhost:8000/api/productos', {
      params: {
        busqueda: filtros.busqueda,
        categoria_id: filtros.categoria_id,
        precio_min: filtros.precio_min,
        precio_max: filtros.precio_max,
        page: filtros.pagina,
        orden: orden,
        dir: dir
      }
    })
    resultado.value = data
  } catch (error) {
    console.error("Error al cargar los productos:", error)
  } finally {
    cargando.value = false
  }
}

// Recargar cuando cambien los filtros o la URL
watch(() => route.query, cargarProductos, { immediate: true })

const agregarAlCarrito = (producto) => {
  alert(`Agregaste "${producto.nombre}" al carrito 🛒`)
}
</script>

<template>
  <div class="catalogo-contenedor">
    <h2 class="titulo-catalogo">Catálogo de Productos</h2>

    <div v-if="cargando" class="estado-carga">
      <p>Cargando productos anime y más... ⏳</p>
    </div>

    <div v-else class="layout-catalogo">
      
      <aside class="columna-filtros">
        <FiltrosPanel :filtros="filtros" />
      </aside>

      <div class="columna-productos">
        
        <div v-if="resultado.data.length > 0">
          <div class="grid-productos">
            <div v-for="producto in resultado.data" :key="producto.id" class="card">
              
              <div class="placeholder-img">📷 Sin imagen</div>

              <h3>{{ producto.nombre }}</h3>
              <p class="precio">${{ producto.precio }}</p>
              
              <button @click="agregarAlCarrito(producto)" class="btn-primary">
                Agregar al carrito
              </button>
            </div>
          </div>

          <PaginacionNav 
            :meta="resultado.meta" 
            @cambio-pagina="filtros.pagina = $event" 
          />
        </div>
        
        <div class="sin-resultados" v-else>
          <p>No se encontraron productos con esos filtros 🕵️‍♂️</p>
          <p class="sub-texto">Intenta limpiar los filtros o buscar otra palabra.</p>
        </div>

      </div>
    </div>
  </div>
</template>

<style scoped>
/* Estilos Generales */
.catalogo-contenedor { padding: 1rem; max-width: 1200px; margin: 0 auto; }
.titulo-catalogo { color: #333; margin-bottom: 2rem; border-left: 4px solid #e83e8c; padding-left: 10px; }
.estado-carga { text-align: center; padding: 4rem; color: #e83e8c; font-weight: bold; font-size: 1.2rem; }

/* Grid principal (Panel izquierdo + Productos a la derecha) */
.layout-catalogo { display: grid; grid-template-columns: 300px 1fr; gap: 2rem; align-items: start; }

/* Si la pantalla es pequeña (celulares), pone los filtros arriba de los productos */
@media (max-width: 768px) {
  .layout-catalogo { grid-template-columns: 1fr; }
}

/* Grid interno para las tarjetas de productos */
.grid-productos { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }

/* Tarjetas de Producto */
.card { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #f8f9fa; text-align: center; display: flex; flex-direction: column; justify-content: space-between; }
.placeholder-img { width: 100%; height: 150px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; border-radius: 8px; margin-bottom: 1rem; color: #94a3b8; font-weight: bold; }
.card h3 { margin-top: 0; color: #1f2937; font-size: 1.1rem; flex-grow: 1; }
.precio { font-size: 1.5rem; font-weight: 900; color: #e83e8c; margin: 1rem 0; }

/* Botones */
.btn-primary { background-color: #e83e8c; color: white; border: none; padding: 0.8rem; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; transition: background 0.3s; }
.btn-primary:hover { background-color: #d2337d; }

/* Estado sin resultados */
.sin-resultados { text-align: center; padding: 4rem 2rem; background: #fff5f8; border-radius: 12px; color: #e83e8c; font-weight: bold; font-size: 1.1rem; border: 2px dashed #fbcfe8; }
.sub-texto { font-size: 0.9rem; color: #64748b; margin-top: 0.5rem; font-weight: normal; }
</style>