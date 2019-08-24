<template>
  <product-sku-table :attrs="attrs" v-bind="$attrs" v-slot="{ attrItemIndexs }">
    <div class="product-form-sku-field">
      <input type="hidden" :name="`skus[${getSkuIndex(attrItemIndexs)}][attr_items_index]`" :value="JSON.stringify(attrItemIndexs)">
      <div>
        <label class="control-label">單價</label>
        <input type="number" class="form-control" v-model.number="getSku(attrItemIndexs).price" :name="`skus[${getSkuIndex(attrItemIndexs)}][price]`" min="0" />
      </div>
      <div>
        <label class="control-label">庫存</label>
        <input type="number" class="form-control" v-model.number="getSku(attrItemIndexs).stock" :name="`skus[${getSkuIndex(attrItemIndexs)}][stock]`" min="0" />
      </div>
    </div>
  </product-sku-table>
</template>

<script>
import ProductSkuTable from './ProductSkuTable'

export default {
  components: {
    ProductSkuTable
  },
  props: {
    attrs: Array,
    skus: Array
  },
  methods: {
    getSku(attrItemsIndex) {
      const sku = this.skus.find(sku => JSON.stringify(sku.attr_items_index) === JSON.stringify(attrItemsIndex))

      if (sku) return sku

      this.setDefaultSku(attrItemsIndex)
      return this.skus[this.skus.length - 1]
    },
    getSkuIndex(attrItemsIndex) {
      const skuIndex = this.skus.findIndex(sku => JSON.stringify(sku.attr_items_index) === JSON.stringify(attrItemsIndex))

      if (skuIndex > -1) return skuIndex

      this.setDefaultSku(attrItemsIndex)
      return this.skus.length - 1
    },
    setDefaultSku(attrItemsIndex) {
      this.skus.push({
        price: 0,
        stock: 0,
        attr_items_index: attrItemsIndex
      })
    }
  }
}
</script>
