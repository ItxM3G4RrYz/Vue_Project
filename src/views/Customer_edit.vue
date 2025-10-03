<template>
  <div class="container mt-4">
    <h2 class="mb-3">Customer List</h2>
    
    <div class="mb-3">
      <a class="btn btn-primary" href="/add_customer" role="button">Add+</a>
    </div>

    <!-- ตารางแสดงข้อมูลลูกค้า -->
    <table class="table table-bordered table-striped">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>LastName</th>
          <th>Phone.</th>
          <th>Username</th>
          <th class="text-center">Edit/Delete</th>
          
        </tr>
      </thead>
      <tbody>
        <tr v-for="customer in customers" :key="customer.customer_id">
          <td>{{ customer.customer_id }}</td>
          <td>{{ customer.firstName }}</td>
          <td>{{ customer.lastName }}</td>
          <td>{{ customer.phone }}</td>
          <td>{{ customer.username }}</td>
          <td class="text-center">
            <!-- เพิ่ม ปุ่มแก้ไข -->
            <button class="btn btn-warning btn-sm" @click="openEditModal(customer)"><i class="fa-solid fa-pen-to-square"></i></button>  |      
            <!-- ปุ่มลบ -->
            <button class="btn btn-danger btn-sm" @click="deleteCustomer(customer.customer_id)"><i class="fa-solid fa-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Loading -->
    <div v-if="loading" class="text-center">
      <p>loading...</p>
    </div>

    <!-- Error -->
    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <!-- เพิ่ม Modal แก้ไขข้อมูล -->
    <div class="modal fade" id="editModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Customer</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="updateCustomer">
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input v-model="editCustomer.firstName" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">lastName</label>
                <input v-model="editCustomer.lastName" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Phone</label>
                <input v-model="editCustomer.phone" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input v-model="editCustomer.username" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Password (Blank to bypass this section)</label>
                <input v-model="editCustomer.password" type="password" class="form-control">
              </div>
              <button type="submit" class="btn btn-success">Save</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>


<script>
import { ref, onMounted } from "vue";
import { Modal } from "bootstrap";   // เพิ่ม ✅ import Modal class

export default {
  name: "CustomerList",
  setup() {
    const customers = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const editCustomer = ref({});   //เพิ่ม
    let editModal;                  //เพิ่ม

    const fetchCustomers = async () => {
      try {
        const response = await fetch("http://localhost/67713669/api_php/customer_api.php", {
          method: "GET",
          headers: { "Content-Type": "application/json" }
        });

        if (!response.ok) throw new Error("Cannot fetch data from server");

        const result = await response.json();
        if (result.success) {
          customers.value = result.data;
        } else {
          error.value = result.message;
        }
      } catch (err) {
        error.value = err.message;
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      fetchCustomers();
      const modalEl = document.getElementById("editModal");     //เพิ่ม
      editModal = new Modal(modalEl);   // เพิ่ม ✅ ใช้ Modal ที่ import มา
    });

//เพิ่ม เปิด Popup Modal ***
    const openEditModal = (customer) => {
      editCustomer.value = { ...customer };
      editModal.show();
    };
// เพิ่มฟังก์ชั่นการแก้ไขข้อมูล ***
    const updateCustomer = async () => {
      try {
        const response = await fetch("http://localhost/67713669/api_php/customer_api.php", {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(editCustomer.value)
        });

        const result = await response.json();

        if (result.success) {
          const index = customers.value.findIndex(c => c.customer_id === editCustomer.value.customer_id);
          if (index !== -1) customers.value[index] = { ...editCustomer.value };

          alert("Edit Data Successfully");
          editModal.hide();
        } else {
          alert(result.message);
        }
      } catch (err) {
        alert("Error: " + err.message);
      }
    };

//ฟังก์ชั่นการลบข้อมูล ***
    const deleteCustomer = async (id) => {
      if (!confirm("Do you need to Delete this Data?")) return;

      try {
        const response = await fetch("http://localhost/67713669/api_php/customer_api.php", {
          method: "DELETE",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ customer_id: id })
        });

        const result = await response.json();

        if (result.success) {
          customers.value = customers.value.filter(c => c.customer_id !== id);
          alert(result.message);
        } else {
          alert(result.message);
        }
      } catch (err) {
        alert("Error: " + err.message);
      }
    };

    return {
      customers,
      loading,
      error,
      deleteCustomer,
      
      editCustomer,  //เพิ่ม
      openEditModal,  //เพิ่ม
      updateCustomer  //เพิ่ม
    };
  }
};
</script>
