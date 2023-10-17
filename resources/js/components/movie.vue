<template>
    <navbar @click="handleClick"></navbar>
    <navpanel @click="handleClick"></navpanel>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="row">
                    <div class="col-8 col-md-4">
                        <img class="img-responsive" :src="`https://image.tmdb.org/t/p/w300_and_h450_bestv2/${movie?.content?.poster_path}`">
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="list-group size14">
                            <div class="list-group-item pr-layer-widget text-light">
                                <span >Title: </span>
                                <strong> {{movie?.content?.title }}</strong>
                            </div>
                            <div class="list-group-item bg-transparent text-white">
                                <span>Original name: </span>
                                <strong>{{ movie.original_title }}</strong>
                            </div>
                            <div class="list-group-item pr-layer-widget text-white">
                                <span>Year: </span>
                                <strong>{{ movie.release_date }}</strong>
                            </div>
                            <div class="list-group-item bg-transparent text-white">
                                <span> Genre: </span>
                                <strong> {{genreNamesString}}</strong>
                            </div>
                            <div class="list-group-item pr-layer-widget text-white">
                                <span>Duration: </span>
                                <strong>{{ movie.runtime }}m.</strong>
                            </div>
                            <div class="list-group-item bg-transparent text-white">
                                <span>Country: </span>
                                <strong> {{movie.production_countries }}, </strong>
                            </div>
                            <div class="list-group-item pr-layer-widget text-white">
                                <span>Budget: </span>
                                <strong>{{ movie.budget }}$</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="pr-layer-widget p-3">
                            <h5>About film</h5>
                            <p class="font-light">{{ movie?.content?.overview }}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 mt-2">
                        <div class="stars d-sm-inline-block">
                            <rating
                                :rating="movie.vote_average"
                                :max-rating="10"
                                @rating-updated="updateMovieRating"/>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 mt-2">
                        <button class="like-button mt-2"  @click="toggleLikeMovie">
                            <i class="fa fa-heart" :class="{ isLiked }"></i>
                        </button>

                        <p class="font20 text-right">Movie Rating: <strong>{{ movie.vote_average }} </strong></p>
                        <p class="m-0 text-right">Total votes: {{ movie.vote_count }}</p>
                    </div>
                    <div class="col-12 mt-2 overflow-hidden" style="min-height: 370px;">
                        <video style="border-style: solid; border-color: #fe7900;" class="w-100"></video>
                    </div>
                    <div class="col-12 mt-3 text-center">
                        <section class="position-relative text-white top-banner pr-layer-widget">
                            <h5 class="text-start pt-3 ps-3 pr-3">Similar Movies</h5>
                            <Carousel v-bind="settings" :breakpoints="breakpoints">
                                <Slide v-for="item in similarMovies?.results" :key="item" class="mt-4 pb-4 pt-0">
                                    <router-link :to="{ name:'movie', params:{ id: item.id }}" class="filmSim m-1 ms-2 text-light ps-3">
                                        <img :src="`https://image.tmdb.org/t/p/w300_and_h450_bestv2/${item.poster_path}`" class="img-fluid mb-2">
                                        <h2 class="size14 p-1">{{item.title}}</h2>
                                    </router-link>
                                </Slide>
                                <template #addons>
                                    <Navigation />
                                </template>
                            </Carousel>
                        </section>
                    </div>
                </div>
                <div class="mt-4">
                    <form @submit.prevent="submitComment()" class="d-flex">
                        <textarea v-model="newComment" rows="2" placeholder="Write a comment"></textarea>
                        <button type="submit" class="comBtn m-1 ms-4">Submit</button>
                    </form>
                </div>
                <div class="col-12 mt-4">
                    <h5>Comments</h5>

                    <div class="bg-grey com" v-for="comment in comments" :key="comment.id">
                        <p class="comUser mb-1">{{ comment.user.first_name}}</p>
                        <button class="like-button mt-2 comLike"  @click="toggleLikeComment(comment)">
                            <i class="fa fa-thumbs-up me-2" :class="{ 'comLiked': comment.comLiked, 'comDisliked': !comment.comLiked }" ></i>
                            <p class="m-0">{{comment.likes_count}}</p>
                        </button>
                        <p>{{ comment.content }}</p>
                        <p class="date">{{ formatDate(comment.created_at) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 mt-5 mt-md-0">
                <topMovies @click="handleClick"></topMovies>
            </div>
        </div>
    </div>
    <foooter></foooter>
</template>
<script>
import axios from 'axios';
import { useStore } from 'vuex';
import { mapGetters } from 'vuex';
import navbar from './navbar.vue'
import navpanel from './navpanel.vue'
import topMovies from './topMovies.vue'
import rating from './rating.vue'
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
import foooter from './footer.vue'
import dayjs from 'dayjs';
import 'font-awesome/css/font-awesome.min.css';


export default {
    name : 'movie',

    components : {
        navbar,
        navpanel,
        topMovies,
        Carousel,
        useStore,
        mapGetters,
        rating,
        Slide,
        Navigation,
        foooter,
        dayjs,
    },

    data() {
        return {
            movie : [],
            similarMovies : [],
            newComment: '',
            comments: [],
            genreNamesString: '',
            dateOnly: "",
            users:'',
            isLiked: false,
            comLiked: false,
        }
    },

    settings: {
        itemsToShow: 4,
        snapAlign: 'center',
    },

    breakpoints : {
        700: {
            itemsToShow: 3,
            snapAlign: 'center',
        },
        1024: {
            itemsToShow: 5,
            snapAlign: 'start',
        },
    },

    computed: {
        ...mapGetters(['getLang']),
    },

    watch: {
        $route() {
            this.fetchData()
        },

        getLang() {
            this.fetchData();
        },
    },

    methods: {
        async fetchData() {
            try {
                const movieId = this.$route.params.id;
                const movieResponse = await axios.get(`/api/movie/${movieId}?lang=${this.getLang}`);
                this.movie = movieResponse.data.movie;

                const genres = this.movie.genres;
                console.log(genres)
                const genreNames = genres.map(genre => genre.genre_name);
                this.genreNamesString = genreNames.join(', ');

                const apiKey = 'a348e7136197bd5186dd097b93931f79';
                const lang = this.getLang;

                const similarMoviesResponse = await axios.get(`https://api.themoviedb.org/3/movie/${movieId}/similar?api_key=${apiKey}&language=${lang}`);
                this.similarMovies = similarMoviesResponse.data;

                console.log(this.similarMovies.results[0].title);
            } catch (error) {
                console.error(error);
            }
        },

        async submitComment() {
            try {
                const movieId = this.$route.params.id;

                if (!this.newComment) {
                    return;
                }

                const commentData = {
                    content: this.newComment,
                };

                const response = await axios.post(`/api/movie/${movieId}/comments`, commentData);

                this.newComment = '';

                this.fetchComments();
            } catch (error) {
                console.error(error);
            }
        },

        async fetchComments() {
            try {
                const movieId = this.$route.params.id;

                const response = await axios.get(`/api/movie/${movieId}/comments`);
                this.comments = response.data;

                const userId = parseInt(localStorage.getItem('userId'));

                this.comments.forEach(comment => {
                    const liked = comment.likes.some(like => like.user_id === userId);
                    comment.comLiked = liked;
                });

                console.log('HERE', this.comments);
            } catch (error) {
                console.error(error);
            }
        },

        formatDate(timestamp) {
            const date = dayjs(timestamp);
            return date.format("YYYY-MM-DD");
        },

        async toggleLikeMovie() {
            try {
                const movieId = this.$route.params.id;

                const response = await axios.post(`/api/movies/${movieId}/like`);

                this.fetchComments();

                if (response.data.message === 'Movie liked successfully') {
                    this.isLiked = true;
                } else if (response.data.message === 'Movie is unliked') {
                    this.isLiked = false;
                }

                console.log(this.isLiked);
            } catch (error) {
                console.error('Error toggling like for movie:', error);
            }
        },

        async checkMovieLikeStatus() {
            try {
                const movieId = this.$route.params.id;
                console.log(movieId);

                const response = await axios.get(`/api/movies/${movieId}/check-like-status`);

                if (response.data.isLiked) {
                    this.isLiked = true;
                } else {
                    this.isLiked = false;
                }

                console.log(this.isLiked);
            } catch (error) {
                console.error('Error checking like status for movie:', error);
            }
        },

        async toggleLikeComment(comment) {
            try {
                const response = await axios.post(`/api/comments/${comment.id}/like`);

                if (response.data.message === 'Comment liked successfully') {
                    comment.comLiked = true;
                } else if (response.data.message === 'Com is unliked') {
                    comment.comLiked = false;
                }

                this.fetchComments();

                console.log(comment.comLiked);
            } catch (error) {
                console.error('Error toggling like for comment:', error);
            }
        },

        handleClick() {
            this.fetchComments();
            this.checkMovieLikeStatus();
        },

        async updateMovieRating(rating) {
            const ratingData = {
                movie_id: this.movie.movie_id,
                rating: rating,
            };

            try {
                const response = await axios.post('/api/rate-movie', ratingData);
                console.log(response);

                if (response.data.message === 'rated successfully') {
                    this.fetchData();
                }
            } catch (error) {
                console.error('Error submitting rating:', error);
            }
        },
    },

    mounted(){
        this.fetchData();
        this.fetchComments();
        this.checkMovieLikeStatus();
    },

}
</script>

<style>

.comBtn{
    border-style: solid;
    border-width: 1px;
    background-color: #fe7900;
    border-radius: 20px;
    padding: 10px;
}

.comBtn:hover {
    background-color: #ffa459;
}

.com {
    padding: 10px;
    margin-bottom: 20px;
    position: relative;
}

.comUser{
    font-weight: bold;
}

.date {
    position: absolute;
    margin: 0 !important;
    bottom: 0;
    right: 0;
    font-size: 14px;
}

.comLike {
    position: absolute;
    margin: 0 !important;
    top: 0;
    right: 0;
    font-size: 22px !important;
}

.stars > div > .star-rating > span {
    font-size: 44px !important;
}

.img-responsive {
    width: 100%;
    height: auto;
}

textarea {
    width: 600px;
}

textarea::placeholder {
    color: black !important;
    font-size: 18px;
}

.list-group {
    display: flex;
    flex-direction: column;
    padding-left: 0;
    margin-bottom: 0;
}

.list-group-item {
    position: relative;
    display: block;
    padding: 0.75rem 1.25rem;
    margin-bottom: -1px;
    background-color: #fff;
    border: 1px solid rgba(0,0,0,.125);
}

.size14 {
    font-size: 14px;
}

.pr-layer-widget {
    background-color: #242526 !important;
}

.list-group-item > span {
    font-family: MonserratLight;
}

.font20 {
    font-size: 20px;
}

.text-right {
    text-align: right!important;
}

.w-100 {
    width: 100%;
}

.filmSim {
    width: 166px;
    height: 240px;
}

ul > li  {
    background-color: unset !important;
}

ul > li > button:hover {
    background-color: #fe7900 !important;
}

.carousel__slide {
    flex-shrink: inherit;
}

.font60 {
    font-size: 60px;
}

.w-30 {
    width: 28% !important;
}

.like-button {
    border: none;
    background: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    font-size: 40px;
    color: #d8d8d8;
}


.isLiked {
    color: #fe7900;
}

.comLiked {
    color: #fe7900;
}

</style>
