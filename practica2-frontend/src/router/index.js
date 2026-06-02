import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', redirect: '/catalogo' }, // Que por defecto te mande al catálogo público
    
    // Rutas públicas
    { path: '/login', name: 'login', component: () => import('../views/LoginView.vue') },
    { path: '/register', name: 'register', component: () => import('../views/RegisterView.vue') },
    { path: '/catalogo', name: 'catalogo', component: () => import('../views/CatalogoView.vue') },
    
    // Rutas protegidas (Requieren inicio de sesión)
    { 
      path: '/dashboard', 
      name: 'dashboard', 
      component: () => import('../views/DashboardView.vue'), 
      meta: { requiresAuth: true } 
    },
    { 
      path: '/admin/nuevo', 
      name: 'nuevo-producto', 
      component: () => import('../views/admin/NuevoProducto.vue'), 
      meta: { requiresAuth: true } 
    },
    { 
      path: '/admin/productos', 
      name: 'admin-productos', 
      component: () => import('../views/admin/Productos.vue'), // Asumiendo que tienes esta vista
      meta: { requiresAuth: true } 
    },
    
  ]
})

// GUARD DE NAVEGACIÓN
router.beforeEach((to, from) => {
  const auth = useAuthStore()
  
  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return { name: 'login' }
  } 
  else if ((to.name === 'login' || to.name === 'register') && auth.isAuthenticated) {
    return { name: 'dashboard' }
  } 
})

export default router