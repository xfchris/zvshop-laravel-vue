import '@popperjs/core'

window._ = require('lodash')
window.$ = window.jQuery = require('jquery')
window.bootstrap = require('bootstrap')

window.axios = require('axios')
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

window.swal = require('sweetalert2')
