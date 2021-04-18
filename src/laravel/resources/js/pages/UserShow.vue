<template>
  <v-row>
    <v-col cols="12" class="ma-1">
      <v-card elevation="5">
        <!-- ユーザープロフィール -->
        <v-row class="ma-1">
          <v-col cols="4" class="justify-items-center">
            <v-avatar
              size="62"
              class="align-items-center"
            >
              <v-img
                color="grey"
                :src="`${user.profile_image}`"
              ></v-img>
            </v-avatar>
            <!-- <div class="text-secondary">
              <span v-if="user.screen_name !== null">{{ user.screen_name }}</span>
            </div> -->
            <div class="font-weight-bold ml-1 mt-1">
              {{ user.screen_name }}
            </div>
          </v-col>
          <v-col cols="8" class="d-flex flex-column align-end">
            <div class="d-flex flex-row">
              <v-card class="d-flex align-end flex-column pt-2" elevation="0" tile>
                <p class="font-weight-medium">ログ</p>
                <p>{{ logCount }}</p>
              </v-card>
              <v-card class="d-flex align-end flex-column pt-2" elevation="0" tile>
                <p class="font-weight-medium">フォロー</p>
                <p>{{ follow_count }}</p>
              </v-card>
              <v-card class="d-flex align-end flex-column pt-2" elevation="0" tile>
                <p class="font-weight-medium">フォロワー</p>
                <p>{{ follower_count }}</p>
              </v-card>
            </div>
            <div class="d-flex flex-row">
              <v-card-actions>
                <!-- ログインユーザーならプロフィール編集画面へのリンク -->
                <RouterLink v-if="user.id === loginUserId" class="button button--link" :to="{ name: 'user.edit', params: { userId: user.id }}">
                  <v-btn
                    outlined
                    rounded
                    text
                  >
                    プロフィール編集
                  </v-btn>
                </RouterLink>
                <!-- ログインユーザーでなければフォローボタン -->
                <div v-else>
                  <v-btn v-if="following" @click="unFollow(user.id)">フォロー解除</v-btn>
                  <v-btn v-else @click="follow(user.id)" color="success">フォローする</v-btn>
                  <div v-if="followed">フォローされています</div>
                </div>
              </v-card-actions>
            </div>
          </v-col>
        </v-row>
        <v-row class="mx-1">
          <v-col cols="12">
            <!-- 自己紹介文 -->
            <v-card class="px-2" elevation="0" shaped>{{ user.profile_text }}</v-card>
          </v-col>
        </v-row>
        <v-row>
          <!-- ユーザーの登録種目 -->
          <v-col cols="12" class="my-4">
            <UserEvent
              :events="events"
              :userId="user.id"
              />
          </v-col>
        </v-row>
        <v-row>
          <!-- ユーザーのログ -->
          <v-col cols="12">
            <Log
              v-for="log in logs"
              :key="log.id"
              :log="log"
              @favoriteLog="favoriteLog"
              @unFavoriteLog="unFavoriteLog"
            />
            <Pagination :current-page="currentPage" :last-page="lastPage" :user-id="Number($route.params.userId)"/>
          </v-col>
        </v-row>
      </v-card>
    </v-col>
  </v-row>

</template>

<script>
import { OK, CREATED } from '../util'
import Log from '../components/Log.vue'
import UserEvent from '../components/UserEvent.vue'
import Pagination from '../components/Pagination'

export default {
  components: {
    Log,
    UserEvent,
    Pagination
  },
  computed: {
    loginUserId () {
      return this.$store.getters['auth/userId']
    },
  },
  props: {
    page: {
      type: Number,
      required: false,
      default: 1
    }
  },
  data () {
    return {
      user: {},
      events: [],
      logs: [],
      logCount: '',
      following: '',
      followed: '',
      follow_count: '',
      follower_count: '',
      currentPage: 0,
      lastPage: 0
    }
  },
  methods: {
    // 指定したIDのユーザー情報取得
    async getUser () {
      const response = await axios.get(`/api/users/${this.$route.params.userId}`)

      console.log(response)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.user = response.data.user_data
      this.logCount = response.data.log_count
      this.following = response.data.is_following
      this.followed = response.data.is_followed
      this.follow_count = response.data.follow_count
      this.follower_count = response.data.follower_count

    },
    async getUserLogs () {
      const response = await axios.get(`/api/users/${this.$route.params.userId}/logs/?page=${this.page}`)

      console.log(response)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.logs = response.data.data
      this.currentPage = response.data.current_page
      this.lastPage = response.data.last_page
    },
    // 指定したIDのユーザーの種目取得
    async getUserEvents () {
      const response = await axios.get(`/api/users/${this.$route.params.userId}/events`)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.events = response.data
    },
    // Log子コンポーネントからemitで渡される、いいね登録
    async favoriteLog ({ id }) {
      const response = await axios.post('/api/favorites', {
        log_id: id
      })

      console.log(response)

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getUserLogs()
    },
    // Log子コンポーネントからemitで渡される、いいね解除
   async unFavoriteLog ({ id }) {
      const response = await axios.post('/api/favorites/' + id)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getUserLogs()
    },
    // 指定したIDのユーザーをフォロー
    async follow (id) {
      const response = await axios.post(`/api/users/follow/${id}`)

      console.log(response)

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.getUser()
    },
    // 指定したIDのユーザーのフォローを解除する
    async unFollow (id) {
      const response = await axios.post(`/api/users/unfollow/${id}`)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getUser()
    },
  },
  watch: {
    $route: {
      async handler () {
        await this.getUserLogs()
        await this.getUser()
        await this.getUserEvents()
      },
      immediate: true
    }
  },
  // ルート遷移時呼ばないため
  async mounted () {
  }
}
</script>

<style>

</style>