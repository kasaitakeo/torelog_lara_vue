// Web API における CSRF 対策
import './bootstrap'
import Vue from 'vue'

import '@mdi/font/css/materialdesignicons.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
Vue.use(Vuetify);

// ルーティングの定義をインポート
import router from './router'
// 
import store from './store'
// ルートコンポーネントをインポートする
import App from './App.vue'

import InfiniteLoading from 'vue-infinite-loading';
//Vue-infinite-loadingを使用する
Vue.use(InfiniteLoading);

// ログインチェックしてからアプリを生成する
const createApp = async () => {
  await store.dispatch('auth/currentUser')

  new Vue({
    el: '#app',
    router, // ルーティングの定義を読み込む
    store,
    vuetify: new Vuetify({
      iconfont: 'mdi', //追記
    }),
    components: { App }, // ルートコンポーネントの使用を宣言する
    template: '<App />' // ルートコンポーネントを描画する
  })
}

createApp()
