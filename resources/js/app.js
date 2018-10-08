import './bootstrap'
import h from './lib/helper'

import Vue from 'vue'

import SelectDistrict from './components/SelectDistrict.vue'
import UserAddressesCreateAndEdit from './components/UserAddressesCreateAndEdit.vue'

window.Vue = Vue
for (const k in h) {
    window[k] = h[k]
}

setOptions({
    container: '#alert-block'
})

Vue.component('SelectDistrict', SelectDistrict)
Vue.component('UserAddressesCreateAndEdit', UserAddressesCreateAndEdit)

const app = new Vue({
    el: '#app'
})
