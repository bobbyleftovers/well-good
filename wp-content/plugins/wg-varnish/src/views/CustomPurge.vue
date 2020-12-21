<template>
  <div class="custom-purge">
    <div class="notice" :class="notification.type" v-if="notification">
      <p>
        <span v-html="notification.text"></span>.
        <!--<span v-if="notification.type == 'notice-success' ">
          See full logs
          <a href="/wp-admin/admin.php?page=wg-varnish_logs">here</a>
        </span>-->
      </p>
      <span class="dashicons dashicons-dismiss" @click="notification = false"></span>
    </div>
    <div class="postbox wp-metabox">
      <h2 class="hndle">
        <span>Purge urls</span>
      </h2>
      <div class="inside custom-purge__body">
        <PurgeEndpoint
          @input="onInput"
          @remove="remove"
          v-for="endpoint in endpoints"
          :endpoints="endpoints"
          :endpoint="endpoint"
          :key="endpoint.id"
          :key2="endpoint.id"
          :root="root"
        />
        <div class="custom-purge__row custom-purge__row--add-new" @click="add">
          <span class="dashicons dashicons-plus-alt"></span> Add endpoint
        </div>
        <button
          class="button button-primary button-hero custom-purge__submit"
          @click="submit"
        >Purge now</button>
      </div>
      <Loading v-if="is_loading" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import PurgeEndpoint from "@/components/PurgeEndpoint.vue";
import select from "select-dom";

export default {
  components: {
    PurgeEndpoint
  },
  data() {
    return {
      is_loading: false,
      endpoints: [{ id: Date.now(), urls: [""] }],
      notification: false
    };
  },
  computed: {
    root() {
      return window.location.origin + "/";
    },
    reduced_endpoints() {
      var a = [];
      this.endpoints.forEach(row => {
        var urls = row.urls;
        if (typeof urls !== "object") urls = [""];
        urls.forEach(url => {
          url = ("/" + url + "/").replace("//", "/");
          if (!a.includes(url)) a.push(url);
        });
      });
      return a;
    }
  },
  methods: {
    onInput(val) {
      if (typeof val.urls !== "object") val.urls = [""];
      this.endpoints.forEach((row, i) => {
        if (row.id === val.key) this.endpoints[i].urls = val.urls;
      });
      this.endpoints = Object.assign([], this.endpoints);
    },
    add() {
      this.endpoints.push({ id: Date.now(), urls: [""] });
      this.$nextTick(() => {
        select.last(".custom-purge__input", this.$el).focus();
      });
    },
    submit() {
      this.is_loading = true;

      // eslint-disable-next-line no-console
      console.log("%c Purging Varnish ", "background: #222; color: #bada55");
      // eslint-disable-next-line no-console
      console.log(this.reduced_endpoints);

      fetch(window.WG_Varnish_REST_Purge.purge_urls, {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ urls: this.reduced_endpoints })
      })
        .then(response => {
          return response.json();
        })
        .then(data => {
          let servers = "";
          let serversLength = 0;
          Object.keys(data).forEach(server => {
            // if (servers !== '') servers += ','
            servers += `<pre>${server}</pre>`;
            serversLength++;
          });
          this.notification = Object.assign(
            {},
            {
              text: `Purged <strong>${this.reduced_endpoints.length}</strong> urls in ${serversLength} servers: ${servers}`,
              type: "notice-success"
            }
          );
        })
        .catch(err => {
          this.notification = Object.assign(
            {},
            {
              text: "Awghh! Something went wrong...",
              type: "error"
            }
          );
          /* eslint no-console: ["error", { allow: ["warn", "error"] }] */
          console.error(err);
        })
        .then(() => {
          this.is_loading = false;
        });
    },
    remove(key) {
      this.endpoints.forEach((row, i) => {
        if (row.id === key) this.endpoints.splice(i, 1);
      });
      this.endpoints = Object.assign([], this.endpoints);
    }
  }
};
</script>

<style scoped lang="scss">
.wp-metabox {
  position: relative;
}

.custom-purge {
  max-width: 800px;
}

.custom-purge__body {
  padding: 10px 15px 20px;
}

.custom-purge__row--add-new {
  text-align: center;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  border-color: transparent;
  .dashicons {
    font-size: 1.17em;
    padding-top: 0.18em;
    padding-right: 0.2em;
    display: inline-block;
    opacity: 0.7;
  }
  &:hover {
    color: #0073aa;
    .dashicons {
      opacity: 1;
    }
  }
}

.custom-purge .custom-purge__submit {
  width: 100%;
  margin-top: 20px;
  margin-left: auto;
  margin-right: auto;
  display: block;
}

.notice {
  position: relative;
}
.notice .dashicons-dismiss {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  opacity: 0.6;
  transition: 0.3s;
  &:hover {
    opacity: 1;
  }
}
</style>

<style>
.custom-purge__row {
  display: flex;
  border-color: rgb(190, 190, 190);
  border-style: solid;
  border-radius: 4px;
  border-width: 1px;
  overflow: hidden;
  margin-bottom: 15px;
  line-height: 44px;
  font-size: 14px;
}

.custom-purge .notice pre {
  display: inline-block;
  background: rgb(230, 230, 230);
  padding: 1px 4px;
  border-radius: 3px;
  margin-top: 0;
  margin-bottom: 0;
  margin-left: 3px;
  margin-right: 3px;
}
</style>
