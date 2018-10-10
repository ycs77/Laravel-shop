<script>
export default {
  name: 'user-addresses-create-and-edit',
  props: {
    initAddress: {
      type: String,
      default: ''
    }
  },
  data () {
    return {
      city: '',
      district: '',
      zip_code: '',
      address: this.initAddress
    }
  },
  computed: {
    fullAddress () {
      return `${this.zip_code}${this.zip_code ? ' ' : ''}${this.city}${this.district}${this.address}`
    }
  },
  methods: {
    onDistrictChanged (val) {
      this.city = val[0] || ''
      this.district = val[1] || ''
      this.zip_code = val[2] || ''
    }
  }
}
</script>
