<script setup>
import LogoIcon from './icons/logo.vue'
</script>

<template>
<div>
<div class="menu-logo-container">
<LogoIcon class="logo-icon"/>
<div class="menu-outer-container"></div>
<div class="menu-inner-container">

</div>
</div>

</div>


</template>

<style scoped>
.menu-logo-container{
    width: 10vh;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;

}
.menu-inner-container{
    display: block;
    position: absolute;
    height: 50px;
    left: 0;
    top:50px;
    background-color: #9277FF;
    width: inherit;
    z-index: 2;
    border-top-left-radius: 20px;
    border-bottom-right-radius: 20px;

}
.menu-outer-container{
    background-color: #7C5DFA;
    width: inherit;
    height: inherit;
    z-index: 1;
  
    position: absolute;
    border-bottom-right-radius: 20px;
    border-top-right-radius: 20px;
}

</style>