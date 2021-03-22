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

import { INTERNAL_SERVER_ERROR } from './util'

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
      handler (val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500')
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