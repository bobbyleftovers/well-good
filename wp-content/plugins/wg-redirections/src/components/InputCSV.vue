<template>
    <div class="input-csv">
      <p><strong>Import local CSV. File should contain the following fields</strong></p>
        <div class="input-csv__info">
          <pre>redirect_from</pre> <small>(mandatory)</small><br>
          <pre>redirect_to</pre> <small>(optional, will default to home page)</small><br>
        </div>
       <p><strong>Other optional fields:</strong></p>
        <div class="input-csv__info">
          <pre>http_response</pre> <small>(default: 301)</small><br>
          <pre>skip_amp_on_redirect</pre> <small>(default: 0)</small><br>
          <pre>remove_query_on_redirect</pre> <small>(default: 0)</small><br>
          <pre>is_active</pre> <small>(default: 1)</small><br>
          <pre>add_to_sitemap</pre> <small>(default: 0)</small><br>
          <pre>type</pre> <small>['post','page','category','tag','post:cpt','taxonomy:custom_tax', 'external'] (default: null)</small><br>
        </div>
      <input id="csv" type="file" @change="readFile" ref="input">
    </div>
</template>

<script>
export default {
  methods: {
    readFile () {
      this.reader = new FileReader()
      this.reader.onload = this.buildJSON
      this.reader.readAsBinaryString(this.$refs.input.files[0])
    },
    removeBomUtf8 ($s) {
      return $s.replace('\xEF\xBB\xBF', '')
    },
    buildJSON () {
      var arr = this.removeBomUtf8(this.reader.result).split('\n')
      var jsonObj = []
      var headers = arr[0].split(',')
      for (var i = 1; i < arr.length; i++) {
        var data = arr[i].split(',')
        var obj = {}
        for (var j = 0; j < data.length; j++) {
          if (headers[j]) obj[headers[j].trim().replace(/['"]+/g, '')] = data[j].trim().replace(/['"]+/g, '')
        }
        jsonObj.push(obj)
      }
      this.$emit('input', jsonObj)
    }
  }
}
</script>

<style scoped>
.input-csv pre {
  padding:0;
  display:inline;
}
.input-csv__info {
  margin-bottom: 20px;
}
</style>
