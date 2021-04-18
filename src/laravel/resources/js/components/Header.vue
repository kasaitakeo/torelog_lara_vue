<template>
  <div>
    <v-navigation-drawer
      v-model="drawer"
      app
    >
      <v-list dense>
        <RouterLink class="button button--link" :to="{name: 'user'}">
          <v-list-item link>
            <v-list-item-action>
              <v-icon>account_circle</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>ユーザーリスト</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </RouterLink>
        <RouterLink class="button button--link" :to="{name: 'log.create'}">
          <v-list-item link>
            <v-list-item-action>
              <v-icon>fitness_center</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>トレログ</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </RouterLink>
        <RouterLink class="button button--link" :to="{name: 'event.create'}">
          <v-list-item link>
            <v-list-item-action>
              <v-icon>add_circle</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>種目追加</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </RouterLink>
      </v-list>
    </v-navigation-drawer>
    <v-app-bar
      app
      color="indigo"
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>
        <RouterLink class="button button--link" to="/">
          トレログ
        </RouterLink>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <div v-if="isLogin">  
        <span class="button button--link" @click="logout">
          Logout
        </span>

      </div>
      <div v-else>
        <RouterLink class="button button--link" to="/login">
          Login / Register
        </RouterLink>
      </div>
    </v-app-bar>
  </div>
</template>
<script>
export default {
  data () {
    return {
      drawer: null,
    }
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    userName () {
      return this.$store.getters['auth/userName']
    },
    userId () {
      return this.$store.getters['auth/userId']
    }
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