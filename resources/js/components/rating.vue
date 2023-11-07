<template>
    <div>
        <div class="star-rating">
         <span
             v-for="star in maxRating"
             :key="star"
             class="star"
             :class="{ filled: star <= rating || star <= tempRating }"
             @mouseover="tempRating = star"
             @mouseleave="tempRating = 0"
             @click="handleRatingClick(star)"
         >
        &#9733;
      </span>
        </div>
    </div>
</template>

<script>
import {computed} from 'vue';

export default {
    name: 'rating',
    data() {
        return {
            tempRating: 0,
        };
    },

    props: {
        rating: {
            type: Number,
            required: true,
        },

        maxRating: {
            type: Number,
            default: 10,
        },
    },

    setup(props) {
        const filledStars = computed(() => {
            const filled = Math.floor(props.rating);
            const halfFilled = Math.ceil(props.rating) - filled;
            const empty = props.maxRating - filled - halfFilled;

            return {
                filled,
                halfFilled,
                empty,
            };
        });

        return {
            filledStars,
        };
    },

    methods: {
        handleRatingClick(rating) {
            console.log(`User rated ${this.movieTitle} with ${rating} stars`);
            this.$emit('rating-updated', rating); // Emit the rating to the parent component
        },
    },
};
</script>

<style>
.star {
    cursor: pointer;
    color: #d8d8d8;
}

.filled {
    color: #fe7900;
}
</style>
