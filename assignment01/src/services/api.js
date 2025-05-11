// src/services/api.js
export default {
  // 获取产品数据
  async getProducts() {
    try {
      // 在实际项目中，这里应该是从后端API获取数据
      // const response = await fetch('/api/products');
      // return await response.json();
      
      // 模拟从sessionStorage获取数据
      const productData = JSON.parse(sessionStorage.getItem('productData') || '{}');
      return productData;
    } catch (error) {
      console.error('Error fetching products:', error);
      return {};
    }
  },

  // 获取购物车数据
  async getCart() {
    try {
      const cartData = JSON.parse(sessionStorage.getItem('cart') || '[]');
      return cartData;
    } catch (error) {
      console.error('Error fetching cart:', error);
      return [];
    }
  },

  // 更新购物车
  async updateCart(cart) {
    try {
      sessionStorage.setItem('cart', JSON.stringify(cart));
      return true;
    } catch (error) {
      console.error('Error updating cart:', error);
      return false;
    }
  }
}