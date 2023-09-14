<template>
    <Carousel v-bind="settings" :breakpoints="breakpoints">
    <Slide v-for="item in movies" :key="item.id" class="mt-4 pb-4 pt-0">
        <router-link :to="{ name:'movie', params:{ id: item.id }}" class="filmNvp m-1 text-light ps-4" @click="getId(`${item.id }`)">
            <img :src="`https://image.tmdb.org/t/p/w300_and_h450_bestv2/${item.poster_path}`" class="carousel__item img-fluid">
            <h2 class="font13 p-1 text-center mt-2">{{item.title}}</h2>
        </router-link>
    </Slide>

    <template #addons>
        <Navigation />
    </template>
  </Carousel>

</template>

<script>
import 'bootstrap/dist/css/bootstrap.css'
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
import { useStore } from 'vuex';
import { mapGetters } from 'vuex';
import axios from 'axios';
export default{
    name: 'navpanel',

    components: {
        Carousel,
        Slide,
        useStore,
        mapGetters,
        Navigation},

    data: () => ({

        return : {
            movies:[],
            id : Number,
        },

        settings: {
            itemsToShow: 8,
            snapAlign: 'center',
        },

        breakpoints: {
            200: {
                itemsToShow: 3,
                snapAlign: 'center',
            },
            1024: {
                itemsToShow: 5,
                snapAlign: 'start',
            },
        },
    }),

    computed: {
        ...mapGetters(['getLang']),
    },

    mounted() {
        this.fetchDatas();
    },


    watch: {
        getLang() {
            this.fetchDatas();
        },
    },

    methods: {
        fetchDatas() {
            axios.get(`/api/navpanelMovies?`)
                .then(response => {
                    this.movies = response.data.data;
                    console.log(this.movies)
                })
                .catch(error => {
                    console.error('Error fetching movies:', error);
                });
        },

        getId(id){
            this.id = id
            console.log(id)
        },
        showNext() {
            this.$refs.carousel.next()
       },
    }
}
</script>

<style>

.filmNvp{
    width: 180px !important;
    height: 260px !important;
}

.font13 {
    font-size: 13px;
}

.font14{
    font-size: 14px;
}

.carousel__prev, .carousel__next{
    color: white;
    width: 40px;
}

</style>
