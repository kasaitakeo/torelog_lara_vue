<template>
  <v-row>
    <v-col>
      <v-card v-for="item in users" :key="item.id" class="pa-2 mb-3">
        <v-row>
          <v-col cols="2">
            <v-avatar
              size="50"
              class="align-items-center my-1 ml-1"
            >
              <v-img
                color="grey"
                :src="`${item.user_data.profile_image}`"
              ></v-img>
            </v-avatar>
          </v-col>
          <v-col cols="4">
            <v-row>
              <!-- UserShowへのリンク -->
              <RouterLink class="button button--link"  :to="{name: 'user.show', params: {userId: item.user_data.id}}">
                <div class="font-weight-bold">{{ item.user_data.screen_name }}</div>
              </RouterLink>
            </v-row>

          </v-col>
          <v-col cols="6" class="d-flex align-end justify-end">
            <RouterLink v-if="item.user_data.id === loginUserId" class="button button--link" :to="{ name: 'user.edit', params: { userId: item.user_data.id }}">
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
              <v-btn 
                v-if="item.is_following" 
                @click="unFollow(item.user_data.id)"
                rounded
              >
                フォロー解除
              </v-btn>
              <v-btn 
                v-else 
                @click="follow(item.user_data.id)" 
                color="success"
                rounded
              >
                フォローする
              </v-btn>
              <div v-if="item.is_followed">フォローされています</div>
            </div>
          </v-col>
        </v-row>
      </v-card>
      <infinite-loading @infinite="infiniteHandler"></infinite-loading>
    </v-col>
  </v-row>
</template>

<script>
import { OK, CREATED } from '../util'

export default {
  computed: {
    loginUserId () {
      return this.$store.getters['auth/userId']
    },
  },
  data () {
    return {
      users: [],
      page: 1,
    }
  },
  methods: {
    infiniteHandler($state) {
      axios.get('/api/users', {
        params: {
          page: this.page,
          per_page: 1
        },
      }).then(({ data }) => {
        console.log(data)
        //そのままだと読み込み時にカクつくので1500毎に読み込む
        setTimeout(() => {
          if (this.page < data.users.data.length) {
            this.page += 1
            this.users.push(...data.data)
            $state.loaded()
          } else {
            $state.complete()
          }
        }, 500)
      }).catch((err) => {
        $state.complete()
      })
    },
    async follow (id) {
      const response = await axios.post(`/api/users/follow/${id}`)

      console.log(response)

      if (response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.$router.go({path: this.$router.currentRoute.path, force: true})
    },
    // 指定したIDのユーザーのフォローを解除する
    async unFollow (id) {
      const response = await axios.post(`/api/users/unfollow/${id}`)

      console.log(response)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false  
      }

      this.$router.go({path: this.$router.currentRoute.path, force: true})
    },
  }
}
</script>

<style>

</style>