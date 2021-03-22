<template>
  <v-row>
    <v-col cols="12" class="mb-3">
      
      <v-card>
        <v-row>
          <v-col cols="4">
          <v-list-item three-line>
            <v-list-item-avatar
              tile
              size="80"
              color="grey"
            ></v-list-item-avatar>
          </v-list-item>
          <v-list-item-content>
            <div class="overline mb-4">
              OVERLINE
            </div>
            <v-list-item-title class="headline mb-1">
              Headline 5
            </v-list-item-title>
            <v-list-item-subtitle>Greyhound divisely hello coldly fonwderfully</v-list-item-subtitle>
          </v-list-item-content>
          </v-col>
          <v-col cols="8">
            <v-list-item-content>
              <div class="overline mb-4">
                フォローしています
              </div>
              <v-list-item-title class="headline mb-1">
                <div class="d-flex flex-row mb-3 align-items-center">
                  <div class="d-flex flex-column mb-3 align-items-center">
                    <p class="font-weight-bold">ログ数</p>
                    <span>{{ logCount }}</span>
                  </div>
                  <div class="d-flex flex-column mb-3 align-items-center">
                    <p class="font-weight-bold">フォロー数</p>
                    <span>{{ follow_count }}</span>
                  </div>
                  <div class="d-flex flex-column mb-3 align-items-center">
                    <p class="font-weight-bold">フォロワー数</p>
                    <span>{{ follower_count }}</span>
                  </div>

                </div>
            </v-list-item-title>

            <v-list-item-subtitle>Greyhound divisely hello coldly fonwderfully</v-list-item-subtitle>
          </v-list-item-content>

          </v-col>


        </v-row>


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
      </v-card>
    </v-col>
    <p>{{ user.name }}</p>
    <UserEvent
      :events="events"
      :userId="user.id"
      />
    <!-- <RouterLink :to="{ name: 'user.event', params: { userId: user.id }}">
      <button>種目リスト</button>
    </RouterLink> -->
    <!-- <UserEvent
    :userId="user.id"
    /> -->
    <Log
      v-for="log in user.logs"
      :key="log.id"
      :log="log"
      @favoriteLog="favoriteLog"
      @unFavoriteLog="unFavoriteLog"
    />
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