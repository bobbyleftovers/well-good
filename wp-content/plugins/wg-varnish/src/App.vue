<template>
  <div id="poststuff">
      <CustomPurge />
    </div>
</template>

<script>

import CustomPurge from '@/views/CustomPurge'

export default {
  components: { CustomPurge }
}
</script>

<style scoped>
#poststuff {
  font-family: inherit;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: left;
  color: inherit;
}
</style>
