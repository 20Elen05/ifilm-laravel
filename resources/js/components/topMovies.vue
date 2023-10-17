<template>
    <div>
        <div class="widget-header mb-4 d-flex align-items-center justify-content-between">
            <h1 class="text-light font24 m-0">Top Movies</h1>
            <button class="custom-button"><img @click="reload" class="refresh" src="../assets/reload.png"></button>
        </div>
        <router-link class="pr-widget" :to="{ name:'movie', params:{ id: item.movie_id }}" v-for="item in movies" >
            <div class="bg-grey mt-4 d-flex justify-content-between">
                <div class="d-flex align-items-start ">
                    <img :src="`https://image.tmdb.org/t/p/w300_and_h450_bestv2/${item?.content?.poster_path}`" class="img-fluid w-30">
                    <div class="ms-2 mt-2 sideInfo">
                        <p class="font-weight-bold m-0 text-light"> {{ item?.content?.title }}</p>
                        <small class="text-light d-block">
                            <span>Year:</span>
                            <strong>{{ item.release_date }}</strong>
                        </small>
                        <small class="text-light d-block">
                            <span>Total votes:</span>
                            <strong>{{item.vote_count}}</strong>
                        </small>
                    </div>
                </div>
                <div class="d-flex align-items-end text-light m-2">
                    <span class="circle font-weight-bold">{{ item.vote_average }}</span>
                </div>
            </div>
        </router-link>
    </div>
</template>

<script>
import axios from 'axios';
import { useStore } from 'vuex';
import { mapGetters } from 'vuex';
export default {
    name: 'topMovies',

    components: {
        useStore,
        mapGetters},

    data() {
        return {
            topMovies:[],
            movies : [],
            topPage : 1,
        }
    },

    computed: {
        ...mapGetters(['getLang']),
    },

    mounted(){
        this.fetchData()
    },

    watch: {
        $route() {
            this.fetchData()
        },

        getLang() {
            this.fetchData();
        },
    },

    methods : {
        async fetchData() {
            try {
                const response = await axios.get(`/api/topMovies?page=${this.topPage}&lang=${this.getLang}`);

                this.movies = response.data.data;

                console.log(this.movies);
            } catch (error) {
                console.error('Error fetching movies:', error);
            }
        },

        reload() {
            this.topPage += 1;
            console.log(this.topPage)
            this.fetchData()
        }
    }
}

</script>


<style>
.pr-widget {
    transition: all .3s ease;
}

.pr-widget :hover{
    transition: all .4s ease;
    transform: scale(1.02);
}

.font24{
    font-size: 24px !important;
}

.bg-grey {
    background-color: #242526;
}

.circle {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fe7900 !important;
    border-radius: 50%!important;
}

.refresh {
    width: 20px;
}

.custom-button {
  border: none;
  background: none;
  padding: 0;
  margin: 0;
  font-family: inherit;
  font-size: inherit;
  color: inherit;
  cursor: pointer;
  text-decoration: none;
}
</style>
