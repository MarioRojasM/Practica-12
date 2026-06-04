// tests/ProductosList.test.js
import { mount, flushPromises } from '@vue/test-utils'
import { vi } from 'vitest'
import axios from 'axios'
import ProductosList from '@/views/ProductosList.vue'

// 1. Aquí "secuestramos" a Axios [cite: 274]
vi.mock('axios')

it('carga y muestra productos desde la API', async () => {
  // 2. Le decimos qué mentira contar cuando alguien use axios.get [cite: 276-280]
  axios.get.mockResolvedValue({
    data: { data: [
      { id: 1, nombre: 'Mouse', precio: 25.00 },
    ]},
  })

  // 3. Montamos el componente [cite: 281]
  const wrapper = mount(ProductosList)
  
  // 4. Esperamos a que todas las promesas (como axios) terminen [cite: 282]
  await flushPromises()

  // 5. Verificamos que sí intentó llamar a la ruta correcta y que dibujó el "Mouse" [cite: 283-284]
  expect(axios.get).toHaveBeenCalledWith('/api/productos')
  expect(wrapper.text()).toContain('Mouse')
})