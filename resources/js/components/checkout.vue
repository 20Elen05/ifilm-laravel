<template>
    <div class="payment-form">
        <div>
            <label for="card-element" class="font20 m-3" style="color: #fe7900 !important;">Credit or debit card</label>
            <div id="card-element" class="my-4"></div>
            <div id="card-errors" role="alert" class="error">{{ errorMessage }}</div>
        </div>
        <div>
            <label for="email">Email</label>
            <input id="email" type="email" v-model="email"/>
        </div>
        <div class="d-flex">
            <router-link :to="{ name:'movie', params:{ id: movieId}}">
                <button>Go back</button>
            </router-link>
            <button @click="processPayment">Pay now!</button>
        </div>
    </div>
</template>

<script>
import {ref, onMounted} from "vue";
import {loadStripe} from "@stripe/stripe-js";
import {useRoute, useRouter} from "vue-router";

export default {
    name: 'checkout',
    setup() {
        const stripe = ref(null);
        const elements = ref(null);
        const card = ref(null);
        const email = ref("");
        const errorMessage = ref('');
        const movieId = ref(0);
        const route = useRoute()
        const router = useRouter();

        onMounted(async () => {
            if (route.query.movie) {
                movieId.value = +route.query.movie
            }

            const stripePromise = loadStripe("pk_test_51O3HhNLnvvEJ81OebslAMIFB4Xm8XwB7arNif2loN2SHzbvIldxG6H58hEO9Oz5UDobXM9FmDHYYakZo0cg8Ah9Q002dcouwds");
            stripe.value = await stripePromise;

            elements.value = stripe.value.elements();

            card.value = elements.value.create("card");

            card.value.mount("#card-element");
        });

        const processPayment = async () => {
            const {token, error} = await stripe.value.createToken(card.value, {
                email: email.value,
            });

            if (error) {
                errorMessage.value = error.message;
            } else {
                const userId = localStorage.getItem('userId');

                const response = await axios.post('/api/payments', {
                    movie_id: movieId.value,
                    user_id: userId,
                    token: token.id,
                    email: email.value,
                });
                console.log(response);

                if (response.status === 200) {
                    router.push(`/movie/${movieId.value}`)
                } else {
                    console.error('Payment failed. Unable to save payment data.');
                }
            }
        };
        return {
            processPayment,
            email,
            errorMessage,
            movieId,
        };
    },
};

</script>

<style>
.payment-form {
    max-width: 500px;
    margin-top: 200px;
    position: relative;
    left: 30%;
    padding: 20px;
    background-color: #f5f5f5;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
}

label {
    font-weight: bold;
}

input {
    width: 100%;
    height: 40px;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 3px;
}

#email {
    background-color: #fff;
}

.error {
    color: #f00;
    margin-top: 10px;
}

button {
    background-color: #fe7900;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin: 10px;
}

button:hover {
    background-color: #ff8c2f;
}
</style>


