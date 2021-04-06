<template>
  <v-row>
    <v-col>
      <v-card v-for="user in users" :key="user.id" class="pa-2 mb-3">
        <v-row>
          <v-col cols="4"  >
            <v-avatar
              size="50"
              class="align-items-center my-1 ml-1"
            >
              <v-img
                color="grey"
                :src="`${user.profile_image}`"
              ></v-img>
            </v-avatar>
          </v-col>
          <v-col cols="8">
            <!-- UserShowへのリンク -->
            <RouterLink class="button button--link"  :to="{name: 'user.show', params: {userId: user.id}}">
              {{ user.screen_name }}
            </RouterLink>
          </v-col>
        </v-row>
      </v-card>
      <infinite-loading @infinite="infiniteHandler"></infinite-loading>
    </v-col>
  </v-row>
</template>

<script>
import { OK } from '../util'

export default {
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
          if (this.page < data.data.length) {
            this.page += 1
            this.users.push(...data.data)
            $state.loaded()
          } else {
            $state.complete()
          }
        }, 1500)
      }).catch((err) => {
        $state.complete()
      })
    },
  }
}
</script>

<style>

</style>