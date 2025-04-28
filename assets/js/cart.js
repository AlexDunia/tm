addToCartFromModal(productId, quantity = 1) {
    const product = this.products.find(p => p.id === productId);
    if (!product) {
        console.error('Product not found');
        return;
    }

    const existingItem = this.cart.find(item => item.id === productId);
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        this.cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            quantity: quantity,
            image: product.image
        });
    }

    this.saveCart();
    this.updateCartCount();
    this.updateCartTotal();
    this.updateCartItems();
    this.updateCartModal();
}
