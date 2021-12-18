import { createApp } from 'vue'
import global from './store/global'
import BtnBlockUser from './components/users/BtnBlockUser'

const app = createApp({
  provide: {
    global
  }
})
app.component('btn-block-user', BtnBlockUser)
app.mount('#app')

require('./bootstrap')
