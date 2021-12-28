import { createApp } from 'vue'
import global from './store/global'
import BtnBlockUser from './components/users/BtnBlockUser'
import BtnTumbnail from './components/BtnTumbnail.vue'
import InputFile from './components/InputFile.vue'

const app = createApp({
  provide: {
    global
  }
})

app.component('btn-block-user', BtnBlockUser)
app.component('btn-tumbnail', BtnTumbnail)
app.component('input-file', InputFile)

app.mount('#app')

require('./bootstrap')
