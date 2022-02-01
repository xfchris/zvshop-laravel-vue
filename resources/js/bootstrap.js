import '@popperjs/core'

window._ = require('lodash')
window.bootstrap = require('bootstrap')

window.axios = require('axios')
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

window.swal = require('sweetalert2')

window.setTimeout(() => document.querySelector('.alert-auto-close')?.remove(), 4000)
