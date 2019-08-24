export default {
  props: {
    /**
     * Attrs example:
     * [
     *   {
     *     id: 1,
     *     name: '顏色',
     *     items: ['紅色', '黃色']
     *   }
     * ]
     */
    initAttrs: Array,
    /**
     * Skus example:
     * [
     *   {
     *     id: 1,
     *     price: 100,
     *     stock: 20,
     *     attr_items_index: [0, 0]
     *   }
     * ]
     */
    initSkus: Array
  },
  data() {
    return {
      attrs: this.initAttrs.map(attr => {
        attr.items = attr.items || []
        return attr
      }),
      skus: this.initSkus.map(sku => {
        if (typeof sku.attr_items_index === 'string') {
          sku.attr_items_index = JSON.parse(sku.attr_items_index)
        }
        return sku
      })
    }
  },
  computed: {
    minPrice() {
      return this.skus.length ? Math.min(...this.skus.map(sku => sku.price)) : null
    }
  },
  watch: {
    attrs: {
      handler() {
        this.skus = this.skus.filter(sku => {
          return sku.attr_items_index.length === this.attrs.length
        })
      },
      deep: true
    },
    skus: {
      handler() {
        this.$emit('sku-min-price', this.minPrice)
      },
      deep: true,
      immediate: true
    }
  },
  methods: {
    tabsData(index) {
      if (!index) {
        return []
      }

      return this.attrs[index].items.map((attr, index) => {
        return {
          id: index,
          label: attr
        }
      })
    },
    addAttr() {
      if (this.attrs.length < 1) {
        this.realAddAttr()
      } else {
        this.confirm('確定要新增欄位?', '如果更改了欄位的數量，此商品的 SKU 將會重新計算。', () => {
          this.realAddAttr()
        })
      }
    },
    removeAttr(index) {
      this.confirm('確定要刪除欄位?', this.attrs.length <= 1 ? '' : '如果更改了欄位的數量，此商品的 SKU 將會重新計算。', () => {
        this.attrs.splice(index, 1)
      })
    },
    realAddAttr() {
      this.attrs.push({
        name: '',
        items: ['']
      })
      this.$nextTick(() => {
        this.$refs[`attrs[${this.attrs.length - 1}][name]`][0].focus()
      })
    },
    addAttrItem(attr_index) {
      this.attrs[attr_index].items.push('')
      this.$nextTick(() => {
        this.$refs[`attrs[${attr_index}][items][${this.attrs[attr_index].items.length - 1}]`][0].focus()
      })
    },
    removeAttrItem(attr_index, item_index) {
      this.attrs[attr_index].items.splice(item_index, 1)
    },
    confirm(title, text, callback) {
      swal({
        title: title,
        text: text,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: '確認',
        cancelButtonText: '取消',
        showLoaderOnConfirm: true,
        preConfirm() {
          if (callback) callback()
        }
      })
    }
  }
}
