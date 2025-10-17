<template>
  <div class="container mt-4">
    <h2 class="mb-3">Customer List</h2>

    <div class="mb-3">
      <button class="btn btn-primary" @click="openAddModal">
        Add+ <i class="bi bi-plus-circle"></i>
      </button>
    </div>

    <table class="table table-bordered table-striped">
      <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>LastName</th>
          <th>Phone.</th>
          <th>Username</th>
          <th class="text-center">Edit/Delete

          </th>

        </tr>
      </thead>
      <tbody>
        <tr v-for="customer in customers" :key="customer.customer_id">
          <td>{{ customer.customer_id }}</td>
          <td>{{ customer.firstName }}</td>
          <td>{{ customer.lastName }}</td>
          <td>{{ customer.phone }}</td>
          <td>{{ customer.username }}</td>
          <td>
            <button class="btn btn-warning btn-sm" @click="openEditModal(customer)"><i
                class="fa-solid fa-pen-to-square"></i></button> |
            <!-- ปุ่มลบ -->
            <button class="btn btn-danger btn-sm" @click="deleteCustomer(customer.customer_id)"><i
                class="fa-solid fa-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="loading" class="text-center">
      <p>Loading Data...</p>
    </div>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <!-- ✅ Modal ใช้ทั้งเพิ่ม/แก้ไข -->
    <div class="modal fade" id="editModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ isEditMode ? "Edit Customer" : "Add Customer" }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveCustomer">
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input v-model="editCustomer.firstName" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Surname</label>
                <input v-model="editCustomer.lastName" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Phone.</label>
                <input v-model="editCustomer.phone" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input v-model="editCustomer.username" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input v-model="editCustomer.password" type="password" class="form-control" :required="!isEditMode"
                  placeholder="กรอกเฉพาะเมื่อเพิ่มใหม่หรือเปลี่ยนรหัสผ่าน">
              </div>
              <button type="submit" class="btn btn-success">
                {{ isEditMode ? "Saved" : "Add Customer" }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { ref, onMounted } from "vue";

export default {
  name: "CustomerList",
  setup() {
    const customers = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const editCustomer = ref({});
    const isEditMode = ref(false);
    let editModal = null;

    const fetchCustomers = async () => {
      try {
        const response = await fetch("http://localhost/67713669/api_php/api_customer.php");
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
      const modalEl = document.getElementById("editModal");
      editModal = new window.bootstrap.Modal(modalEl);
    });

    // ✅ เปิด Modal เพิ่มลูกค้าใหม่
    const openAddModal = () => {
      isEditMode.value = false;
      editCustomer.value = {
        firstName: "",
        lastName: "",
        phone: "",
        username: "",
        password: ""
      };
      editModal.show();
    };

    // ✅ เปิด Modal แก้ไขลูกค้า
    const openEditModal = (customer) => {
      isEditMode.value = true;
      editCustomer.value = { ...customer, password: "" };
      editModal.show();
    };

    // ✅ ใช้ฟังก์ชันเดียวสำหรับทั้งเพิ่ม/แก้ไข
    const saveCustomer = async () => {
      const url = "http://localhost/67713669/api_php/api_customer.php";
      const method = isEditMode.value ? "PUT" : "POST";

      try {
        const response = await fetch(url, {
          method,
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(editCustomer.value)
        });

        const result = await response.json();

        if (result.success) {
          alert(result.message);
          fetchCustomers();
          editModal.hide();
        } else {
          alert(result.message);
        }
      } catch (err) {
        alert("ERROR: " + err.message);
      }
    };

    // ✅ ลบลูกค้า
    const deleteCustomer = async (id) => {
      if (!confirm("Do you want to Delete this data?")) return;
      try {
        const response = await fetch("http://localhost/67713669/api_php/api_customer.php", {
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
        alert("ERROR: " + err.message);
      }
    };

    return {
      customers,
      loading,
      error,
      editCustomer,
      isEditMode,
      openAddModal,
      openEditModal,
      saveCustomer,
      deleteCustomer
    };
  }
};
</script>
