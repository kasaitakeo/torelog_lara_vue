<template>
  <!-- <div v-if="log.event_logs.length > 0"> -->
    <!-- <v-row>
      <v-col cols="12" class="ma-3"> -->
        <v-card
          color="#E3F2FD"
          class="mb-4"
        >
            <v-row>
              <EventLog
                v-for="event_log in log.event_logs" 
                :key="event_log.id"
                :item="event_log"
                :ableDelete="false"
              />
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-card-text class="headline px-2">
                  {{ log.text }}
                </v-card-text>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-card-text 
                  align="end"
                  justify="end"
                >
                  <div v-if="this.$route.name === 'log.show'">
                  <div v-if="log.user.id === userId">
                    <RouterLink class="button button--link" v-bind:to="{name: 'log.edit', params: {logId: log.id}}">
                      <div>edit</div> 
                    </RouterLink>
                    <div @click="deleteLog(log.id)">delete</div>
                  </div>
                </div>
                <div v-else>
                  <RouterLink class="button button--link" :to="{name: 'log.show', params: {logId: log.id}}">
                    <div>ログ詳細</div>
                  </RouterLink>
                </div>
                </v-card-text>
              </v-col>
            </v-row>
          
          <v-card-actions>
            <v-list-item class="grow">
              <v-list-item-avatar color="grey darken-3">
                <v-img
                  class="elevation-6"
                  alt=""
                  :src="log.user.profile_image"
                ></v-img>
              </v-list-item-avatar>
              <v-list-item-content>
                <RouterLink class="button button--link" :to="{name: 'user.show', params: {userId: log.user.id}}">
                  {{ log.user.name }}
                </RouterLink>
              </v-list-item-content>
              <v-row
                align="center"
                justify="end"
              >
                <v-icon class="mr-1" v-if="favoriteStatus(log.favorites)" @click="favoriteLog(log.id)">
                  favorite_border
                </v-icon>
                <v-icon class="mr-1" v-else @click="unFavoriteLog(log.id)">
                  favorite
                </v-icon>
                <span class="subheading mr-2">{{ log.favorites.length }}</span>
                <span class="mr-1">·</span>
                <RouterLink class="button button--link" :to="{name: 'comment.create', params: {logId: log.id}}">
                  <v-icon class="mr-1">
                    mdi-share-variant
                  </v-icon>
                  <span class="subheading">{{ log.comments.length }}</span>
                </RouterLink>
                <v-btn
                  icon
                  @click="show = !show"
                >
                  <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
                </v-btn>
              </v-row>
            </v-list-item>
          </v-card-actions>
          <v-expand-transition>
            <v-card v-show="show">
              <v-list two-line>
                <template v-for="comment in log.comments.slice(0, 6)" >
                  <v-list-item :key="comment.id">
                    <v-list-item-avatar>
                      <img :src="comment.user.profile_image">
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title>{{ comment.user.name }}</v-list-item-title>
                      <v-list-item-subtitle>{{ comment.text }}</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </template>
              </v-list>
            </v-card> 
            
          </v-expand-transition>
        </v-card>
      <!-- </v-col>
    </v-row> -->
</template>
<script>
import EventLog from '../components/EventLog.vue'

export default {
  components: {
    EventLog,
  },
  props: {
    log: {
      type: Object,
      required: true
    },
  },
  data () {
    return {
      show: null
    }
  },
  methods: {
    async favoriteLog (log_id) {
      this.$emit('favoriteLog', {
        id: log_id,
      })
    },
    async unFavoriteLog (log_id) {
      this.$emit('unFavoriteLog', {
        id: log_id,
      })
    },
    favoriteStatus (favorites) {

      console.log(this.userId)

      for (let favorite in favorites) {
        if (favorite.user_id !== this.userId) {
          return false
        } 
      }
      return true
    },
    async deleteLog (id) {
      const response = await axios.delete('/api/logs/' + id)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.getLog()
    },
  },
  computed: {
    userId () {
      return this.$store.getters['auth/userId']
    }
  }
}
</script>