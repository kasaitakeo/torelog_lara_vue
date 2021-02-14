import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import LogList from './pages/LogList.vue'
import Login from './pages/Login.vue'
import SystemError from './pages/errors/System.vue'
import LogForm from './pages/LogForm.vue'

// ナビゲーションガード追加のため
import store from './store'

// VueRouterプラグインを使用->これにより<RouterView />を使用できる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
  {
    path: '/',
    component: LogList
  },
  {
    path: '/login',
    component: Login,
    // beforeEnter は定義されたルートにアクセスされてページコンポーネントが切り替わる直前に呼び出される関数
    // 第一引数 to はアクセスされようとしているルートのルートオブジェクト、第二引数 from はアクセス元のルート、そして第三引数 next はページの移動先（切り替わり先）を決めるための関数
    beforeEnter (to, form, next) {
      if (store.getters['auth/check']) {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/LogForm',
    component: LogForm
  },
  {
    path: '/500',
    component: SystemError
  }
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
  mode: 'history',
  routes
})

// app.jsでインポートするためVueRouterインスタンスをエクスポートする
export default router
