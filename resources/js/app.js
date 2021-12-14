import { createApp } from 'vue'
import global from './store/global'

const app = createApp({
  provide: {
    global
  }
})
app.mount('#app')

require('./bootstrap')
