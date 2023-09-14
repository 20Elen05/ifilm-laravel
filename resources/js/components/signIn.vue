<template>
    <form ction="/signIn" method="post" @submit.prevent="submitForm">
        <img class="width300"  src="@/assets/light-mode-logo.png" />
        <label>
            <p class="label-txt">USERNAME</p>
            <input type="text" class="input" name="username" v-model="inputValue.username">
            <div class="line-box">
                <div class="line"></div>
            </div>
            <span class="error-message">{{ usernameError }}</span>
        </label>

        <label>
            <p class="label-txt">PASSWORD</p>
            <input type="password" class="input text-black" name="password" v-model="inputValue.password">
            <div class="line-box">
                <div class="line"></div>
            </div>
            <span class="error-message">{{ passwordError }}</span>
        </label>
        <span class="error-message">{{ signinError }}</span>
        <p class="text-dark mt-4">Haven't registered yet?
            <strong>
                <router-link :to="{path : '/'}" class="text-dark">Sign Up!</router-link>
            </strong>
        </p>

        <button type="submit">submit</button>
    </form>
</template>

<script>
import axios from 'axios';
export default {
    name: 'signIn',
    components:{

    },
    data() {
        return {
            inputValue: {
                username: '',
                password: ''
            },
            usernameError: '',
            passwordError: '',

            signinError: '',
            validationErrors: {},
        }
    },

    watch: {
        'inputValue.username': function (val) {
            if (val.length < 3) {
                this.usernameError = 'The First Name should have at least 3 characters'
            } else if(val.length > 10){
                this.usernameError = 'The first name can have maximum 10 characters'
            } else {
                this.usernameError = ''
            }
        },

        'inputValue.password': function (val) {
            if (val.length < 8) {
                this.passwordError = 'The password should have at least 3 characters'
            } else if(val.length > 16){
                this.passwordError = 'The password can have maximum 10 characters'
            } else {
                this.passwordError = ''
            }
        }
    },

    methods: {
        onFocus() {
            this.$nextTick(() => {
                this.$refs.label.classList.add('label-active');
            });
        },
        onBlur() {
            if (!this.inputValue.trim()) {
                this.$nextTick(() => {
                    this.$refs.label.classList.remove('label-active');
                });
            }
        },

        async submitForm() {
            const formData = {
                username: this.inputValue.username,
                password: this.inputValue.password,
            };

            axios.post('api/signIn', formData)
                .then(response => {
                    if (response.data.message === 'error') {
                        this.signinError = "Invalid username or password"
                    } else if (response.data.message === 'Success') {
                        const userId = response.data.user_id;
                        localStorage.setItem('userId', userId);
                        localStorage.setItem('token', response.data.token);
                        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
                        this.$router.push({ name: 'standart' });
                    }
                })
                .catch(error => {
                    if (error.response && error.response.data && error.response.data.errors) {
                        this.validationErrors = error.response.data.errors;

                        if (this.validationErrors.username) {
                            this.usernameError = this.validationErrors.username[0]
                        }
                        if (this.validationErrors.password) {
                            this.passwordError = this.validationErrors.password[0]
                        } else {
                            this.validationErrors = {};
                        }
                    }
                });
        }
    }
};
</script>

<style scoped>
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
    width: 40%;
    margin: 60px auto;
    background: #efefef;
    padding: 60px 120px 80px 120px;
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

.input:focus+.line-box .line {
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
