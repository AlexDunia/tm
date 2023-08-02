<template>
    <div>
        <!-- <h1>Hello from Vue</h1> -->
        <button @click="addToCart">Add to cart</button>
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
