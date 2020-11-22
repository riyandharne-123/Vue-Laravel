import Vue from 'vue'
import VueRouter from 'vue-router'
import axios from 'axios'

import Admin from './components/Admin'
import Login from './components/Login'
import Register from './components/Register'
import Roles from './components/Roles'

Vue.use(VueRouter)

const routes = [
  {
   path:'/',
   redirect:'/login',
  },
  {
    path:'/login',
    component:Login,
    name:'Login',
  },
  {
    path:'/register',
    component:Register,
    name:'Register',
  },
  {
    path:'/admin',
    component:Admin,
    name:'Admin',
    beforeEnter: (to,from,next) => {
      axios.get('/api/verify_user')
      .then(res => {
        if(res.data.api_token == localStorage.getItem('token'))
        {
          next()
        }
      })
      .catch(err => 
        next('/login'))
      }
  },
  {
    path:'/roles',
    component:Roles,
    name:'Roles',
    beforeEnter: (to,from,next) => {
    axios.get('/api/verify_user')
    .then(res => {
      if(res.data.api_token == localStorage.getItem('token'))
      {
        next()
      }
    })
    .catch(err => 
      next('/login'))
    }
  },
]

const router = new VueRouter({routes})

//auth guard
router.beforeEach((to,from,next) => {
  const jwtToken = `Bearer ${localStorage.getItem('token')}`;
window.axios.defaults.headers.common['authorization'] = jwtToken;
  next();
})

export default router