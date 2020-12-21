<template>
    <div class="loading">
        <div class="spinner is-active"></div>
    </div>
</template>

<style scoped>
    .loading {
        position:absolute;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background: rgba(255,255,255, 0.5);
        z-index: 9999;
    }

    .loading .spinner {
        position:absolute;
        top:50%;
        left:50%;
        transform: translate3d(-50%,-50%,0);
    }
</style>
