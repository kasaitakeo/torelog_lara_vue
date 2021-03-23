<template>
  <v-form
    @submit.prevent="submit"
    v-model="valid"
    lazy-validation
    enctype="multipart/form-data"
  >
    <v-text-field
      v-model="name"
      :counter="20"
      :rules="nameRules"
      label="名前"
      required
    ></v-text-field>

    <v-text-field
      v-model="screenName"
      :counter="20"
      :rules="nameRules"
      label="アカウント名"
      required
    ></v-text-field>

    <v-text-field
      v-model="email"
      :rules="emailRules"
      label="E-mail"
      required
    ></v-text-field>

    <input class="form__item" type="file" @change="onUpload">
      <output class="form-output" v-if="image">
        <img :src="image" alt="">
      </output>
    <!-- <v-file-input
      type="file"
      accept="image/*"
      label="プロフィール画像"
      @change="onImageUploaded"
    >
      <output class="form-output" v-if="image !== null">
        <img :src="'storage/profile_image/'.image" alt="">
      </output>
    </v-file-input> -->
    
    <v-btn
      type="submit"
      color="success"
      class="mr-4"
    >
      更新
    </v-btn>
  </v-form>
</template>

<script>
import { OK } from '../util'

export default {
  props: {
    userId: Number
  },
  data () {
    return {
      user: {},
      screenName: '',
      name: '',
      email: '',
      image: null,
      // preview: null,
      valid: false,
      nameRules: [
        v => !!v || 'Name is required',
        v => (v && v.length <= 10) || 'Name must be less than 10 characters',
      ],
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],
    }
  },
  methods: {
    async getUser () {
      const response = await axios.get('/api/users/' + this.userId)
      console.log(response.data)
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.screenName = response.data.user_data.screen_name
      this.name = response.data.user_data.name
      this.email = response.data.user_data.email
      if (response.data.user_data.profile_image !== null) {
        this.image = response.data.user_data.profile_image
      }
    },
    async submit () {
      
      let formData = new FormData()
      formData.append('name', this.name)
      formData.append('screen_name', this.screenName)
      formData.append('email', this.email)
      formData.append('profile_image', this.image)
      console.log(formData.get('name'))
      console.log(...formData.entries())

      const response = await axios.post('/api/users/', formData, {
        headers: {
          'X-HTTP-Method-Override': 'PUT',
          'Content-Type': 'multipart/form-data'
        }
      })  

      console.log(response)

       if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      this.$router.push({name: 'user'})
    },
    // フォームでファイルが選択されたら実行される
    // onUpload (event) {
    //   this.preview = event.target.files[0]
    //   this.image = event.target.files[0]
    // },
    onUpload (event) {
      // event(=e)から画像データを取得する
      const image = event.target.files[0]
      this.createImage(image)
    },
    createImage(image) {
      const reader = new FileReader()
      // imageをreaderにDataURLとしてattachする
      reader.readAsDataURL(image)
      // readAdDataURLが完了したあと実行される処理
      reader.onload = () => {
        this.image = reader.result
      }
    },
  },
  mounted () {
    if (!this.$store.getters['auth/check']) {
      this.$router.push('/')
    }
    this.getUser()
  }
}
</script>

<style>

</style>