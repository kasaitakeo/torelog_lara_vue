<template>
  <v-card 
    v-show="message"
    raised
    color="orange lighten-2"
    class="mx-auto"
    max-width="800"
    height="60"
  >
    {{ message }}
  </v-card>
</template>

<script>
  import { mapState } from 'vuex'

  export default {
    computed: {
      ...mapState({
        message: state => state.message.content
      })
    }
  }
</script>