import '@popperjs/core'

window._ = require('lodash')

const bootstrap = require('bootstrap')

window.bootstrap = bootstrap

window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
