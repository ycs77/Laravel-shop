import axios from 'axios'
import 'sweetalert'

window.axios = axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

if (LA.token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = LA.token
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}
