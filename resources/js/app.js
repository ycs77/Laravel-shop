import './bootstrap'
import h from './helper'

import Vue from 'vue'

window.Vue = Vue
for (const k in h) {
    window[k] = h[k]
}

setOptions({
    container: '#alert-block'
})

const app = new Vue({
    el: '#app'
})
