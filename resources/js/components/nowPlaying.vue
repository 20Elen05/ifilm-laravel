<template>
    <div>
        <h1 v-if="this.getLang === 'en'" class="text-light font24 mt-5">To The Cinema</h1>
        <h1 v-if="this.getLang === 'ru'" class="text-light font24 mt-5">В кино</h1>
        <router-link class="pr-widget" :to="{ name:'movie', params:{ id: item.movie_id }}" v-for="item in movies">
            <div class="bg-grey mt-4 d-flex justify-content-between text-decoration-none">
                <div class="d-flex align-items-start">
                    <img :src="`https://image.tmdb.org/t/p/w300_and_h450_bestv2/${item?.content?.poster_path}`" class="img-fluid w-30">
                    <div class="ms-2 mt-2 sideInfo">
                        <p class="font-weight-bold m-0 text-light"> {{ item?.content?.title }}</p>
                        <small class="text-light d-block">
                            <span v-if="this.getLang === 'en'">Year:</span>
                            <span v-if="this.getLang === 'ru'">Год:</span>
                            <strong class="">{{ item.release_date }}</strong>
                        </small>
                        <small class="text-light d-block">
                            <span v-if="this.getLang === 'en'">Total votes:</span>
                            <span v-if="this.getLang === 'ru'">Всего голосов:</span>
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
    name: 'nowPlaying',

    components: {
        useStore,
        mapGetters,
    },

    data() {
        return {
            nowPlaying:[],
            movies : [],
        }
    },

    computed: {
        ...mapGetters(['getLang']),
    },

    mounted(){
        this.fetchData()
    },

    watch: {
        getLang() {
            this.fetchData();
        },

        $route() {
            this.fetchData()
        },
    },

    methods : {
        async fetchData() {
            try {
                const response = await axios.get(`/api/nowPlayingMovies?lang=${this.getLang}`);
                this.movies = response.data.data;
            } catch (error) {
                console.error('Error fetching movies:', error);
            }
        },
    }
}

</script>


<style>
.font-weight-bold{
    font-weight: 700;
}

.bg-grey {
    background-color: #242526;
}

.w-30 {
    width: 28% !important;
}

.w-70 {
    width: 72%;
}

</style>
