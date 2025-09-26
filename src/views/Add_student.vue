<template>
  <div class="container mt-4 col-md-4 bg-body-secondary ">
    <h2 class="text-center mb-3">Sign In</h2>
    <form @submit.prevent="addStudent">
      <div class="mb-2">
        <input v-model="student.first_name" class="form-control" placeholder="Firstname" required />
      </div>
      <div class="mb-2">
        <input v-model="student.last_name" class="form-control" placeholder="Lastname" required />
      </div>
            <div class="mb-2">
        <input v-model="student.email" class="form-control" placeholder="Email" required />
      </div>
      <div class="mb-2">
        <input v-model="student.phone" class="form-control" placeholder="Phone" required />
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
      student: {
        first_name: "",
        last_name: "",
        email: "",
        phone: ""
      },
      message: ""
    };
  },
  methods: {
    async addStudent() {
      try {
        const res = await fetch("http://localhost/67713669/api_php/add_student.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(this.student)
        });
        const data = await res.json();
        this.message = data.message;

        if (data.success) {
          // ✅ เคลียร์ข้อมูลใน textbox หลังบันทึกสำเร็จ
          this.student = { first_name: "", last_name: "", email: "", phone: ""};
        }

      } catch (err) {
        this.message = "Something went wrong: " + err.message;
      }
    }
  }
}
</script>
