<template>
    <head>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    </head>
    <signUp v-if="$route.path === '/'"></signUp>
    <router-view v-if="$route.name == 'signIn' "></router-view>
    <router-view v-if="$route.name == 'standart'"></router-view>
    <router-view v-if="$route.name == 'profile'"></router-view>
    <router-view v-if="$route.name == 'movie'"></router-view>
    <router-view v-if="$route.name =='search'"></router-view>
    <router-view v-if="$route.path == '/checkout'"></router-view>
  </template>

  <script>

  import signUp from './components/signUp.vue'
  import profile from './components/profile.vue'
  import movie from './components/movie.vue'
  import search from './components/search.vue'

  export default {
    name: 'App',
    components: {
        signUp,
        profile,
        movie,
        search,
    }
  }
  </script>

  <style>
  @font-face {
    font-family: 'MonserratNormal';
    src: url('./assets/Montserrat-Medium.ttf') format('truetype');
  }

  @font-face {
    font-family: 'MonserratLight';
    src: url('./assets/Montserrat-Light.ttf') format('truetype');
  }

  a{
    text-decoration: none !important;
  }

  body{
    background-color: #18191a;
    font-family: MonserratNormal;
    color: white;
  }

  ul {
    list-style: none !important;
  }

  .container {
      max-width: 1130px !important;
      height: auto;
  }

  ul > li > button {
    color: white;
    background-color: unset !important;
    border: unset !important;
  }

  ul {
    background-color: unset !important;
  }

  .bg-white {
     background-color: unset !important; /*CHJNJES */
  }
</style>
