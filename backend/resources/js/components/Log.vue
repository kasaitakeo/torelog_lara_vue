<template>
  <div>
    <v-card
      class="mx-auto"
      color="#26c6da"
      max-width="600"
    >
      <RouterLink class="button button--link" :to="{name: 'log.show', params: {logId: log.id}}">
        <v-card-title></v-card-title>
        <v-card-subtitle>

        </v-card-subtitle>
        <v-row>
        <EventLog
          v-for="event_log in log.event_logs" 
          :key="event_log.id"
          :item="event_log"
          :ableDelete="false"
        />

        </v-row>
        <v-card-text class="headline font-weight-bold">
          {{ log.text }}
        </v-card-text>
      </RouterLink>
      <v-card-actions>
        <v-list-item class="grow">
          <v-list-item-avatar color="grey darken-3">
            <v-img
              class="elevation-6"
              alt=""
              src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
            ></v-img>
          </v-list-item-avatar>

          <RouterLink class="button button--link" :to="{name: 'user.show', params: {userId: log.user.id}}">
            <v-list-item-content>
              <v-list-item-title>{{ log.user.name }}</v-list-item-title>
            </v-list-item-content>
          </RouterLink>
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
        <div v-show="show" v-for="comment in log.comments" :key="comment.id">
          <v-divider></v-divider>
          <v-card-subtitle>{{ comment.user.name }}</v-card-subtitle>
          <v-card-text>
            {{ comment.text }}
          </v-card-text>
        </div>
      </v-expand-transition>
    </v-card>
  </div>
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
    }
  },
  computed: {
    userId () {
      return this.$store.getters['auth/userId']
    }
  }
}
</script>