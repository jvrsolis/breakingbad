<template>
    <div id="app">
        <!-- <div id="nav">
        <router-link to="/">Home</router-link> |
        <router-link to="/about">About</router-link> |
        <router-link to="/dashboard">Dashboard</router-link>
        </div> -->
        <router-view/>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style src="../../css/tailwind.css">
