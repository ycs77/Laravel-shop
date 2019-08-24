<template>
  <div class="table-responsive">
    <table v-if="attrs.length === 1" class="table">
      <tbody>
        <tr>
          <th v-for="(item, index) in attrs[0].items" :key="index">{{ item }}</th>
        </tr>
        <tr>
          <td v-for="(item, index) in attrs[0].items" :key="index">
            <slot :attrItemIndexs="[index]"></slot>
          </td>
        </tr>
      </tbody>
    </table>

    <table v-else-if="attrs.length >= 2" class="table">
      <tbody>
        <tr>
          <th></th>
          <th v-for="(item1, index1) in attrs[0].items" :key="index1">{{ item1 }}</th>
        </tr>
        <tr v-for="(item2, index2) in attrs[1].items" :key="index2">
          <th>{{ item2 }}</th>
          <td v-for="(item1, index1) in attrs[0].items" :key="index1">
            <slot :attrItemIndexs="[index1, index2].concat(tabAttrs)"></slot>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    attrs: Array,
    tabAttrs: {
      type: Array,
      default: () => []
    }
  }
}
</script>
