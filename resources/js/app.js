import { createApp } from 'vue'
import global from './store/global'
import BtnBlockUser from './components/users/BtnBlockUser'
import TumbnailProduct from './components/products/TumbnailProduct.vue'

const app = createApp({
  provide: {
    global
  }
})
app.component('btn-block-user', BtnBlockUser)
app.component('tumbnail-product', TumbnailProduct)

app.mount('#app')

require('./bootstrap')
