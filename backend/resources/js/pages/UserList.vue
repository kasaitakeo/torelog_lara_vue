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
            <RouterLink class="button button--link"  :to="{name: 'user.show', params: {userId: user.id}}">
              {{ user.name }}
            </RouterLink>
          </v-col>
        </v-row>

      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import { OK } from '../util'

export default {
  data () {
    return {
      users: {},
    }
  },
  methods: {
    async getUsers () {
      const response = await axios.get(`/api/users`)

      console.log(response)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.users = response.data
    },
  },
  mounted () {
    this.getUsers()
  }
}
</script>

<style>

</style>