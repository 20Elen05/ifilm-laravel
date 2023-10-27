<template>
    <div class="container m-0 p-0">
        <div class="row">
            <Pagination class="ps-5" v-model="currentPage" :total-pages="100"></Pagination>
            <div v-for="item in movies" class="col-6 col-md-4 pb-4 d-block text-reset p-0 oneMovie">
                <router-link :to="{ name:'movie', params:{ id: item.movie_id }}" >
                    <div class="position-relative overflow-hidden text-white me-4 movie-layer">
                        <div class="pr-layer font-light hidden">
                            <h5>{{item.original_title}}</h5>
                            <ul class="p-0 list-style-none flex-wrap">
                                <li class="font14 d-block">
                                    <span>Year: </span>
                                    <span>{{ item.release_date }}</span>
                                </li>
                                <br>
                                <li class="font14 d-block">
                                    <span>Total votes: </span>
                                    <span>{{ item.vote_count }}</span>33
                                </li>
                                <br>
                                <li class="font14 d-block">
                                    <span>Popularity: </span>
                                    <span>{{ item.popularity }}</span>
                                </li>
                            </ul>
                            <div class="text-center mt-3 d-md-block">
                                <span class="circle circleBorder text-white font-weight-bold d-inline-flex">{{ item.vote_average }}</span>
                            </div>
                        </div>
                        <img :src="`https://image.tmdb.org/t/p/w300_and_h450_bestv2/${item?.content?.poster_path}`" class="img-fluid img-zoom">
                    </div>
                </router-link>
                <rating :rating="item.vote_average" :max-rating="10" />
                <h2 class="font15 text-center text-white text-bold">{{item?.content?.title}}</h2>
            </div>
            <Pagination class="ps-5" v-model="currentPage" :total-pages="100" style="background-color: unset !important;"></Pagination>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import rating from './rating.vue'
import { Pagination } from 'flowbite-vue'
import { useStore } from 'vuex';
import { mapGetters } from 'vuex';

export default {
    name: 'movies',
    components: {
        axios,
        rating,
        useStore,
        mapGetters,
        Pagination},

    data() {
        return {
            movies:[],
            currentPage : 1,
            perPage: 20,
            isHovered: false
        }
    },

    computed: {
        ...mapGetters(['getLang']),
    },

    mounted() {
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
                const response = await axios.get(`/api/movies?page=${this.currentPage}&lang=${this.getLang}`);
                this.movies = response.data.data;

            } catch (error) {
                console.error('Error fetching movies:', error);
            }
        },
        nextPage() {
            this.currentPage++;
            this.fetchMovies();
        },

    }
}

</script>

<style>

ul  {
    display: flex !important;
}

.border {
    border: unset !important;
}

.font17 {
    font-size: 17px;
}

.font15 {
    font-size: 15px;
}

.hidden {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.hidden:hover {
    opacity: 1;
    transition: opacity 0.3s ease;
}

.pr-layer {
    background-color: rgba(33,33,33,.80);
    position: absolute;
    width: 100%;
    height: 100%;
    padding: 20px;
    z-index: 1;
}

.img-zoom {
  transition: transform 0.5s ease;
}

.hidden:hover .img-zoom {
  transform: scale(1.5) !important;
  transition: transform 0.5s ease;
}

.movie-layer:hover .img-zoom {
  transform: scale(1.5);
  transition: transform 0.5s ease;
}

.text-bold {
    font-weight: 800;
}

.star {
    font-size: 22px !important;
}

ul > li > button {
    background-color: unset !important;
    color: #8c6847 !important;
}


ul > li > button:hover {
    background-color: #fe7900 !important;
}

.circleBorder{
    border-color: white;
    border-width: 0.5px;
    border-style: solid;
}

</style>
