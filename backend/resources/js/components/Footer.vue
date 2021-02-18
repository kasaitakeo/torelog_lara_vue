<template>
  <footer class="footer">
    <div v-if="isLogin">
      <button class="button button--link" @click="logout">
        Logout
      </button>
      <RouterLink class="button button--link" to="/LogForm">
        torelog
      </RouterLink>
    </div>
    <RouterLink v-else class="button button--link" to="/login">
      Login / Register
    </RouterLink>
  </footer>
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