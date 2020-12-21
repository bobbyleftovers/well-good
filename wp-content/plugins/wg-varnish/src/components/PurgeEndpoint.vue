<template>
  <div>
    <div class="custom-purge__endpoint">
      <div class="custom-purge__row">
        <span class="custom-purge__input-prepend">{{root}}</span>
        <input v-model="main" class="custom-purge__input" />
      </div>
      <div
        class="custom-purge__remove button button-secondary"
        v-if="endpoints.length > 1"
        @click="remove()"
        :key="key2"
      ></div>
      <div class="custom-purge__input-append">
        <multiselect
          v-model="additional"
          select-label="Add"
          deselect-label="Remove"
          :show-labels="true"
          tag-placeholder="Add this as new tag"
          placeholder="Additional endpoints"
          label="name"
          track-by="code"
          :options="options"
          :multiple="true"
          :taggable="true"
          :searchable="false"
        />
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
export default {
  components: {
    Multiselect
  },
  props: ['root', 'endpoint', 'endpoints', 'key2'],
  data () {
    return {
      main: '',
      additional: [],
      options: [
        { name: 'amp', code: '/amp' },
        { name: 'feed', code: '/feed' }
      ]
    }
  },
  watch: {
    additional () {
      this.emit()
    },
    main () {
      this.emit()
    }
  },
  methods: {
    emit () {
      var val = [this.main]
      this.additional.forEach(additional => {
        val.push(this.main + additional.code)
      })
      this.$emit('input', { key: this.key2, urls: val })
    },
    remove () {
      this.$emit('remove', this.key2)
    }
  }
}
</script>

<style>
fieldset[disabled] .multiselect {
  pointer-events: none;
}
.multiselect__spinner {
  position: absolute;
  right: 1px;
  top: 1px;
  width: 48px;
  height: 35px;
  background: #fff;
  display: block;
}
.multiselect__spinner:after,
.multiselect__spinner:before {
  position: absolute;
  content: "";
  top: 50%;
  left: 50%;
  margin: -8px 0 0 -8px;
  width: 16px;
  height: 16px;
  border-radius: 100%;
  border: 2px solid transparent;
  border-top-color: #41b883;
  box-shadow: 0 0 0 1px transparent;
}
.multiselect__spinner:before {
  animation: spinning 2.4s cubic-bezier(0.41, 0.26, 0.2, 0.62);
  animation-iteration-count: infinite;
}
.multiselect__spinner:after {
  animation: spinning 2.4s cubic-bezier(0.51, 0.09, 0.21, 0.8);
  animation-iteration-count: infinite;
}
.multiselect__loading-enter-active,
.multiselect__loading-leave-active {
  transition: opacity 0.4s ease-in-out;
  opacity: 1;
}
.multiselect__loading-enter,
.multiselect__loading-leave-active {
  opacity: 0;
}
.multiselect,
.multiselect__input,
.multiselect__single {
  font-family: inherit;
  font-size: 16px;
  -ms-touch-action: manipulation;
  touch-action: manipulation;
}
.multiselect {
  box-sizing: content-box;
  display: block;
  position: relative;
  width: 100%;
  min-height: 40px;
  text-align: left;
  color: #35495e;
}
.multiselect * {
  box-sizing: border-box;
}
.multiselect:focus {
  outline: none;
}
.multiselect--disabled {
  background: #ededed;
  pointer-events: none;
  opacity: 0.6;
}
.multiselect--active {
  z-index: 50;
}
.multiselect--active:not(.multiselect--above) .multiselect__current,
.multiselect--active:not(.multiselect--above) .multiselect__input,
.multiselect--active:not(.multiselect--above) .multiselect__tags {
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}
.multiselect--active .multiselect__select {
  transform: rotate(180deg);
}
.multiselect--above.multiselect--active .multiselect__current,
.multiselect--above.multiselect--active .multiselect__input,
.multiselect--above.multiselect--active .multiselect__tags {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
.multiselect__input,
.multiselect__single {
  position: relative;
  display: inline-block;
  min-height: 20px;
  line-height: 20px;
  border: none;
  border-radius: 5px;
  background: #fff;
  padding: 0 0 0 5px;
  width: 100%;
  transition: border 0.1s ease;
  box-sizing: border-box;
  margin-bottom: 8px;
  vertical-align: top;
}
.multiselect__input:-ms-input-placeholder {
  color: #35495e;
}
.multiselect__input::placeholder {
  color: #35495e;
}
.multiselect__tag ~ .multiselect__input,
.multiselect__tag ~ .multiselect__single {
  width: auto;
}
.multiselect__input:hover,
.multiselect__single:hover {
  border-color: #cfcfcf;
}
.multiselect__input:focus,
.multiselect__single:focus {
  border-color: #a8a8a8;
  outline: none;
}
.multiselect__single {
  padding-left: 5px;
  margin-bottom: 8px;
}
.multiselect__tags-wrap {
  display: inline;
}
.multiselect__tags {
  min-height: 40px;
  display: block;
  padding: 8px 40px 0 8px;
  border-radius: 5px;
  border: 1px solid #e8e8e8;
  background: #fff;
  font-size: 14px;
}
.multiselect__tag {
  position: relative;
  display: inline-block;
  padding: 4px 26px 4px 10px;
  border-radius: 5px;
  margin-right: 10px;
  color: #fff;
  line-height: 1;
  background: #41b883;
  margin-bottom: 5px;
  white-space: nowrap;
  overflow: hidden;
  max-width: 100%;
  text-overflow: ellipsis;
}
.multiselect__tag-icon {
  cursor: pointer;
  margin-left: 7px;
  position: absolute;
  right: 0;
  top: 0;
  bottom: 0;
  font-weight: 700;
  font-style: normal;
  width: 22px;
  text-align: center;
  line-height: 22px;
  transition: all 0.2s ease;
  border-radius: 5px;
}
.multiselect__tag-icon:after {
  content: "\D7";
  color: #266d4d;
  font-size: 14px;
}
.multiselect__tag-icon:focus,
.multiselect__tag-icon:hover {
  background: #369a6e;
}
.multiselect__tag-icon:focus:after,
.multiselect__tag-icon:hover:after {
  color: #fff;
}
.multiselect__current {
  min-height: 40px;
  overflow: hidden;
  padding: 8px 30px 0 12px;
  white-space: nowrap;
  border-radius: 5px;
  border: 1px solid #e8e8e8;
}
.multiselect__current,
.multiselect__select {
  line-height: 16px;
  box-sizing: border-box;
  display: block;
  margin: 0;
  text-decoration: none;
  cursor: pointer;
}
.multiselect__select {
  position: absolute;
  width: 40px;
  height: 38px;
  right: 1px;
  top: 1px;
  padding: 4px 8px;
  text-align: center;
  transition: transform 0.2s ease;
}
.multiselect__select:before {
  position: relative;
  right: 0;
  top: 65%;
  color: #999;
  margin-top: 4px;
  border-color: #999 transparent transparent;
  border-style: solid;
  border-width: 5px 5px 0;
  content: "";
}
.multiselect__placeholder {
  color: #adadad;
  display: inline-block;
  margin-bottom: 10px;
  padding-top: 2px;
}
.multiselect--active .multiselect__placeholder {
  display: none;
}
.multiselect__content-wrapper {
  position: absolute;
  display: block;
  background: #fff;
  width: 100%;
  max-height: 240px;
  overflow: auto;
  border: 1px solid #e8e8e8;
  border-top: none;
  border-bottom-left-radius: 5px;
  border-bottom-right-radius: 5px;
  z-index: 50;
  -webkit-overflow-scrolling: touch;
}
.multiselect__content {
  list-style: none;
  display: inline-block;
  padding: 0;
  margin: 0;
  min-width: 100%;
  vertical-align: top;
}
.multiselect--above .multiselect__content-wrapper {
  bottom: 100%;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
  border-bottom: none;
  border-top: 1px solid #e8e8e8;
}
.multiselect__content::webkit-scrollbar {
  display: none;
}
.multiselect__element {
  display: block;
}
.multiselect__option {
  display: block;
  padding: 12px;
  min-height: 40px;
  line-height: 16px;
  text-decoration: none;
  text-transform: none;
  vertical-align: middle;
  position: relative;
  cursor: pointer;
  white-space: nowrap;
}
.multiselect__option:after {
  top: 0;
  right: 0;
  position: absolute;
  line-height: 40px;
  padding-right: 12px;
  padding-left: 20px;
  font-size: 13px;
}
.multiselect__option--highlight {
  background: #41b883;
  outline: none;
  color: #fff;
}
.multiselect__option--highlight:after {
  content: attr(data-select);
  background: #41b883;
  color: #fff;
}
.multiselect__option--selected {
  background: #f3f3f3;
  color: #35495e;
  font-weight: 700;
}
.multiselect__option--selected:after {
  content: attr(data-selected);
  color: silver;
}
.multiselect__option--selected.multiselect__option--highlight {
  background: #ff6a6a;
  color: #fff;
}
.multiselect__option--selected.multiselect__option--highlight:after {
  background: #ff6a6a;
  content: attr(data-deselect);
  color: #fff;
}
.multiselect--disabled .multiselect__current,
.multiselect--disabled .multiselect__select {
  background: #ededed;
  color: #a6a6a6;
}
.multiselect__option--disabled {
  background: #ededed !important;
  color: #a6a6a6 !important;
  cursor: text;
  pointer-events: none;
}
.multiselect__option--group {
  background: #ededed;
  color: #35495e;
}
.multiselect__option--group.multiselect__option--highlight {
  background: #35495e;
  color: #fff;
}
.multiselect__option--group.multiselect__option--highlight:after {
  background: #35495e;
}
.multiselect__option--disabled.multiselect__option--highlight {
  background: #dedede;
}
.multiselect__option--group-selected.multiselect__option--highlight {
  background: #ff6a6a;
  color: #fff;
}
.multiselect__option--group-selected.multiselect__option--highlight:after {
  background: #ff6a6a;
  content: attr(data-deselect);
  color: #fff;
}
.multiselect-enter-active,
.multiselect-leave-active {
  transition: all 0.15s ease;
}
.multiselect-enter,
.multiselect-leave-active {
  opacity: 0;
}
.multiselect__strong {
  margin-bottom: 8px;
  line-height: 20px;
  display: inline-block;
  vertical-align: top;
}
[dir="rtl"] .multiselect {
  text-align: right;
}
[dir="rtl"] .multiselect__select {
  right: auto;
  left: 1px;
}
[dir="rtl"] .multiselect__tags {
  padding: 8px 8px 0 40px;
}
[dir="rtl"] .multiselect__content {
  text-align: right;
}
[dir="rtl"] .multiselect__option:after {
  right: auto;
  left: 0;
}
[dir="rtl"] .multiselect__clear {
  right: auto;
  left: 12px;
}
[dir="rtl"] .multiselect__spinner {
  right: auto;
  left: 1px;
}
@keyframes spinning {
  0% {
    transform: rotate(0);
  }
  to {
    transform: rotate(2turn);
  }
}
.multiselect__tags {
  border: none;
  background: none;
  padding-top: 12px;
  min-height: 45px;
}
.multiselect__content-wrapper {
  border-color: rgb(190, 190, 190);
  width: calc(100% + 2px);
  margin-left: -1px;
}
.multiselect__element {
  margin-bottom: 0;
}
.multiselect:not(.multiselect--active) {
  height: 45px;
  overflow-y: auto;
}
.multiselect.multiselect--active {
  background: rgb(240, 240, 240);
  border-right: 1px solid rgb(190, 190, 190);
  border-top-right-radius: 4px;
  max-width: 198px;
}
.multiselect__option:not(.multiselect__option--highlight) {
  background: rgb(240, 240, 240);
}
</style>

<style lang="scss" scoped>
.custom-purge__endpoint {
  position: relative;
  .custom-purge__row {
    padding-right: 190px;
    background: rgb(240, 240, 240);
  }
}
.custom-purge__input-prepend {
  line-height: 44px;
  font-size: 14px;
  border-width: 1px;
  padding-left: 7px;
  background: rgb(240, 240, 240);
  padding-right: 7px;
  padding-left: 15px;
  color: rgb(150, 150, 150);
  border-right: 1px solid rgb(190, 190, 190);
}
.custom-purge__input-append {
  width: 190px;
  position: absolute;
  top: 1px;
  right: 0px;
  border-left: 1px solid rgb(190, 190, 190);
}

.custom-purge__input {
  border: 1px solid;
  flex-grow: 1;
  padding: 0;
  margin: 0;
  line-height: 30px;
  border: none;
  padding-left: 6px;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

.custom-purge__remove {
  position: absolute;
  top: 50%;
  left: -10px;
  transform: translateY(-50%) !important;
  border-radius: 50%;
  padding: 0;
  width: 24px;
  height: 24px;
  line-height: 23px;
  font-size: 1.2em;
  &:before {
    content: "\f335";
    font-family: dashicons;
  }
  text-align: center;
  opacity: 0;
  transition: 0.2s;
  .custom-purge__endpoint:hover & {
    opacity: 1;
  }
  z-index: 99999999;
}
</style>
