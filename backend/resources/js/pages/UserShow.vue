<template>
  <v-row>
    <v-col cols="12" class="mb-1">
      <v-card>
        <v-row>
          <v-col cols="4">
            <v-img
              size="60"
              color="grey"
              src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
            ></v-img>
            <div class="overline ml-1 mb-1">
              <span v-if="user.screen_name !== null">{{ user.screen_name }}</span>
            </div>
            <div class="font-weight-bold ml-1 mb-1">
              {{ user.name }}
            </div>
          </v-col>
          <v-col cols="8">
            <div class="d-flex flex-row mb-3">
              <div class="d-flex flex-column mb-3">
                <p class="font-weight-medium">ログ数</p>
                <span>{{ logCount }}</span>
              </div>
              <div class="d-flex flex-column mb-3">
                <p class="font-weight-medium">フォロー数</p>
                <span>{{ follow_count }}</span>
              </div>
              <div class="d-flex flex-column mb-3">
                <p class="font-weight-medium">フォロワー数</p>
                <span>{{ follower_count }}</span>
              </div>
            </div>
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
            </v-card-actions>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12">
            <p v-if="user.user_text !== null">{{ user.user_text }}</p>
            <p v-else>よろしくお願いします！</p>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" class="mb-4">
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
  },
  mounted () {
    this.getUser()
    this.getUserEvents()
  }
}
</script>

<style>

</style>