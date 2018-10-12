<script>
export default {
  name: 'product-show',
  data () {
    return {
      stock: 0,
      showStock: false
    }
  },
  methods: {
    skuSelected (price, stock) {
      this.$refs.price.innerHTML = price
      this.showStock = true
      this.stock = stock
    }
  }
}
</script>
