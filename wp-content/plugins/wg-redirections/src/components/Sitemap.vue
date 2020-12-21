<template>
  <div id="poststuff">
    <div class="sitemap">
      <Logs v-show="show_logs" ref="logs" />
      <div class="sitemap__actions">
         <div class="button action sitemap__button" @click="build">Build</div>
      </div>
    </div>
  </div>
</template>

<script>
import Logs from './Logs.vue'

export default {
  components: { Logs },
  data () {
    return {
      show_logs: false
    }
  },
  methods: {
    build () {
      this.show_logs = true
    }
  }
}
</script>

<style scoped>
.sitemap__actions {
  margin-top: 20px;
}

.sitemap__button {
  margin-right: 15px;
}
</style>
