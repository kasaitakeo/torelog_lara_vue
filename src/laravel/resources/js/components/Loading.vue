<template>
  <div v-show="loading">
    <div class="fullview">
      <div class="loading-spacer"></div>
      <vue-loading 
        type="spiningDubbles"
        color="#aaa"
        :size="{ width: '100px', height: '100px' }"
        >
      </vue-loading>
    </div>
  </div>
</template>

<script>
import { VueLoading } from 'vue-loading-template'

export default {
  name: 'loading',
  props: {
    loading: ''
  },
  components: {
    VueLoading,
  },

}
</script>

<style>
.fullview {
  width: 100%;
  height: 100%;
  background: #fefefe;
  position: fixed;
  top: 100;
  left: 0;
}
.loading-spacer {
  height: 30%;
}
</style>