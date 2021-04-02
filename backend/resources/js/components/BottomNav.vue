<template>
  <v-bottom-navigation
    app
    v-model="value"
    :background-color="color"
    dark
    shift
  >
    <RouterLink class="button button--link" :to="{name: 'log.create'}">
      <v-btn>
        <v-icon>fitness_center</v-icon>
        <span class="pt-5">トレログ</span>
      </v-btn>
    </RouterLink>
    <RouterLink class="button button--link" :to="{name: '/'}">
      <v-btn>
        <v-icon>house</v-icon>
        <span class="pt-5">ホーム</span>
      </v-btn>
    </RouterLink>
    <RouterLink class="button button--link" :to="{name: 'user.show', params: {userId: userId}}">
      <v-btn>
        <v-icon>account_circle</v-icon>
        <span class="pt-5">マイページ</span>
      </v-btn>
    </RouterLink>   
  </v-bottom-navigation>
</template>
<script>
  export default {
    data () { 
      return {
        value: 1
      }
    },
    computed: {
      color () {
        switch (this.value) {
          case 0: return 'blue-grey'
          case 1: return 'teal'
          case 2: return 'indigo'
          default: return 'yellow'
        }
      },
      userId () {
        return this.$store.getters['auth/userId']
      },
    },
    mounted () {
      switch (this.$route.name) {
        case 'log.create': 
          this.value = 0
          break
        case '/':
          this.value = 1
          break
        case 'user.show':
          this.value = 2
          break
        default: this.value = 3
      }
    },
  }
</script>