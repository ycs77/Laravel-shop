import './bootstrap'

import Vue from 'vue'

import SelectDistrict from './components/SelectDistrict.vue'
import UserAddressesCreateAndEdit from './components/UserAddressesCreateAndEdit.vue'
import SearchBar from './components/SearchBar.vue'
import ProductShow from './components/ProductShow.vue'

window.Vue = Vue

Vue.component('SelectDistrict', SelectDistrict)
Vue.component('UserAddressesCreateAndEdit', UserAddressesCreateAndEdit)
Vue.component('SearchBar', SearchBar)
Vue.component('ProductShow', ProductShow)

const app = new Vue({
    el: '#app'
})
