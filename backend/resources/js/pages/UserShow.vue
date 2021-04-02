<template>
  <v-row>
    <v-col cols="12" class="ma-1">
      <v-card elevation="5">
        <v-row class="ma-1">
          <v-col cols="4" >
            <v-avatar
              size="62"
              class="align-items-center"
            >
              <v-img
                color="grey"
                :src="`${user.profile_image}`"
              ></v-img>
            </v-avatar>
            <div class="text-secondary">
              <span v-if="user.screen_name !== null">{{ user.screen_name }}</span>
            </div>
            <div class="font-weight-bold ml-1 mb-1">
              {{ user.name }}
            </div>
          </v-col>
          <v-col cols="8" class="d-flex flex-column align-end">
              <div class="d-flex flex-row">
                <v-card class="d-flex align-end flex-column ma-1 pt-2" elevation="0" tile>
                  <p class="font-weight-medium">ログ</p>
                  <p>{{ logCount }}</p>
                </v-card>

                <v-card class="d-flex align-end flex-column ma-1 pt-2" elevation="0" tile>
                  <p class="font-weight-medium">フォロー</p>
                  <p>{{ follow_count }}</p>
                </v-card>

                <v-card class="d-flex align-end flex-column ma-1 pt-2" elevation="0" tile>
                  <p class="font-weight-medium">フォロワー</p>
                  <p>{{ follower_count }}</p>
                </v-card>
              </div>
              
            <div class="d-flex flex-row mt-auto">
              <v-card-actions>
                <RouterLink v-if="user.id === loginUserId" class="button button--link" :to="{ name: 'user.edit', params: { userId: user.id }}">
                  <v-btn
                    outlined
                    rounded
                    text
                  >
                    プロフィール編集
                  </v-btn>
                </RouterLink>
                <div v-else>
                  <v-btn v-if="following" @click="unFollow(user.id)">フォロー解除</v-btn>
                  <v-btn v-else @click="follow(user.id)" color="success">フォローする</v-btn>
                  <div v-if="followed">フォローされています</div>

                </div>
              </v-card-actions>

            </div>
          </v-col>
        </v-row>
        <v-row class="ma-1">
          <v-col cols="12">
            <v-card class="px-2" elevation="0" shaped v-if="user.user_text !== null">{{ user.user_text }}</v-card>
            <v-card class="pa-2" elevation="0" shaped v-else>よろしくお願いします！</v-card>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" class="my-4">
            <UserEvent
              :events="events"
              :userId="user.id"
              />
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12">
            <Log
              v-for="log in user.logs"
              :key="log.id"
              :log="log"
              @favoriteLog="favoriteLog"
              @unFavoriteLog="unFavoriteLog"
            />
          </v-col>
        </v-row>
    <!-- <RouterLink :to="{ name: 'user.event', params: { userId: user.id }}">
      <button>種目リスト</button>
    </RouterLink> -->
    <!-- <UserEvent
    :userId="user.id"
    /> -->
      </v-card>
    </v-col>
  </v-row>

</template>

<script>
import { OK } from '../util'
import Log from '../components/Log.vue'
import UserEvent from '../components/UserEvent.vue'

export default {
  components: {
    Log,
    UserEvent,
  },
  // props: {
  //   userId: Number
  // },
  computed: {
    loginUserId () {
      return this.$store.getters['auth/userId']
    },
  },  
  data () {
    return {
      user: {},
      events: [],
      logCount: '',
      following: '',
      followed: '',
      follow_count: '',
      follower_count: '',

    }
  },
  methods: {
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
    async getUserEvents () {
      const response = await axios.get(`/api/${this.$route.params.userId}/events`)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.events = response.data
    },
    async favoriteLog ({ id }) {
      const response = await axios.post('/api/favorites', {
        log_id: id
      })

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getUser()
    },
    async unFavoriteLog ({ id }) {
      const response = await axios.post('/api/favorites/' + id)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getUser()
    },
    async follow (id) {
      const response = await axios.post(`/api/users/follow/${id}`)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getUser()
    },
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
  mounted () {
    this.getUser()
    this.getUserEvents()
  }
}
</script>

<style>

</style>