import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import Login from './pages/Login.vue'
import SystemError from './pages/errors/System.vue'
import LogList from './pages/LogList.vue'
import LogCreate from './components/LogCreate.vue'
import LogShow from './components/LogShow.vue'
import LogEdit from './components/LogEdit.vue'
import UserList from './pages/UserList.vue'
import UserShow from './components/UserShow.vue'
import UserEdit from './components/UserEdit.vue'

// ナビゲーションガード追加のため
import store from './store'

// VueRouterプラグインを使用->これにより<RouterView />を使用できる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
  {
    path: '/',
    name: '/',
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
    path: '/logs/create',
    name: 'log.create',
    component: LogCreate
  },
  {
    path: '/logs/:logId',
    name: 'log.show',
    component: LogShow,
    props: true
  },
  {
    path: '/logs/:logId/edit',
    name: 'log.edit',
    component: LogEdit,
    props: true
  },
  {
    path: '/users',
    name: 'user',
    component: UserList,
  },
  {
    path: '/users/:userId',
    name: 'user.show',
    component: UserShow,
    props: true
  },
  {
    path: '/users/:userId/edit',
    name: 'user.edit',
    component: UserEdit,
    props: true
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
