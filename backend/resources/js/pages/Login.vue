<template>
  <div class="container--small">
    <ul class="tab">
      <li
        class="tab__item"
        :class="{'tab__item--active': tab === 1}"
        @click="tab = 1"
      >ログイン</li>
      <li
        class="tab__item"
        :class="{'tab__item--active': tab === 2}"
        @click="tab = 2"
      >新規登録</li>
    </ul>
    <div class="panel" v-show="tab === 1">
      <form class="form" @submit.prevent="login">
        <div v-if="loginErrors" class="errors">
          <ul v-if="loginErrors.email">
            <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
          </ul>
          <ul v-if="loginErrors.password">
            <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <label for="login-email">メールアドレス</label>
        <input type="text" class="form__item" id="login-email" v-model="loginForm.email">
        <label for="login-password">パスワード</label>
        <input type="password" class="form__item" id="login-password" v-model="loginForm.password">
        <div class="form__button">
          <button type="submit" class="button button--inverse">ログイン</button>
        </div>
      </form>
    </div>
    <div class="panel" v-show="tab === 2">
      <form class="form" @submit.prevent="register">
        <div v-if="registerErrors" class="errors">
          <ul v-if="registerErrors.name">
            <li v-for="msg in registerErrors.name" :key="msg">{{ msg }}</li>
          </ul>
          <ul v-if="registerErrors.email">
            <li v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li>
          </ul>
          <ul v-if="registerErrors.password">
            <li v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <label for="username">氏名</label>
        <input type="text" class="form__item" id="username" v-model="registerForm.name">
        <label for="screenname">アカウント名</label>
        <input type="text" class="form__item" id="screenname" v-model="registerForm.screen_name">
        <label for="email">メールアドレス</label>
        <input type="text" class="form__item" id="email" v-model="registerForm.email">
        <label for="password">パスワード</label>
        <input type="password" class="form__item" id="password" v-model="registerForm.password">
        <label for="password-confirmation">パスワード (確認)</label>
        <input type="password" class="form__item" id="password-confirmation" v-model="registerForm.password_confirmation">
        <div class="form__button">
          <button type="submit" class="button button--inverse">新規登録</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  data () {
    return {
      tab: 1,
      loginForm: {
        email: '',
        password: ''
      },
      registerForm: {
        name: '',
        screen_name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
    }
  },
  computed: {
    // mapStateを使用することで下記のように書くことができる
    ...mapState({
      apiStatus: state => state.auth.apiStatus,
      loginErrors: state => state.auth.loginErrorMessages,
      registerErrors: state => state.auth.registerErrorMessages
    })
    // apiStatus () {
    //   return this.$store.state.auth.apiStatus
    // },
    // loginErrors () {
    //   return this.$store.state.auth.loginErrorMessages
    // }
  },
  methods: {
    async register () {
      // stores/index.js にて Vue.use(Vuex) という記述で Vuex プラグインの使用を宣言したので、this.$store からストアを参照することができる。
      // アクション呼び出すにはdispatchメソッド使用
    
      await this.$store.dispatch('auth/register', this.registerForm)

      // トップページに移動する
      // awaitで非同期なアクションの処理が完了するのを待ってから（Promiseの解決を待ってから）、トップページに遷移するためにthis.$routerのpushメソッドを読んでいます
      // VueRouterの設定時にVue.use(VueRouter)でVueRouterプラグインの使用を宣言したため thisにルーターオブジェクトを表す$routerが追加
      if (this.apiStatus) {
        // トップページへ移動
        this.$router.push('/')
      }
    },
    async login () {
      await this.$store.dispatch('auth/login', this.loginForm)

      if (this.apiStatus) {
        // トップページへ移動
        this.$router.push('/')
      }
    },
    clearError () {
      this.$store.commit('auth/setLoginErrorMessages', null)
      this.$store.commit('auth/setRegisterErrorMessages', null)
    }
  },
  created () {
    this.clearError()
  }
}
</script>