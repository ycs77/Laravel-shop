<template>
  <div v-if="show">
    <ul class="nav nav-tabs" role="tablist">
      <li v-for="(item, index) in items" :key="index" :class="tabClass(index)" role="presentation">
        <a :href="'#' + getPrefix(item)" :aria-controls="getPrefix(item)" role="tab" data-toggle="tab">{{ item.label }}</a>
      </li>
    </ul>

    <div class="tab-content">
      <div v-for="(item, index) in items" :key="index" :id="getPrefix(item)" :class="tabPaneClass(index)" role="tabpanel">
        <product-sku-tab v-if="!isNestedEnd" :attrs="attrs" :skus="skus" :tabs="tabs" :prefix="getPrefix(item)" :depth="depth + 1" :tab-attrs="getTabAttrs(index)"></product-sku-tab>

        <product-sku-table-wrapper v-else :attrs="attrs" :skus="skus" :tab-attrs="getTabAttrs(index)"></product-sku-table-wrapper>
      </div>
    </div>
  </div>

  <product-sku-table-wrapper v-else-if="attrs.length >= 1" :attrs="attrs" :skus="skus"></product-sku-table-wrapper>

  <div v-else class="help-block"><i class="fa fa-fw fa-info-circle"></i>請新增 SKU 欄位</div>
</template>

<script>
import ProductSkuTableWrapper from './ProductSkuTableWrapper'

export default {
  components: {
    ProductSkuTableWrapper
  },
  props: {
    prefix: String,
    /**
     * Attrs example:
     * [
     *   {
     *     id: 'example-tab',
     *     label: 'Example tab'
     *   }
     * ]
     */
    tabs: Function,
    depth: {
      type: Number,
      default: 0
    },
    attrs: Array,
    skus: Array,
    tabAttrs: {
      type: Array,
      default: () => []
    },
  },
  data() {
    return {
      prependCount: 2
    }
  },
  computed: {
    items() {
      return this.tabs(this.index)
    },
    show() {
      return typeof this.index !== 'undefined'
    },
    index() {
      if (this.attrs.length <= this.prependCount) {
        return
      }

      return this.depth + this.prependCount
    },
    isNestedEnd() {
      return this.attrs.length === this.depth + this.prependCount + 1
    }
  },
  methods: {
    tabClass(index) {
      return {
        active: index === 0
      }
    },
    tabPaneClass(index) {
      return [
        'tab-pane',
        'fade',
        index === 0 ? 'in' : '',
        index === 0 ? 'active' : ''
      ]
    },
    getPrefix(item) {
      return `${this.prefix}-${item.id}`
    },
    getTabAttrs(index) {
      return this.tabAttrs.concat(index)
    }
  }
}
</script>
