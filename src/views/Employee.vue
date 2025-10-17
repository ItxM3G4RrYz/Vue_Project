<template>
  <div class="container mt-4">
    <h2 class="mb-3">Our Employee</h2>

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
          <th>Salary</th>
          <th>TimeWent</th>
          <th class="text-center">Edit/Delete

          </th>

        </tr>
      </thead>
      <tbody>
        <tr v-for="employee in employees" :key="employee.emp_id">
          <td>{{ employee.emp_id }}</td>
          <td>{{ employee.firstname }}</td>
          <td>{{ employee.lastname }}</td>
          <td>{{ employee.phone }}</td>
          <td>{{ employee.salary }}</td>
          <td>{{ employee.timewent }}</td>
          <td class="text-center">
            <button class="btn btn-warning btn-sm" @click="openEditModal(employee)"><i
                class="fa-solid fa-pen-to-square"></i></button> |
            <!-- ปุ่มลบ -->
            <button class="btn btn-danger btn-sm" @click="deleteEmployee(employee.emp_id)"><i
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
            <h5 class="modal-title">{{ isEditMode ? "Edit Employee" : "Add Employee" }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveEmployee">
              <div class="mb-3">
                <label class="form-label">firstName</label>
                <input v-model="editEmployee.firstname" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">LastName</label>
                <input v-model="editEmployee.lastname" type="text" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Phone.</label>
                <input v-model="editEmployee.phone" type="number" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Salary</label>
                <input v-model="editEmployee.salary" type="number" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input v-model="editEmployee.password" type="password" class="form-control" :required="!isEditMode"
                  placeholder="Fill when change password or add new user">
              </div>
              <button type="submit" class="btn btn-success">
                {{ isEditMode ? "Saved" : "Add Employee" }}
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
  name: "EmployeeList",
  setup() {
    const employees = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const editEmployee = ref({});
    const isEditMode = ref(false);
    let editModal = null;

    const fetchEmployees = async () => {
      try {
        const response = await fetch("http://localhost/67713669/api_php/api_employee.php");
        const result = await response.json();

        if (result.success) {
          employees.value = result.data;
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
      fetchEmployees();
      const modalEl = document.getElementById("editModal");
      editModal = new window.bootstrap.Modal(modalEl);
    });

    // ✅ เปิด Modal เพิ่มลูกค้าใหม่
    const openAddModal = () => {
      isEditMode.value = false;
      editEmployee.value = {
        firstname: "",
        lastname: "",
        phone: "",
        salary: "",
        password: ""
      };
      editModal.show();
    };

    // ✅ เปิด Modal แก้ไขลูกค้า
    const openEditModal = (employee) => {
      isEditMode.value = true;
      editEmployee.value = { ...employee, password: "" };
      editModal.show();
    };

    // ✅ ใช้ฟังก์ชันเดียวสำหรับทั้งเพิ่ม/แก้ไข
    const saveEmployee = async () => {
      const url = "http://localhost/67713669/api_php/api_employee.php";
      const method = isEditMode.value ? "PUT" : "POST";

      try {
        const response = await fetch(url, {
          method,
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(editEmployee.value)
        });

        const result = await response.json();

        if (result.success) {
          alert(result.message);
          fetchEmployees();
          editModal.hide();
        } else {
          alert(result.message);
        }
      } catch (err) {
        alert("ERROR: " + err.message);
      }
    };

    // ✅ ลบลูกค้า
    const deleteEmployee = async (id) => {
      if (!confirm("Do you want to Delete this data?")) return;
      try {
        const response = await fetch("http://localhost/67713669/api_php/api_employee.php", {
          method: "DELETE",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ emp_id: id })
        });
        const result = await response.json();
        if (result.success) {
          employees.value = employees.value.filter(c => c.emp_id !== id);
          alert(result.message);
        } else {
          alert(result.message);
        }
      } catch (err) {
        alert("ERROR: " + err.message);
      }
    };

    return {
      employees,
      loading,
      error,
      editEmployee,
      isEditMode,
      openAddModal,
      openEditModal,
      saveEmployee,
      deleteEmployee
    };
  }
};
</script>
