<template>
    <div>
        <!-- <h1>Hello from Vue</h1> -->
        <button @click="addToCart">Another fucking button </button>
    </div>
</template>

<script>
export default {
    methods: {
        addToCart() {
            console.log('Item added to cart.');
        },
    },
};
</script>
