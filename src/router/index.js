import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
    {
    path: '/customers',
    name: 'customers',
    component: () => import('../views/Customers.vue')
  },
  {
    path: '/student',
    name: 'student',
    component: () => import('../views/Student.vue')
  },
  {
    path: '/add_customer',
    name: 'add_customer',
    component: () => import('../views/Add_customer.vue')  
  },
    {
    path: '/add_student',
    name: 'add_student',
    component: () => import('../views/Add_student.vue')  
  },
  {
    path: '/about',
    name: 'about',
    component: () => import('../views/AboutView.vue')
  },
    {
    path: '/product',
    name: 'product',
    component: () => import('../views/Product.vue')
  },
      {
    path: '/login',
    name: 'login',
    component: () => import('../views/Login.vue')
  },
        {
    path: '/customer_edit',
    name: 'customer_edit',
    component: () => import('../views/Customer_edit.vue')
  },
      {
    path: '/product_edit',
    name: 'product_edit',
    component: () => import('../views/product_edit.vue')
  },
      {
    path: '/employee',
    name: 'employee',
    component: () => import('../views/Employee.vue')
  },
      {
    path: '/add_product',
    name: 'add_product',
    component: () => import('../views/Add_product.vue')
  },
    {
    path: '/showproduct',
    name: 'showproduct',
    component: () => import('../views/ShowProduct.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
