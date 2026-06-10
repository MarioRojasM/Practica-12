<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import PedidoEstado from '../components/PedidoEstado.vue'

const productos = ref([])
const carrito = ref([])
const pedidoActivoId = ref(null)

// 1. Cargamos los productos con la ruta COMPLETA y su Token
const cargarProductos = async () => {
  try {
    const { data } = await axios.get('http://localhost:8000/api/productos', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    })
    productos.value = data.data || data 
  } catch (error) {
    console.error("Error al cargar productos:", error)
  }
}

const agregarAlCarrito = (producto) => {
  const itemExistente = carrito.value.find(item => item.producto_id === producto.id)
  
  if (itemExistente) {
    itemExistente.cantidad++
  } else {
    carrito.value.push({
      producto_id: producto.id,
      nombre: producto.nombre,
      precio: parseFloat(producto.precio),
      cantidad: 1
    })
  }
}

const totalCarrito = computed(() => {
  return carrito.value.reduce((total, item) => total + (item.precio * item.cantidad), 0)
})

// 2. Procesamos la compra también con la ruta COMPLETA
const procesarCompra = async () => {
  try {
    const response = await axios.post('http://localhost:8000/api/pedidos', { 
      items: carrito.value 
    }, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}` 
      }
    })
    
    pedidoActivoId.value = response.data.pedido_id
    carrito.value = [] 
  } catch (error) {
    console.error('Error al procesar la compra:', error)
    alert('Hubo un error al conectar con Laravel. Revisa la consola.')
  }
}

onMounted(() => {
  cargarProductos()
})
</script>

<template>
  <div class="carrito-container">
    <h1 class="titulo">🛒 Mi Tienda Online</h1>

    <div class="grid-productos" v-if="!pedidoActivoId">
      <div v-for="producto in productos" :key="producto.id" class="tarjeta-producto">
        <h3>{{ producto.nombre }}</h3>
        <p class="precio">${{ producto.precio }}</p>
        <button @click="agregarAlCarrito(producto)" class="btn-agregar">
          Añadir al Carrito
        </button>
      </div>
    </div>

    <div class="resumen-carrito" v-if="carrito.length > 0 && !pedidoActivoId">
      <h2>Resumen de Compra</h2>
      <ul>
        <li v-for="item in carrito" :key="item.producto_id">
          {{ item.cantidad }}x {{ item.nombre }} - ${{ item.precio * item.cantidad }}
        </li>
      </ul>
      <h3 class="total">Total: ${{ totalCarrito }}</h3>
      
      <button @click="procesarCompra" class="btn-comprar">
        Finalizar Compra
      </button>
    </div>

    <div class="estado-pedido" v-if="pedidoActivoId">
      <PedidoEstado :pedidoId="pedidoActivoId" />
    </div>
  </div>
</template>

<style scoped>
.carrito-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
  font-family: sans-serif;
  color: #333;
}

.titulo {
  text-align: center;
  color: #e83e8c;
  margin-bottom: 2rem;
}

.grid-productos {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1.5rem;
  margin-bottom: 3rem;
}

.tarjeta-producto {
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 1.5rem;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.precio {
  font-weight: bold;
  font-size: 1.2rem;
  color: #e83e8c;
  margin: 1rem 0;
}

.btn-agregar {
  background: white;
  color: #e83e8c;
  border: 2px solid #e83e8c;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-agregar:hover {
  background: #e83e8c;
  color: white;
}

.resumen-carrito {
  background: #fdf2f8;
  padding: 2rem;
  border-radius: 12px;
  border: 1px solid #fbcfe8;
}

.resumen-carrito ul {
  list-style: none;
  padding: 0;
}

.resumen-carrito li {
  padding: 0.5rem 0;
  border-bottom: 1px solid #fbcfe8;
}

.total {
  text-align: right;
  font-size: 1.5rem;
  color: #e83e8c;
  margin-top: 1rem;
}

.btn-comprar {
  width: 100%;
  background: #e83e8c;
  color: white;
  border: none;
  padding: 12px;
  font-size: 1.2rem;
  font-weight: bold;
  border-radius: 8px;
  cursor: pointer;
  margin-top: 1rem;
}

.estado-pedido {
  margin-top: 2rem;
}
</style>