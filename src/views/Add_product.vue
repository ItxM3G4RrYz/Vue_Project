<template>
  <div class="container mt-4 col-md-4 bg-body-secondary ">
    <h2 class="text-center mb-3">Add Product</h2>
    <form @submit.prevent="addProduct">
      <div class="mb-2">
        <input v-model="product.product_name" class="form-control" placeholder="ProductName" required />
      </div>
      <div class="mb-2">
        <input v-model="product.description" class="form-control" placeholder="Details" required />
      </div>
      <div class="mb-2">
        <input  v-model="product.price" class="form-control" placeholder="Price" required />
      </div>
      <div class="mb-2">
        <input v-model="product.stock" class="form-control" placeholder="Summary" required />
      </div>
      <div class="mb-2">
     <!-- ใช้ @change อ่านไฟล์ -->
        <input type="file" @change="onFileChange" ref="fileInput" required />
      </div>
      
      <div class="text-center mt-4 ">
      <button type="submit" class="btn btn-primary mb-4">Save</button> &nbsp;
      <button type="reset" class="btn btn-secondary mb-4">Cancel</button>
      </div>
    </form>

    <div v-if="message" class="alert alert-info mt-3">
      {{ message }}
    </div>
  </div>
</template>


<script>
export default {
  data() {
    return {
      product: {
        product_id: null,
        product_name: "",
        description: "",
        price: "",
        stock: "",
        image: null, // เก็บไฟล์ object
      },
      message: "",
    };
  },
  methods: {
    onFileChange(e) {
      this.product.image = e.target.files[0];
    },
    async addProduct() {
      try {
        // ใช้ FormData สำหรับส่ง text + file
        const formData = new FormData();
        formData.append("product_name", this.product.product_name);
        formData.append("description", this.product.description);
        formData.append("price", this.product.price);
        formData.append("stock", this.product.stock);
        formData.append("image", this.product.image);

        const res = await fetch("http://localhost/67713669/api_php/add_product.php", {
          method: "POST",
          body: formData, // ❌ ห้ามใส่ Content-Type เดี๋ยว browser จะจัดการเอง
        });

        const data = await res.json();
        this.message = data.message;

        if (data.success) {
          // ✅ เคลียร์ข้อมูล
          this.product = { product_name: "", description: "", price: "", stock: "", image: null };
          this.$refs.fileInput.value = "";
        }
      } catch (err) {
        this.message = "เกิดข้อผิดพลาด: " + err.message;
      }
    },
  },
};
</script>