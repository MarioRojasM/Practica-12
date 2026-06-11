<script setup>
import { ref, onMounted, onUnmounted } from 'vue' // Agregué estos imports básicos de Vue por si tu proyecto no los auto-importa
import echo from '@/plugins/echo'

const pedidosNuevos = ref([])
const alertasStock  = ref([])

onMounted(() => {
    echo.private('admin-panel')
        .listen('NuevoPedidoRecibido', (e) => {
            pedidosNuevos.value.unshift(e)
            // Auto-eliminar después de 10 segundos
            setTimeout(() => pedidosNuevos.value.pop(), 10000)
        })
        .listen('StockBajoAlerta', (e) => {
            alertasStock.value.unshift(e)
        })
})

onUnmounted(() => echo.leave('admin-panel'))
</script>

<template>
    <TransitionGroup name='toast'>
        <div v-for='p in pedidosNuevos' :key='p.id' class='toast'>
            🛒 Nuevo pedido #{{ p.id }} de {{ p.cliente }} — ${{ p.total }}
        </div>
    </TransitionGroup>

    <div v-for='a in alertasStock' :key='a.producto_id' class='alerta-stock'>
        ⚠️ Stock bajo: {{ a.nombre }} ({{ a.stock_actual }} unidades)
    </div>
</template>

<style scoped>
/* Agrego unos estilos rápidos para que no se vea feo, puedes modificarlos */
.toast, .alerta-stock {
    background-color: #fce4ec;
    color: #e91e63;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 8px;
    border: 1px solid #f48fb1;
    font-weight: bold;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.toast-enter-active, .toast-leave-active { transition: all 0.5s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateX(30px); }
</style>