<template>
  <nav class="navbar">
    <RouterLink class="navbar__brand" to="/">
      torelog
    </RouterLink>
    <div class="navbar__menu">
      <div v-if="isLogin" class="navbar__item">
        <RouterLink v-bind:to="{name: 'event.create'}">
          <button class="btn btn-success">addevent</button>
        </RouterLink>
        <RouterLink v-bind:to="{name: 'log.create'}">
          <button class="btn btn-success">torelog</button>
        </RouterLink>
        <RouterLink v-bind:to="{name: 'user'}">
          <button class="btn btn-success">userlist</button>
        </RouterLink>
      </div>
      <span v-if="isLogin" class="navbar__item">
        {{ userName }}
      </span>
      <div v-else class="navbar__item">
        <RouterLink class="button button--link" to="/login">
          Login / Register
        </RouterLink>
      </div>
    </div>
  </nav>
</template>
<script>
export default {
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    userName () {
      return this.$store.getters['auth/userName']
    }
  }
}
</script>