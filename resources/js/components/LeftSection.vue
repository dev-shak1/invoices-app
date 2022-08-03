<script setup>
import MenuLogo from './MenuLogo.vue'
const isIconMoon = true;
</script>

<template>
    <div>
    <div class="nav-bar">
        <MenuLogo/>
        <div class="bottom-container">
            <!-- <svg v-if="isIconMoon" src="../assets/icon-moon.svg"></svg>
            <svg v-else src="../assets/icon-sun.svg"></svg>
 <b-avatar src="../assets/image-avatar.jpg"></b-avatar> -->
        </div>
    </div>
    </div>
</template>

<style scoped>
.nav-bar{
    height: 100%;
    background-color: #252945;
    width: 10vh;
    border-bottom-right-radius: 20px;
    border-top-right-radius: 20px;
    display: flex;

}
.bottom-container{
    background-color: aqua;
    margin-top: auto;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
</style>