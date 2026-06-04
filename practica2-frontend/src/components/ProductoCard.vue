<script setup>
defineProps({
  producto: {
    type: Object,
    required: true
  }
})

defineEmits(['agregar-carrito'])
</script>

<template>
  <div class="producto-card">
    <h3>{{ producto.nombre }}</h3>
    <p class="precio">${{ producto.precio }}</p>
    
    <button data-test="btn-agregar" @click="$emit('agregar-carrito', producto)">
      Agregar al Carrito
    </button>
  </div>
</template>

<style scoped>
.producto-card {
  border: 1px solid #e2e8f0;
  padding: 1rem;
  border-radius: 8px;
  text-align: center;
  background: white;
}
.precio {
  font-weight: bold;
  color: #e83e8c;
}
button {
  background-color: #e83e8c;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
}
</style>