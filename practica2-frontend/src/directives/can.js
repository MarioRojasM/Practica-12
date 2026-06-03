import { useAuthStore } from '../stores/auth'

export const vCan = {
  mounted(el, binding) {
    const auth = useAuthStore()
    const permiso = binding.value // Este será el texto que le pases, ej: 'crear'

    // Si el permiso es falso en Pinia, ocultamos el elemento
    if (!auth.permisos[permiso]) {
      el.style.display = 'none'
    }
  }
}