<template>
    <header class="container px-4" :class="{ 'night-mode': isNightMode }">
       <nav class="navbar pt-3 shadow-none navbar-light navbar-expand-lg">
            <div class="navbar-brand">
                <router-link to="/standart">
                    <img width="120" id="logo" :src="logoImage" />
                </router-link>
            </div>
            <select v-model="selectedLang"  class="font-light mx-2 text-light form-control border-0 p-0 shadow-none w-auto bg-transparent">
                <option value="en">EN</option>
                <option value="ru">RU</option>
            </select>
            <div class="navbar-collapse collapse" style="display: none;">
                <form class="d-flex ms-4 ms-sm-3 pt-lg-0 flex-nowrap justify-content-end form-inline">
                    <input class="me-lg-2 radius-5 px-3 py-1 shadow-none bg-transparent form-control form-control-sm font-light" v-model="searchedMovie" type="text" placeholder="Search" v-on:click="changeBorderColor">
                    <router-link v-if="searchedMovie" :to="{ name:'search', params: { keyword : this.searchedMovie}}">
                        <button name="search" size="sm" class="btn btn-if mx-2 my-sm-0 p-1 font-weight-bold size13">Search</button>
                    </router-link>
                </form>
                <div class="font-light navbar-nav px-3 font17 ms-auto test-right mt-2 mt-lg-0">
                    <router-link :to="{ name:'movie', params:{ id: randomId }}" class="mx-3 ps-lg-3 text-light px-1">Random</router-link>
                    <NightMode class="themeButton"></NightMode>
                    <label for="themeMode" class="ms-3 custom-control-label">Night Mode</label>
                </div>
            </div>

           <router-link :to="{name:'profile'}">
               <div class="side-by-side">
                   <img class="ms-4 d-block" src="../assets/account.png" />
                   <div><p class="mt-3 ms-4 text-light">My Account</p></div>
               </div>
           </router-link>
       </nav>
    </header>
</template>

<script>
import 'bootstrap/dist/css/bootstrap.css'
import NightMode from './nightMode.vue';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import { useStore } from 'vuex';
import ifilmLogo from '@/assets/ifilm.png';
import lightModeLogo from '@/assets/light-mode-logo.png';

export default {
  name: 'navbar',
  components: {
      axios,
      computed,
      NightMode,
      useStore
  },

  props: {
    isNightMode: {
      type: Boolean,
      required: true,
    },
  },

  data() {
    return {
      randomMovies: [],
      randomId: Number,
      searchedMovie: '',
      selectedLang: 'en',
    }
  },

  mounted() {
    this.fetchSearchedMovie();
  },

  computed: {
    selectedLang: {
      get() {
        return this.$store.getters.getLang;
      },
      set(value) {
        this.$store.commit('setLang', value);
      }
    },
      logoImage() {
          if (this.isNightMode) {
              return lightModeLogo;
          } else {
              return ifilmLogo ;
          }
      }
  },

  methods: {
      async fetchSearchedMovie() {
          try {
              const apiKey = 'a348e7136197bd5186dd097b93931f79';
              const language = 'en';

              const response = await axios.get(`https://api.themoviedb.org/3/movie/top_rated?api_key=${apiKey}&language=${language}`);

              this.randomMovies = response.data;

              const randomIndex = Math.floor(Math.random() * this.randomMovies.results.length);

              this.randomId = randomIndex;

              console.log(this.randomId);
          } catch (error) {
              console.error(error);
          }
      },

      changeBorderColor(event) {
          event.target.style.borderColor = '#f97701';
      },

      updateLanguage() {
          this.$store.commit('setLang', this.selectedLang);
      },

      toggleNightMode() {
          this.$emit('toggle-night-mode');

      },
  },

  watch: {
    selectedLang(newVal) {
      console.log(newVal)
    },

    $route() {
      this.fetchSearchedMovie();
    },
  },
}
</script>

<style>
.bg-transparent {
  background-color: transparent!important;
}

.btn {
  color: white;
  font-size: 14px;
  font-weight: bold;
}

.btn-if {
  background-color: #fe7900!important;
  color: #fff!important;
}

.radius-5 {
  border-radius: 20px;
}

.font-light {
  font-family: MonserratLight;
}

input {
  color: white !important;
}

::placeholder {
  color: white !important;
}

label {
  color: white !important;
}

select > option {
  color: #fe7900;
  background-color: #18191a;
}

.side-by-side {
    display: flex; /* Use flexbox */
    align-items: center; /* Align items vertically in the center */
}

/* NIGHT MODE  */

.night-mode {
  background-color: #fff !important;
}

.night-mode a, label {
  color: #7f7f7f !important;
}

.night-mode a:hover{
  color: #000 !important;
}

.night-mode label:hover {
  color: #000 !important;
}

.night-mode select {
  color: #000 !important;
}

.night-mode .carousel {
  background-color: #343a40 !important;
}

.night-mode ol > li > a > h2 {
  color: white !important;
}

.night-mode .oneMovie > h2 {
  color: #000 !important;
}

.night-mode  h1{
  color: #fe7900 !important;
 }

.night-mode .refresh{
  border-radius: 50%;
  background-color: #fe7900;
}

.night-mode .bg-grey {
  background-color: #f8f8f8;
}

.night-mode .sideInfo > p {
  color: #000 !important;
}

.night-mode .sideInfo > small {
  color: #7c7c7c !important;
}

.night-mode .pr-layer-widget {
  background-color: #f8f8f8 !important;
}

.night-mode .list-group-item  {
  color: #000 !important;
}

.night-mode .list-group-item {
  border-style: solid !important;
  border-width: 0.2px !important;
}

.night-mode .side-by-side > div > p {
    color: black !important;
}
.night-mode input {
    color: black !important;
}

.night-mode input::placeholder {
    color: black !important;
}

</style>
