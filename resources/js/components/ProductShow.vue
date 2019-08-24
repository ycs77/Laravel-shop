<script>
export default {
  name: 'product-show',
  props: {
    initSkus: {
      type: Array,
      default: () => {}
    }
  },
  data () {
    return {
      skus: this.initSkus.map(sku => {
        if (typeof sku.attr_items_index === 'string') {
          sku.attr_items_index = JSON.parse(sku.attr_items_index)
        }
        return sku
      }),
      attrItemsIndex: [],
      stock: 0,
      showStock: false
    }
  },
  computed: {
    sku() {
      return this.skus.find(sku => JSON.stringify(sku.attr_items_index) === JSON.stringify(this.attrItemsIndex))
    }
  },
  methods: {
    skuSelected(attrIndex, itemIndex) {
      this.$set(this.attrItemsIndex, attrIndex, itemIndex)
      if (this.sku) {
        this.$refs.price.innerHTML = this.sku.price
        this.stock = this.sku.stock
        this.showStock = true
      }
    }
  }
}
</script>
