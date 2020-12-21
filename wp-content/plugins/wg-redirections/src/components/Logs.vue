<template>
  <div class="wg-redirections__logs" v-html="logs" ref="logs" />
</template>

<script>
export default {
  data () {
    return {
      logs: ''
    }
  },
  watch: {
    json () {
      this.importReady()
    }
  },
  methods: {
    reset () {
      this.logs = ''
    },
    success (log) {
      this.new(log, 'success')
    },
    error (log) {
      this.new(log, 'error')
    },
    warning (log) {
      this.new(log, 'warning')
    },
    new (log, color = 'black') {
      switch (color) {
        case 'error':
          color = 'red'
          break
        case 'warning':
          color = 'orange'
          break
        case 'success':
          color = 'green'
          break
      }
      this.logs += `<span style="color:${color};">${log}</span><br>`
      this.$nextTick(() => {
        this.$refs.logs.scrollTop = this.$refs.logs.scrollHeight
      })
    }
  }
}
</script>

<style scoped>
.wg-redirections__logs {
  width: 100%;
  max-width: 700px;
  height: 400px;
  font-family: monospace;
  padding: 15px;
  background: white;
  border: 1px solid lightgray;
  border-radius: 5px;
  box-sizing: border-box;
  margin-top: 20px;
  overflow: auto;
}
</style>
