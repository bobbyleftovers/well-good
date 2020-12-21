<template>
  <div id="poststuff">
    <div class="col">
      <div class="wg-redirections__action" v-for="(action, key) in actions" :key="key">
        <div>{{action.name}}</div>
        <div>
          <button class="button gray" v-if="action.needs_reset && !action.ongoing && action.has_started" @click="resetAction(key)">Reset</button>
          <button class="button" v-if="!action.ongoing" @click="startAction(key)">Start</button>
          <button class="button red" v-else @click="stopAction(key)">Stop</button>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="logs" ref="logs" v-html="computedLogs"></div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Qs from 'qs'

export default {
  data () {
    return {
      logs: '',
      actions: [
        {
          name: 'Flatten target URLs',
          ongoing: false,
          has_started: false,
          needs_reset: false,
          offset: 0,
          action: 'flatten_target_urls'
        }
      ]
    }
  },
  computed: {
    computedLogs () {
      if (this.logs === '') return '<span style="color:lightgray">No logs yet</span>'
      return this.logs
    }
  },
  methods: {
    resetAction (key, logs = true, stop = true) {
      if (stop) this.stopAction(key, false)
      if (logs) this.log('Resetting action: ' + this.actions[key].name, 'orange')
      this.actions[key].has_started = false
      this.actions[key].offset = 0
    },
    startAction (key, val = true, logs = true) {
      this.actions[key].ongoing = val
      if (val) {
        if (logs) this.log('Starting action: ' + this.actions[key].name, 'orange')
        this.actions[key].has_started = true
        this.doAction(key)
      } else if (logs) this.log('Stopping action: ' + this.actions[key].name, 'red')
    },
    stopAction (key, logs = true) {
      if (!this.actions[key].needs_reset) this.resetAction(key, false, false)
      this.startAction(key, false, logs)
    },
    doAction (key) {
      if (!this.actions[key].ongoing) return
      var action = this.actions[key]
      var data = {
        action: 'wg_redirections_maintanance_' + action.action,
        _wpnonce: window.wg_redirections._wpnonce,
        data: action
      }

      axios.post(window.ajaxurl, Qs.stringify(data)).then(response => {
        const data = response.data.data
        if (typeof response.data.error !== 'undefined') return this.responseError(key, response.data.error)
        if (typeof data.log !== 'undefined') this.log(data.log)
        if (typeof data.logs !== 'undefined') data.logs.forEach(log => this.log(log))
        if (typeof data.next !== 'undefined' && data.next) {
          if (this.actions[key].has_started) this.actions[key].offset++
          if (this.actions[key].ongoing) this.doAction(key)
        } else {
          this.resetAction(key, false)
          this.log('Successfully finished maintanance action: ' + this.actions[key].name, 'lime')
        }
      }).catch(error => {
        this.responseError(key, error)
      })
    },
    responseError (key, error) {
      this.stopAction(key, false)
      this.log(`${error}`, 'red')
    },
    log (log, color = 'lightgray') {
      var d = new Date()
      this.logs += `<small>${d.getHours()}:${d.getMinutes()}:${d.getSeconds()}</small> <span style="color:${color};">${log}</span><br>`
      this.$nextTick(() => {
        this.$refs.logs.scrollTop = this.$refs.logs.scrollHeight
      })
    }
  }
}
</script>

<style scoped>
#poststuff {
  display:grid;
  grid-template-columns: 1fr 1fr;
  max-width: 1200px;
  column-gap: 30px;
  position:relative;
}
.col {
  max-width: 500px;
}
.wg-redirections__action {
  display:flex;
  min-height: 40px;
  border-bottom: 1px solid lightgray;
  align-items: center;
  justify-content: space-between;
  padding: 10px 0;
  max-width: 400px;
}
.wg-redirections__action:last-child {
  border-bottom:none
}
.button {
  min-width: 90px;
  margin-left: 6px;
}
.button.red {
  border-color:red;
  color: red;
}
.button.gray {
  border-color:gray;
  color: gray;
}
.logs {
  width: 100%;
  max-width: 700px;
  height: 400px;
  font-family: monospace;
  padding: 15px;
  background: rgb(37, 37, 37);
  border: 1px solid black;
  border-radius: 5px;
  box-sizing: border-box;
  overflow: auto;
}
</style>
