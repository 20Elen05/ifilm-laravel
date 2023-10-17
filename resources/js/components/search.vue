<template>
    <navbar></navbar>
    <navpanel></navpanel>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6" v-for="movie in movies">
                <div class="mt-4">
                    <router-link :to="{ name:'movie', params:{ id: movie.movie_id }}" class="d-block text-reset " >
                        <div class="d-flex pr-layer-widget">
                            <img class="img-fluid w-30 h-202 img-border" :src="`https://image.tmdb.org/t/p/w300_and_h450_bestv2/${movie?.content?.poster_path}`">
                            <div class="p-2 w-70 d-flex justify-content-between ">
                                <div class="d-grid">
                                    <div class="line-height-16">
                                        <p class="font-weight-bold mb-1">{{ movie?.content?.title }}</p>
                                        <small class="font12">{{ movie?.content?.overview }}</small>
                                    </div>
                                    <div class="d-sm-block align-self-end">
                                        <rating :rating='`${movie.vote_average}`' :max-rating="10"></rating>
                                        <p class="m-0">Total votes: <strong>{{ movie.vote_count }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-end">
                                <span class="circle text-white font-weight-bold m-2">{{movie.vote_average }}</span>
                            </div>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
<!--        <Pagination class="ps-25 pt-5" v-model="currentPage" :total-pages="100"></Pagination>-->
    </div>
    <foooter></foooter>
</template>


<script>
import axios from 'axios';
import { useStore } from 'vuex';
import { mapGetters } from 'vuex';
import navbar from './navbar.vue'
import navpanel from './navpanel.vue'
import rating from './rating.vue'
import { Pagination } from 'flowbite-vue'
import foooter from './footer.vue'

export default {
    name: 'search',

    components: {navbar,
                navpanel,
                rating,
                useStore,
                mapGetters,
                Pagination,
                foooter},

    data() {
        return {
            movies: [],
            currentPage : 1,
        }
    },

    computed: {
        ...mapGetters(['getLang']),
    },


    mounted(){
        this.fetchMovies();
    },

    watch: {
        currentPage(newPage) {
            this.fetchMovies(newPage);
        },

        getLang() {
            this.fetchMovies();
        },
    },

    methods: {
        async fetchMovies() {
            try {
                const keyword = this.$route.params.keyword;

                const response = await axios.get(`/api/search?keyword=${keyword}&lang=${this.getLang}`);

                this.movies = response.data;

                console.log(this.movies);
            } catch (error) {
                console.error('Error fetching movies:', error);
            }
        },
    }
}
</script>


<style>
d-grid {
    display: grid;
}

.line-height-16 {
    line-height: 16px;
}

.font12 {
    font-size: 12px;
}

.pr-layer-widget {
    background-color: #242526 !important;
}

small {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
    -webkit-line-clamp: 6 !important;
    margin: 5px;
    max-height: 80px;
}

.h-202{
    height: 202px !important;
}

.img-border {
    border-style: solid;
    border-color: #fe7900;
    border-width: 0.1px !important;
}

.ps-25 {
    padding-left: 25% !important;
}
</style>
