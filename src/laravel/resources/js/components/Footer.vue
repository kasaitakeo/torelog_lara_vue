<template>
  <v-footer app>
  <v-bottom-navigation
    v-model="value"
    :background-color="color"
    
    dark
    shift
  >
    <RouterLink class="button button--link" :to="{name: 'log.create'}">
      <v-btn>
        <span>トレログ</span>
        <v-icon>fitness_center</v-icon>
      </v-btn>
    </RouterLink>
    <RouterLink class="button button--link" :to="{name: '/'}">
      <v-btn>
        <span>ホーム</span>
        <v-icon>house</v-icon>
      </v-btn>
    </RouterLink>
    <RouterLink class="button button--link" :to="{name: 'user.show', params: {userId: userId}}">
      <v-btn>
        <span>マイページ</span>
        <v-icon>account_circle</v-icon>
      </v-btn>
    </RouterLink>   
  </v-bottom-navigation>
    <span class="white--text">&copy; {{ new Date().getFullYear() }}</span>
    <v-spacer></v-spacer>
    <span v-if="isLogin" class="button button--link" @click="logout">
      Logout
    </span>
    <RouterLink v-else class="button button--link" to="/login">
      Login / Register
    </RouterLink>
  </v-footer>
</template> 

<script>
import { mapState, mapGetters } from 'vuex'

export default {
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus
    }),
    ...mapGetters({
      isLogin: 'auth/check'
    })
  },
  methods: {
    async logout () {
      await this.$store.dispatch('auth/logout')

      if (this.apiStatus) {
        this.$router.push('/login')
      }
    }
  }
}
</script>