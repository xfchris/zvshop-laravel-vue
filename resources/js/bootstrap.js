import '@popperjs/core'
import { btnWaitSubmit } from './functions'

window._ = require('lodash')
window.$ = window.jQuery = require('jquery')
window.bootstrap = require('bootstrap')

btnWaitSubmit()

window.axios = require('axios')
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
