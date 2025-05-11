// stores/cart.js
import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
  state: () => ({
    cart: JSON.parse(sessionStorage.getItem('cart')) || []
  }),
  actions: {
    addToCart(item) {
      const existingItem = this.cart.find(cartItem => cartItem.id === item.id && cartItem.name === item.name);
      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        this.cart.push({ ...item });
      }
      sessionStorage.setItem('cart', JSON.stringify(this.cart));
      alert(`${item.name} added to cart!`);
    },
    clearCart() {
      this.cart = [];
      sessionStorage.removeItem('cart');
    }
  }
});