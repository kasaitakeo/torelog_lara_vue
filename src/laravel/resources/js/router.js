import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import Login from './pages/Login.vue'
import LogList from './pages/LogList.vue'
import LogCreate from './pages/LogCreate.vue'
import LogShow from './pages/LogShow.vue'
import LogEdit from './pages/LogEdit.vue'
import EventCreate from './pages/EventCreate.vue'
import EventUpdate from './pages/EventUpdate.vue'
import UserList from './pages/UserList.vue'
import UserShow from './pages/UserShow.vue'
import UserEdit from './pages/UserEdit.vue'
import CommentCreate from './pages/CommentCreate.vue'
import SystemError from './pages/errors/System.vue'
import NotFound from './pages/errors/NotFound.vue'

// ナビゲーションガード追加のため
import store from './store'

// VueRouterプラグインを使用->これにより<RouterView />を使用できる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
  {
    path: '/',
    name: '/',
    component: LogList,
    // propsで渡す値をrouteを引数にとった関数で表示
    props: route => {
      // routeからクエリパラメータpageを取り出し、正規表現を使って整数と解釈されない値は1と見なして返却
      const page = route.query.page
      return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
    }
  },
  {
    path: '/login',
    name: 'login',
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
    component: LogCreate,
    beforeEnter (to, form, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        alert('ログインしなければ利用できません')

        next('/')
      }
    }
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
    beforeEnter (to, form, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        alert('ログインしなければ利用できません')
        
        next('/')
      }
    }  
  },
  {
    path: '/events/create',
    name: 'event.create',
    component: EventCreate,
    beforeEnter (to, form, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        alert('ログインしなければ利用できません')
        
        next('/')
      }
    }
  },
  {
    path: '/events/:eventId/update',
    name: 'event.update',
    component: EventUpdate,
    props: true,
    beforeEnter (to, form, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        alert('ログインしなければ利用できません')
        
        next('/')
      }
    }
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
    // propsで渡す値をrouteを引数にとった関数で表示
    props: route => {
      // routeからクエリパラメータpageを取り出し、正規表現を使って整数と解釈されない値は1と見なして返却
      const page = route.query.page
      return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
    },
    beforeEnter (to, form, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        alert('ログインしなければ利用できません')
        
        next('/')
      }
    }
  },
  {
    path: '/users/:userId/edit',
    name: 'user.edit',
    component: UserEdit,
    beforeEnter (to, form, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        alert('ログインしなければ利用できません')
        
        next('/')
      }
    }
  },
  {
    path: '/comments/:logId/create',
    name: 'comment.create',
    component: CommentCreate,
    props: true,
    beforeEnter (to, form, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        alert('ログインしなければ利用できません')
        
        next('/')
      }
    }
  },
  {
    path: '/500',
    component: SystemError
  },
  {
    path: '*',
    component: NotFound
  }
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
  mode: 'history',
  scrollBehavior () {
    return { x: 0, y: 0 }
  },
  routes
})

// app.jsでインポートするためVueRouterインスタンスをエクスポートする
export default router
