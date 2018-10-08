<script>
import twCity from '../lib/tw-city'

export default {
  name: 'select-district',
  props: {
    initValue: {
      type: Array,
      default: () => ([])
    }
  },
  data () {
    return {
      cities: twCity.counties, // 縣市
      districts: [],           // 鄉鎮市區
      cityId: '',              // 當前選中的縣市的索引值
      districtId: '',          // 當前選中的鄉鎮市區的索引值
      zip_code: ''             // 郵遞區號
    }
  },
  watch: {
    // 當縣市選項發生改變時觸發
    cityId (val) {
      if (isNaN(Number(val))) {
        this.districts = []
        this.districtId = ''
        return
      }
      // 設定當前選中的鄉鎮市區
      this.districts = twCity.districts[val][0]
      this.districtId = ''

      this.submit()
    },
    // 當鄉鎮市區選項發生改變時觸發
    districtId (val) {
      // 設定郵遞區號
      this.zip_code = twCity.districts[this.cityId][1][val]

      this.submit()
    }
  },
  methods: {
    setFromValue (value) {
      value = value.filter(v => v)
      if (!value.length) {
        this.cityId = ''
        return
      }

      // 設定縣市
      const cityId = this.cities.findIndex(v => v === value[0])
      if (isNaN(Number(cityId))) {
        this.cityId = ''
        return
      }
      this.cityId = cityId

      // 設定鄉鎮市區
      const districtId = twCity.districts[cityId][0].findIndex(v => v === value[1])
      if (isNaN(Number(districtId))) {
        this.districtId = ''
        return
      }
      // 因修改cityId觸發了Watch將鄉鎮市區設為預設值
      // 所以使用$nextTick等到Watch執行完畢後才設定新值
      this.$nextTick(() => this.districtId = districtId)
    },
    submit () {
      this.$emit('change', [
        this.cities[this.cityId],
        this.districts[this.districtId],
        this.zip_code
      ])
    }
  },
  created () {
    this.setFromValue(this.initValue)
  }
}
</script>
