<template>
    <div class="json-importer">
      <Logs v-show="show_logs" ref="logs" />
      <div class="json-importer__actions" v-if="is_ready" >
         <div class="button action json-importer__button" v-if="!is_importing" @click="startImport">
           <span v-if="show_logs">Restart import</span>
           <span v-else>Import</span>
         </div>
         <div class="button action json-importer__button" v-if="!is_importing && show_logs && import_on_course" @click="continueImport">
            <span>Continue import</span>
          </div>
         <div class="button action json-importer__button json-importer__stop" v-if="is_importing" @click="stop">Stop</div>
      </div>
    </div>
</template>

<script>
import axios from 'axios'
import Qs from 'qs'
import Logs from './Logs.vue'

export default {
  props: ['json'],
  components: { Logs },
  data () {
    return {
      is_importing: false,
      import_on_course: false,
      show_logs: false,
      index: 0,
      hash: null,
      is_ready: false
    }
  },
  watch: {
    json () {
      this.importReady()
    }
  },
  computed: {
    logs () {
      return this.$refs.logs
    }
  },
  methods: {
    stop () {
      this.is_importing = false
    },
    importReady () {
      this.is_importing = false
      this.show_logs = false
      this.is_ready = true
    },
    startImport () {
      this.index = 0
      this.logs.reset()
      this.import_on_course = true
      this.hash = Date.now()
      this.is_importing = this.hash
      this.show_logs = true
      this.logs.success('Starting import...')
      this.importRow(this.hash)
    },
    continueImport () {
      this.hash = Date.now()
      this.is_importing = this.hash
      this.importRow(this.hash)
    },
    endImport () {
      this.is_importing = false
      this.import_on_course = false
      this.logs.success('Import finished')
    },
    importRow (hash) {
      if (!this.is_importing || this.is_importing !== hash) return
      var data = { action: 'wg_redirections_new_redirection', is_active: 1, _wpnonce: window.wg_redirections._wpnonce, ...this.json[this.index] }
      var logLabel = JSON.stringify(this.json[this.index])

      axios.post(window.ajaxurl, Qs.stringify(data)).then(response => {
        if (typeof response.data === 'string') {
          this.logs.error(`Error! Cannot import ${logLabel}. ${response.data}`)
        } else if (response.data.error) {
          this.logs.error(`Error! Cannot import ${logLabel}. ${response.data.error}`)
        } else {
          this.logs.new('Imported ' + logLabel)
        }

        this.index++

        if (!this.is_importing) {
          this.logs.warning('Import has been stopped by the user')
          return
        }

        if (this.index >= this.json.length) {
          this.endImport()
        } else {
          this.importRow(hash)
        }
      }).catch(error => {
        this.logs.error(`Error when importing ${logLabel}. ${error.message}`)
      })
    }
  }
}
</script>

<style scoped>
.json-importer__actions {
  margin-top: 20px;
}

.json-importer__button {
  margin-right: 15px;
}
</style>
