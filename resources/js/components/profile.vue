<template>
    <navbar></navbar>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card">
            <div class="upper">
                <img src="../assets/light-mode-logo.png"  class="img-fluid">
            </div>
            <div class="user text-center">
                <div class="profile">
                    <img src="../assets/profile-user.png"  class="rounded-circle" width="80">
                </div>
            </div>

            <div class="mt-5 text-center">
                <h4 class="mb-0">{{ firstName }} {{surname}} </h4>
                <span class="text-muted d-block mb-2">{{ username }}</span>
            </div>
            <div class="d-flex justify-content-center mt-2">
                <button @click="logout">Log Out</button>
                <button @click="showDeleteConfirmation">Delete account</button>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <h2 class="text-center mt-3">Favorite Movies</h2>
            <div class="col-12 col-md-6" v-for="movie in likedMovies">
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
    </div>
    <foooter></foooter>
</template>

<script>
import navbar from './navbar.vue'
import foooter from './footer.vue'
import axios from 'axios';
import { useStore } from 'vuex';
import { mapGetters , mapActions} from 'vuex';
export default {
    name: 'profile',

    data() {
        return{
            firstName:'',
            surname: '',
            username:'',
            userId: '',
            likedMoviesId: [],
            likedMovies: [],
        }
    },

    components :{
        navbar,
        foooter,
        useStore,
        mapGetters,
    },

    computed: {
        ...mapGetters(['getLang']),
    },

    watch: {
        getLang() {
            this.getUser();
        },
    },

    methods: {
        async getUser() {
            try {
                const userResponse = await axios.get(`/api/user/${this.userId}`);

                this.user = userResponse.data.user;
                this.firstName = this.user.first_name;
                this.surname = this.user.surname;
                this.username = this.user.username;

                const likedMoviesIds = userResponse.data.likedMovies.map(movie => movie.likeable_id);

                const likedMoviesResponse = await axios.get(`/api/movies/liked?lang=${this.getLang}`, {
                    params: {
                        likedMovieIds: likedMoviesIds,
                    },
                });

                this.likedMovies = likedMoviesResponse.data.movies;

                console.log(this.likedMovies);
            } catch (error) {
                console.error('Error fetching user data:', error);
            }
        },

        async logout() {
            try {
                await axios.post('/api/logout');

                localStorage.removeItem('userId');
                localStorage.removeItem('token');

                axios.defaults.headers.common['Authorization'] = '';

                this.$router.push({ name: 'signIn' });
            } catch (error) {
                console.error('Error logging out:', error);
            }
        },

        async deleteAccount() {
            try {
                const userId = localStorage.getItem('userId');

                await axios.delete(`/api/users/${userId}`);

                localStorage.removeItem('userId');
                localStorage.removeItem('token');

                axios.defaults.headers.common['Authorization'] = '';

                this.$router.push('/');
            } catch (error) {
                console.error('Error deleting account:', error);
            }
        },

        showDeleteConfirmation() {
            const confirmed = window.confirm('Are you sure you want to delete your account?');
            if (confirmed) {
                this.deleteAccount();
            }
        },
    },

     mounted() {
         this.userId = localStorage.getItem('userId');
         console.log(this.userId);
         this.getUser();
    },
}

</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Poppins:weight@100;200;300;400;500;600;700;800&display=swap");
body{
    background-color:#545454;
    font-family: "Poppins", sans-serif;
    font-weight: 300;
}

button {
    border: red;
    border-style: solid;
    border-width: 1px;
    background-color: #c76b6b;
    border-radius: 20px;
    padding: 10px;
}

button:hover {
    background-color: darkred;
}
.container{
    height: auto;
}
.card{
    width: 380px;
    border: none;
    border-radius: 15px;
    padding: 8px;
    background-color: #fff;
    position: relative;
    height: 300px;
    margin-top: 100px;
}
.upper{
    height: 100px;
}
.upper img{
    width: 100%;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}
.user{
    position: relative;
}
.profile img{
    height: 80px;
    width: 80px;
    margin-top:2px;
}
.profile{
    position: absolute;
    top:-50px;
    left: 38%;
    height: 90px;
    width: 90px;
    border:3px solid #000;
    border-radius: 50%;
}
.stats span{
    font-size: 29px;
}
</style>
