<template>
  <div class="container mt-4 col-md-4 bg-body-secondary ">
    <h2 class="text-center mb-3">Sign In</h2>
    <form @submit.prevent="addCustomer">
      <div class="mb-2">
        <input v-model="customer.firstName" class="form-control" placeholder="Firstname" required />
      </div>
      <div class="mb-2">
        <input v-model="customer.lastName" class="form-control" placeholder="Lastname" required />
      </div>
      <div class="mb-2">
        <input v-model="customer.phone" class="form-control" placeholder="Phone" required />
      </div>
      <div class="mb-2">
        <input v-model="customer.username" class="form-control" placeholder="Username" required />
      </div>
      <div class="mb-2">
        <input type="password" v-model="customer.password" class="form-control" placeholder="Password" required />
      </div>
      <div class="text-center mt-4 ">
      <button type="submit" class="btn btn-primary mb-4">Submit</button> &nbsp;
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
      customer: {
        firstName: "",
        lastName: "",
        phone: "",
        username: "",
        password: ""
      },
      message: ""
    };
  },
  methods: {
    async addCustomer() {
      try {
        const res = await fetch("http://localhost/67713669/api_php/add_customer.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(this.customer)
        });
        const data = await res.json();
        this.message = data.message;

        if (data.success) {
          // ✅ เคลียร์ข้อมูลใน textbox หลังบันทึกสำเร็จ
          this.customer = { firstName: "", lastName: "", phone: "", username: "", password: "" };
        }

      } catch (err) {
        this.message = "Something went wrong: " + err.message;
      }
    }
  }
}
</script>
