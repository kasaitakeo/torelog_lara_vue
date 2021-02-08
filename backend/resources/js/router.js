import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import LogList from './pages/LogList.vue'
import Login from './pages/Login.vue'

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
    component: Login
  },
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
  mode: 'history',
  routes
})

// app.jsでインポートするためVueRouterインスタンスをエクスポートする
export default router
