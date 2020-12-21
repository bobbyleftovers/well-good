<template>
  <div id="poststuff">
    <input-csv @input="onCSVLoaded"/>
    <json-importer :json="json"/>
  </div>
</template>

<script>
import InputCsv from './InputCSV.vue'
import JsonImporter from './JSONImporter.vue'

export default {
  components: { InputCsv, JsonImporter },
  data () {
    return {
      json: false
    }
  },
  methods: {
    onCSVLoaded (json) {
      this.json = json
    }
  }
}
</script>
