<template>
  <v-app>
    <v-container>
      <div class="header d-flex flex-column">
        <img src="/assets/logo.png" alt="Stendard Logo">
        <span>Hi there, let’s begin our journey.</span>
      </div>
      <div class="form-container d-flex flex-column">
        <v-form
          ref="form"
          v-model="valid"
          lazy-validation
        >
          <div class="d-flex flex-column form-container__input justify-center align-center">
            <h4 class="font-weight-black">Login</h4>
            <v-text-field
              v-model="email"
              :rules="emailRules"
              label="E-mail"
              class="w-full"
              @keyup.enter="login"
              required
            ></v-text-field>
            <v-text-field
              v-model="password"
              label="Password"
              @click:append="show = !show"
              :type="show ? 'text' : 'password'"
              :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
              :rules="passwordRules"
              class="w-full"
              @keyup.enter="login"
              required
            ></v-text-field>
            <a href="#" class="text-decoration-none text-right w-full">I forgot my password</a>
            <span class="alert-box">{{ alert }}</span>
          </div>
          <div class="d-flex flex-column">
            <v-btn
              :disabled="!valid"
              color="#1890FF"
              class="mt-7 text-white form-container__login"
              @click="login"
            >
              <b class="text-white">Login</b>
            </v-btn>
            <div class="mt-10">
              <span>I don’t have Stendard account</span>
              <br>
              <a href="#" class="text-decoration-none"><h4>Sign Me Up!</h4></a>
            </div>
          </div>
        </v-form>
      </div>
      <div class="footer"></div>
      <h1>LET’S <br>DO THIS</h1>
    </v-container>
  </v-app>
</template>

<script>
export default {
  name: 'Login',
  data: () => ({
    valid: true,
    email: '',
    emailRules: [
      v => !!v || 'E-mail is required',
      v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
    ],
    password: '',
    passwordRules: [
      v => !!v || '',
      v => (v && v.length >= 8) || 'Password must be valid',
    ],
    show: false,
    alert: ''
  }),
  methods: {
    login () {
      this.$refs.form.validate()
      if ( !this.password ) {
        this.alert = 'Please input your password first to login into your account.'
        this.valid = false
      } else if ( this.password !== 'admin1234' ) {
        this.alert = 'Email or password are incorrect, or verify your email first if you just registered your account'
        this.valid = false
      } else {
        this.alert = ''
      }
      if ( this.valid ) {
        this.$router.push('/dashboard')
        localStorage.setItem('isLoggedIn', true) 
      }
    }
  }
}
</script>

<style lang="scss">
  .header {
    margin-top: 2.5em;
    img {
      width: 356px;
      margin-left: -1.5em;
    }
    span {
      margin-top: 2em;
    }
  }

  .form-container {
    text-align: center;
    position: absolute;
    left: 60%;
    top: 30%;
    width: 366px;
    &__input {
      border: 2px solid #FFFFFF;
      padding: 46px 50px;
      border-radius: 24px;
      background: #ffffff;
      box-shadow: -19px -20px 50px 0 rgb(83 189 145 / 30%), 20px 16px 100px 4px rgb(187 83 198 / 30%);
    }
    &__login {
      border-radius: 16px !important;
      height: 50px !important;
      text-transform: none !important;
    }
  }

  .footer {
    background-image: url('/assets/bg.png');
    background-repeat: no-repeat;
    background-size: cover;
    position: absolute;
    width: 681px;
    height: 450px;
    left: -5px;
    bottom: 0;
    z-index: 3;
  }
  h1 {
    font-size: 80px;
    line-height:80px;
    color: #DDDDDD;
    position: absolute;
    left: 13%;
    bottom: 43%;
    z-index: 2;
  }

  .alert-box {
    font-size: 12px;
    color: #E70000;
    line-height: 16px;
    margin-top: 31px;
  }

</style>
