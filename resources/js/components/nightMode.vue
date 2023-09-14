<template>
    <button class="themeButton" @click="toggleNightMode"></button>
</template>
  
<script>
  export default {
    name: 'NightMode',

    methods: {
      toggleNightMode() {
        const rootElement = document.documentElement; 
        rootElement.classList.toggle('night-mode'); 
      },
    },
  };
</script>
  
<style>

.themeButton {
   border-radius: 50% !important;
   background-color: #fe7900;
   height: 20px;
   width: 20px;
}

.night-mode body{
  background-color: #fff !important;
  color: #000;
}

</style>