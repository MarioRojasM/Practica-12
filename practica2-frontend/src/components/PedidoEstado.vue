<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

const { pedidoId } = defineProps(['pedidoId']) // [cite: 124]
const estado     = ref('procesando') // [cite: 125]
const emailListo = ref(false) // [cite: 126]
let  intervalo   = null // [cite: 127]

onMounted(() => {
  // Consultamos el estado del pedido cada 3 segundos 
  intervalo = setInterval(async () => {
    try {
      const { data } = await axios.get(`/api/pedidos/${pedidoId}`) // [cite: 130]
      emailListo.value = !!data.email_enviado_at // [cite: 131]
      
      if (emailListo.value) {
        clearInterval(intervalo) // Detenemos la consulta cuando ya se envió [cite: 132]
      }
    } catch (error) {
      console.error("Error consultando el pedido:", error)
    }
  }, 3000) // [cite: 133]
})

onUnmounted(() => clearInterval(intervalo)) // [cite: 135]
</script>

<template>
  <div class="estado-container">
    <div v-if="!emailListo" class="estado procesando"> ⏳ Procesando tu pedido... </div>
    <div v-else class="estado listo"> ✅ ¡Pedido confirmado! Revisa tu correo. </div>
  </div>
</template>

<style scoped>
.estado-container {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem;
  background-color: #ffffff;
  border: 2px solid #e83e8c;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(232, 62, 140, 0.1);
}

.estado {
  font-size: 1.2rem;
  font-weight: bold;
}

.procesando {
  color: #e83e8c;
  animation: latido 1.5s infinite;
}

.listo {
  color: #28a745;
}

@keyframes latido {
  0% { opacity: 0.5; }
  50% { opacity: 1; }
  100% { opacity: 0.5; }
}
</style>