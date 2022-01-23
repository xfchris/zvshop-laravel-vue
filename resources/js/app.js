import { createApp } from 'vue'
import BtnBlockUser from './components/users/BtnBlockUser'
import BtnInputFile from './components/users/BtnInputFile'
import BtnTumbnail from './components/BtnTumbnail.vue'
import BtnSubmit from './components/BtnSubmit.vue'
import InputFile from './components/InputFile.vue'
import InputQuantity from './components/orders/InputQuantity.vue'
const app = createApp({})

app.component('btn-block-user', BtnBlockUser)
app.component('btn-submit', BtnSubmit)
app.component('btn-input-file', BtnInputFile)
app.component('btn-tumbnail', BtnTumbnail)
app.component('input-file', InputFile)
app.component('input-quantity', InputQuantity)

app.mount('#app')

require('./bootstrap')
