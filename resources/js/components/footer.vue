<template>
    <footer class="footer-bg mt-5 p-3" style="">
        <video class="footerVd" width="750" height="400" controls>
            <!-- <source :src="require('@/assets/V.mp4')"> --></video>
        <h6 class="bottomText font-light size-14 position-absolute text-white ">Copyright © 2019 iFilm.com. All Rights
            Reserved.</h6>
    </footer>
</template>

<script>
export default {
    name: 'foooter'
}
</script>

<style>
.footer-bg {
    min-height: 650px;
    background-position: 50%;
    background-size: cover;
    background-attachment: fixed;
    transition: all .5s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

footer {
    background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('../assets/footer.jpg');
}

.footerVd {
    position: absolute;
    top: 19%;
    left: 25%;
}

.bottomText {
    top: 94% !important;
    left: 36% !important;
    font-weight: 400
}
</style>
