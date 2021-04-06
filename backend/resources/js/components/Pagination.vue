<template>
  <div class="pagination text-center">
    <div v-if="$route.name === '/'">
      <RouterLink
        v-if="! isFirstPage"
        :to="`/?page=${currentPage - 1}`"
        class="button"
      >&laquo; prev</RouterLink>
      <RouterLink
        v-if="! isLastPage"
        :to="`/?page=${currentPage + 1}`"
        class="button"
      >next &raquo;</RouterLink>
    </div>
    <div v-else-if="$route.name === 'user.show'">
      <RouterLink
        v-if="! isFirstPage"
        :to="`/users/${userId}/?page=${currentPage - 1}`"
        class="button"
      >&laquo; prev</RouterLink>
      <RouterLink
        v-if="! isLastPage"
        :to="`/users/${userId}/?page=${currentPage + 1}`"
        class="button"
      >next &raquo;</RouterLink>
    </div>

  </div>
</template>

<script>
export default {
  props: {
    // 現在ページ
    currentPage: {
      type: Number,
      required: true
    },
    // 総ページ数
    lastPage: {
      type: Number,
      required: true
    },
    userId: {
      type: Number,
    }
  },
  computed: {
    isFirstPage () {
      return this.currentPage === 1
    },
    isLastPage () {
      return this.currentPage === this.lastPage
    }
  }
}
</script>