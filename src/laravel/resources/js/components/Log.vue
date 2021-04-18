<template>
  <v-card
    color="#E3F2FD"
    class="ma-1 pa-2" elevation="10"
  >
    <v-row class="ma-1">
      <v-col cols="3">{{ log.created_at | moment }}</v-col>
      <v-col cols="9">title: {{ log.title }}</v-col>
    </v-row>
    <v-row>
      <!-- 登録した種目ログ表示 -->
      <EventLog
        v-for="event_log in log.event_logs" 
        :key="event_log.id"
        :item="event_log"
      />
    </v-row>
    <v-row>
      <v-col cols="12">
        <!-- ログのテキストの表示 -->
        <v-card-text class="px-2">
          <p class="font-weight-regular">torememo</p><p class="headline">{{ log.text }}</p>
        </v-card-text>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <v-card-text 
          align="end"
          justify="end"
        >
          <!-- LogShowページの場合のみログの編集、削除ボタン表示 -->
          <div v-if="this.$route.name === 'log.show'">
            <!-- ログを作成したユーザーがログインユーザーの場合 -->
            <div v-if="log.user.id === loginUserId">
              <RouterLink class="button button--link" v-bind:to="{ name: 'log.edit', params: { logId: log.id }}">
                <v-btn
                  outlined
                  x-small
                >編集</v-btn> 
              </RouterLink>
              <v-btn
                @click="deleteLog(log.id)"
                outlined
                x-small
              >削除</v-btn>
            </div>
          </div>
          <!-- LogShowページでない場合、ログの詳細ボタンを表示 -->
          <div v-else>
            <RouterLink class="button button--link" :to="{ name: 'log.show', params: { logId: log.id }}">
              <div>ログ詳細</div>
            </RouterLink>
          </div>
        </v-card-text>
      </v-col>
    </v-row>
    <v-card-actions>
      <v-row>
        <v-col 
          cols="4" 
          align="start" 
          justify="start"
        >
        <v-row class="d-flex justify-start pl-2">
          <v-avatar color="grey darken-3">
            <v-img
              class="elevation-6"
              alt=""
              :src="log.user.profile_image"
            ></v-img>
          </v-avatar>

        </v-row>
        <v-row class="d-flex justify-start">
          <!-- ログ作成ユーザーの名前をクリックするとユーザー詳細ページへ飛ぶ -->
          <RouterLink class="button button--link" :to="{ name: 'user.show', params: { userId: log.user.id }}">
            <div class="font-weight-bold">{{ log.user.screen_name }}</div>
          </RouterLink>
        </v-row>
        </v-col>
        <v-col
          cols="8"
          align="end"
          justify="end"
        >
          <!-- favoriteStatusの真偽値の状態で条件分岐し、いいね登録といいね解除のアイコンを表示 -->
          <v-icon class="mr-0" v-if="favoriteStatus(log.favorites)" @click="favoriteLog(log.id)">
            favorite_border
          </v-icon>
          <v-icon class="mr-0" v-else @click="unFavoriteLog(log.id)">
            favorite
          </v-icon>
          <!-- いいね数 -->
          <span class="subheading mr-2">{{ log.favorites.length }}</span>
          <span class="mr-0">·</span>
          <!-- コメントアイコンをクリックするとコメント作成ページへ飛ぶ -->
          <RouterLink class="button button--link" :to="{name: 'comment.create', params: {logId: log.id}}">
            <v-icon class="mr-1">
              mdi-share-variant
            </v-icon>
            <!-- コメント数 -->
            <span class="subheading">{{ log.comments.length }}</span>
          </RouterLink>
          <!-- クリックすることで全コメントを表示 -->
          <v-btn
            icon
            @click="show = !show"
          >
            <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
          </v-btn>
        </v-col>
      </v-row>
    </v-card-actions>
    <v-expand-transition>
      <v-card v-show="show">
        <v-list two-line>
          <!-- 全てのコメントの内5つまで表示（0番目から5番目までのコメント） -->
          <template v-for="comment in log.comments.slice(0, 5)" >
            <v-list-item :key="comment.id">
              <v-list-item-avatar>
                <img :src="comment.user.profile_image">
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title>{{ comment.user.screen_name }}</v-list-item-title>
                <v-list-item-subtitle>{{ comment.text }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </template>
        </v-list>
      </v-card> 
    </v-expand-transition>
  </v-card>
</template>
<script>
// LogList LogShow UserShowが親コンポーネント
import { OK } from '../util'
import EventLog from '../components/EventLog.vue'
import moment from 'moment'

export default {
  filters: {
    moment: function (date) {
      return moment(date).format('MM/DD');
    }
  },
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
    // 引数idのログにいいね登録
    async favoriteLog (log_id) {
      this.$emit('favoriteLog', {
        id: log_id,
      })
    },
    // 引数idのログにいいね解除
    async unFavoriteLog (log_id) {
      this.$emit('unFavoriteLog', {
        id: log_id,
      })
    },
    // ログインユーザーがログをいいねしているかの状態
    favoriteStatus (favorites) {
      // このログの全てのいいねをループ
      for (let favorite of favorites) {
        // いいねしたユーザーidとログインユーザーidが等しくない時false
        if (favorite.user_id === this.loginUserId) {
          return false
        } 
      }
      return true
    },
    // ログ削除
    async deleteLog (log_id) {
      const response = await axios.delete('/api/logs/' + log_id)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.$store.commit('message/setContent', {
        content: 'トレログを削除しました！',
        timeout: 3000
      })

      this.$router.push('/')
    },
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    loginUserId () {
      return this.$store.getters['auth/userId']
    }
  }
}
</script>