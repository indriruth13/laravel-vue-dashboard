<template>
  <v-app>
    <router-view></router-view>
  </v-app>
</template>

<script>
  export default {
    name: 'App',
  }
</script>

<style>
  @font-face {
    font-family: "Airbnb Cereal Black";
    src: local("Airbnb Cereal Black"),
    url(/assets/fonts/AirbnbCerealBlack.ttf) format("truetype");
  }

  @font-face {
    font-family: "Airbnb Cereal Light";
    src: local("Airbnb Cereal Light"),
    url(/assets/fonts/AirbnbCerealLight.ttf) format("truetype");
  }

  @font-face {
    font-family: "Airbnb Cereal Book";
    src: local("Airbnb Cereal Book"),
    url(/assets/fonts/AirbnbCerealBook.ttf) format("truetype");
  }

  .v-application {
    font-family: 'Airbnb Cereal Light' !important;
  }
</style>
