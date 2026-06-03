<script setup>
const props = defineProps({
  meta: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['cambio-pagina'])

const cambiarPagina = (pagina) => {
  // Verificamos que la página sea válida antes de emitir el evento
  if (pagina >= 1 && pagina <= props.meta.last_page) {
    emit('cambio-pagina', pagina)
  }
}
</script>

<template>
  <div class="paginacion" v-if="meta && meta.last_page > 1">
    
    <button 
      @click="cambiarPagina(1)" 
      :disabled="meta.current_page === 1"
      class="btn-pag"
    >
      &laquo; Primera
    </button>

    <button 
      @click="cambiarPagina(meta.current_page - 1)" 
      :disabled="meta.current_page === 1"
      class="btn-pag"
    >
      &lsaquo; Anterior
    </button>

    <span class="info-pag">
      Página {{ meta.current_page }} de {{ meta.last_page }}
    </span>

    <button 
      @click="cambiarPagina(meta.current_page + 1)" 
      :disabled="meta.current_page === meta.last_page"
      class="btn-pag"
    >
      Siguiente &rsaquo;
    </button>

    <button 
      @click="cambiarPagina(meta.last_page)" 
      :disabled="meta.current_page === meta.last_page"
      class="btn-pag"
    >
      Última &raquo;
    </button>

  </div>
</template>

<style scoped>
.paginacion { display: flex; justify-content: center; align-items: center; gap: 1rem; margin-top: 3rem; padding-bottom: 2rem; flex-wrap: wrap; }
.btn-pag { background: white; border: 2px solid #fbcfe8; color: #e83e8c; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: bold; cursor: pointer; transition: all 0.3s; }
.btn-pag:hover:not(:disabled) { background: #e83e8c; color: white; border-color: #e83e8c; }
.btn-pag:disabled { border-color: #e2e8f0; color: #94a3b8; cursor: not-allowed; opacity: 0.7; }
.info-pag { font-weight: bold; color: #4b5563; }
</style>