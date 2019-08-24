import Vue from 'vue'
import ProductSku from './components/ProductSku'
import ProductSkuTab from './components/ProductSkuTab'

Vue.component('product-sku-tab', ProductSkuTab)

$(document).on('ready pjax:end', function () {
  window.vm = undefined
  if (document.getElementById('vue-product-form')) {
    window.vm = new Vue({
      components: {
        ProductSku
      },
      data: {
        minPrice: null
      },
      computed: {
        showMinPrice() {
          return typeof this.minPrice === 'number'
        }
      },
      methods: {
        onSkuMinPriceUpdated(minPrice) {
          this.minPrice = minPrice
        }
      },
      mounted() {
        /** @see resources/views/admin/products/form.blade.php */
        init()
      }
    }).$mount('#vue-product-form')
  }
})
