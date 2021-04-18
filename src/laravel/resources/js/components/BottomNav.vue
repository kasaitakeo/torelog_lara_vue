<template>
  <div v-if="isLogin && active">
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
  </div>
</template>
<script>
  export default {
    data () { 
      return {
        value: 1,
        active: true,
      }
    },
    computed: {
      color () {
        switch (this.value) {
          case 0: return 'blue-grey'
          case 1: return 'teal'
          case 2: return 'indigo'
          default: return 'blue-grey'
        }
      },
      isLogin () {
        return this.$store.getters['auth/check']
      },
      userId () {
        return this.$store.getters['auth/userId']
      },
    },
    watch: {
      $route: {
        handler () {
          switch (this.$route.path) {
            case '/logs/create': 
              this.value = 0
              this.active = true
              break
            case '/':
              this.value = 1
              this.active = true
              break
            case `/users/${this.userId}`:
              this.value = 2
              this.active = true
              break
            default: 
              this.active = false
          }
        },
        immediate: true
      }
    }  
  }
</script>