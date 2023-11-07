<template>
    <form action="/signUp" method="post" @submit.prevent="submitForm">
        <img class="width300" src="@/assets/light-mode-logo.png"/>

        <label :class="{ 'error': validationErrors.firstName }">
            <p class="label-txt">FIRST NAME</p>
            <input type="text" class="input" name="firstName" v-model="inputValue.firstName">
            <div class="line-box">
                <div class="line"></div>
            </div>
            <span class="error-message">{{ firstNameError }}</span>
        </label>

        <label :class="{ 'error': validationErrors.surname}">
            <p class="label-txt">SURNAME</p>
            <input type="text" class="input" name="surname" v-model="inputValue.surname">
            <div class="line-box">
                <div class="line"></div>
            </div>
            <span class="error-message">{{ surnameError }}</span>
        </label>

        <label :class="{ 'error': validationErrors.username }">
            <p class="label-txt">USERNAME</p>
            <input type="text" class="input" name="username" v-model="inputValue.username">
            <div class="line-box">
                <div class="line"></div>
            </div>
            <span class="error-message">{{ usernameError }}</span>
        </label>

        <label :class="{ 'error': validationErrors.email }">
            <p class="label-txt">EMAIL</p>
            <input type="text" class="input" name="email" v-model="inputValue.email">
            <div class="line-box">
                <div class="line"></div>
            </div>
            <span class="error-message">{{ emailError }}</span>
        </label>

        <label :class="{ 'error': validationErrors.password }">
            <p class="label-txt">PASSWORD</p>
            <input type="password" class="input" name="password" v-model="inputValue.password">
            <div class="line-box">
                <div class="line"></div>
            </div>
            <span class="error-message">{{ passwordError }}</span>
        </label>

        <p class="text-dark">Already have an account?
            <strong>
                <router-link :to="{ name: 'signIn' }" class="text-dark">Log In!</router-link>
            </strong>
        </p>
        <button type="submit">submit</button>
    </form>
</template>

<script>
import axios from 'axios';
import 'bootstrap/dist/css/bootstrap.css'

export default {
    name: 'signUp',
    data() {
        return {
            inputValue: {
                firstName: '',
                surname: '',
                username: '',
                email: '',
                password: ''
            },
            firstNameError: '',
            surnameError: '',
            usernameError: '',
            emailError: '',
            passwordError: '',

            validationErrors: {},
        }
    },
    methods: {
        onFocus(labelRef) {
            this.$nextTick(() => {
                this.$refs[labelRef].classList.add('label-active');
            });
        },
        onBlur() {
            if (typeof this.inputValue.surname === 'string' && this.inputValue.surname.trim() === '') {
                this.$nextTick(() => {
                    this.$refs.surnameLabel.classList.remove('label-active');
                });
            }
        },

        async submitForm() {
            try {
                const formData = {
                    firstName: this.inputValue.firstName,
                    surname: this.inputValue.surname,
                    username: this.inputValue.username,
                    email: this.inputValue.email,
                    password: this.inputValue.password,
                };

                const response = await axios.post('/api/signUp', formData);

                console.log("Sign up successful!");

                this.$router.push({name: 'signIn'});
            } catch (error) {
                console.error(error);

                if (error.response && error.response.data && error.response.data.errors) {
                    this.validationErrors = error.response.data.errors;

                    if (this.validationErrors.firstName) {
                        this.firstNameError = this.validationErrors.firstName[0];
                    }
                    if (this.validationErrors.surname) {
                        this.surnameError = this.validationErrors.surname[0];
                    }
                    if (this.validationErrors.username) {
                        this.usernameError = this.validationErrors.username[0];
                    }
                    if (this.validationErrors.email) {
                        this.emailError = this.validationErrors.email[0];
                    }
                    if (this.validationErrors.password) {
                        this.passwordError = this.validationErrors.password[0];
                    }
                } else {
                    this.validationErrors = {};
                }
            }
        },
    },
    watch: {
        'inputValue.firstName': function (val) {
            if (val.length < 3) {
                this.firstNameError = 'The First Name should have at least 3 characters'
            } else if (val.length > 10) {
                this.firstNameError = 'The first name can have maximum 10 characters'
            } else {
                this.firstNameError = ''
            }
        },

        'inputValue.surname': function (val) {
            if (val.length < 3) {
                this.surnameError = 'The surname should have at least 3 characters'
            } else if (val.length > 16) {
                this.surnameError = 'The surname can have maximum 10 characters'
            } else {
                this.surnameError = ''
            }
        },

        'inputValue.username': function (val) {
            if (val.length < 6) {
                this.usernameError = 'The username should have at least 6 characters'
            } else if (val.length > 12) {
                this.usernameError = 'The username can have maximum 12 characters'
            } else {
                this.usernameError = ''
            }
        },

        'inputValue.password': function (val) {
            if (val.length < 8) {
                this.passwordError = 'The password should have at least 8 characters'
            } else if (val.length > 16) {
                this.passwordError = 'The password can have maximum 16 characters'
            } else {
                this.passwordError = ''
            }
        },
    },
    computed: {}
};
</script>

<style scoped>
.error {
    border-color: red;
}

.error-message {
    color: red;
    font-size: 18px;
    margin-top: 5px;
    display: block;
}

body {
    background: #C5E1A5;
}

form {
    width: 50%;
    margin: 60px auto;
    background: #efefef;
    padding: 40px 120px 50px 120px;
    text-align: center;
    -webkit-box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.1);
    box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.1);
}

label {
    display: block;
    position: relative;
    margin: 40px 0px;
}

.label-txt {
    position: absolute;
    top: -1.6em;
    padding: 10px;
    font-family: sans-serif;
    font-size: .8em;
    letter-spacing: 1px;
    color: rgb(120, 120, 120);
    transition: ease .3s;
}

.input {
    width: 100%;
    padding-top: 30px;
    background: transparent;
    border: none;
    outline: none;
    color: black !important;
}

.line-box {
    position: relative;
    width: 100%;
    height: 2px;
    background: #BCBCBC;
}

.line {
    position: absolute;
    width: 0%;
    height: 2px;
    top: 0px;
    left: 50%;
    transform: translateX(-50%);
    background: #fe7900;
    transition: ease .6s;
}

.input:focus + .line-box .line {
    width: 100%;
}

.label-active {
    top: -3em;
}

button {
    display: inline-block;
    padding: 12px 24px;
    background: rgb(220, 220, 220);
    font-weight: bold;
    color: rgb(120, 120, 120);
    border: none;
    outline: none;
    border-radius: 3px;
    cursor: pointer;
    transition: ease .3s;
}

button:hover {
    background: #fe7900;
    color: #ffffff;
}

.width300 {
    width: 300px;
}
</style>
