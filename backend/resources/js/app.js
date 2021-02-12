// Web API における CSRF 対策
import './bootstrap'
import Vue from 'vue'
// ルーティングの定義をインポート
import router from './router'
// 
import store from './store'
// ルートコンポーネントをインポートする
import App from './App.vue'

// ログインチェックしてからアプリを生成する
const createApp = async () => {
  await store.dispatch('auth/currentUser')

  new Vue({
    el: '#app',
    router, // ルーティングの定義を読み込む
    store,
    components: { App }, // ルートコンポーネントの使用を宣言する
    template: '<App />' // ルートコンポーネントを描画する
  })
}

createApp()
