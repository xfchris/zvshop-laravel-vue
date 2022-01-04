import { createApp } from 'vue'
import BtnBlockUser from './components/users/BtnBlockUser'
import BtnTumbnail from './components/BtnTumbnail.vue'
import InputFile from './components/InputFile.vue'

const app = createApp({})

app.component('btn-block-user', BtnBlockUser)
app.component('btn-tumbnail', BtnTumbnail)
app.component('input-file', InputFile)

app.mount('#app')

require('./bootstrap')
