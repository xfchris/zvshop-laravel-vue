import '@popperjs/core'
import { btnWaitSubmit } from './functions'

window._ = require('lodash')
window.$ = window.jQuery = require('jquery')
window.bootstrap = require('bootstrap')

// window.$('[data-toggle="tooltip"]').tooltip()
// require('datatables.net/js/jquery.dataTables')
// require('datatables.net-bs5/js/dataTables.bootstrap5')

btnWaitSubmit()

window.axios = require('axios')
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
