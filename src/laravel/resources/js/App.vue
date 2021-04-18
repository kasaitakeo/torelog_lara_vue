<template>
  <div>
    <v-app id="inspire">
    <Header/>
      <!-- <Navbar /> -->
      <v-main class="pa-2 mt-15">
        <v-container fluid>
          <Message />
          <RouterView />
        </v-container>
      </v-main>
    <!-- <Footer /> -->
    <BottomNav/>

    </v-app>
  </div>
</template>

<script>
import Navbar from './components/Navbar.vue'
import Header from './components/Header.vue'
import Message from './components/Message.vue'
import Footer from './components/Footer.vue'
import BottomNav from './components/BottomNav.vue'

import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR } from './util'

export default {
  components: {
    Navbar,
    Header,
    Message,
    Footer,
    BottomNav
  },
  computed: {
    errorCode () {
      return this.$store.state.error.code
    }
  },
  watch: {
    // $store.state.error.codeを算出プロパティで参照しwatchで監視
    errorCode: {
      async handler (val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500')
        } else if (val === UNAUTHORIZED) {
          // トークンをリフレッシュ
          await axios.get('/api/refresh-token')
          // ストアのuserをクリア
          this.$store.commit('auth/setUser', null)
          // ログイン画面へ
          this.$router.push('/login')
        } else if (val === NOT_FOUND) {
          this.$router.push('/not-found')
        }
      },
      // 変更があった場合のみでなく、ページが読み込まれた時に処理を行いたい場合は、immediate: trueを設置
      immediate: true
    },
    $route () {
      this.$store.commit('error/setCode', null)
    }
  }
}
</script>