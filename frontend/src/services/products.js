// src/service/product.js

export default {
  async getProductsFromBackend() {
    try {
      const res = await fetch('http://localhost:8080/user/get/products');
      if (!res.ok) throw new Error('Failed to fetch products');
      const data = await res.json();

      // 验证数据是否是数组
      if (!Array.isArray(data)) throw new Error('Invalid format');
      return data;
    } catch (err) {
      console.error('Fetch error:', err);
      return [];
    }
  }
}
