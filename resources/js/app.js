import './bootstrap'
import h from './lib/helper'

import Vue from 'vue'

import SelectDistrict from './components/SelectDistrict.vue'
import UserAddressesCreateAndEdit from './components/UserAddressesCreateAndEdit.vue'
import SearchBar from './components/SearchBar.vue'
import ProductShow from './components/ProductShow.vue'

window.Vue = Vue
for (const k in h) window[k] = h[k]

Vue.component('SelectDistrict', SelectDistrict)
Vue.component('UserAddressesCreateAndEdit', UserAddressesCreateAndEdit)
Vue.component('SearchBar', SearchBar)
Vue.component('ProductShow', ProductShow)

setOptions({
    container: '#alert-block'
})

const app = new Vue({
    el: '#app'
})
