<template>
    <div class="container mt-4">
        <h2 class="mb-3">Student List</h2>

        <div class="mb-3">
            <a class="btn btn-primary" href="/add_student" role="button">Add+</a>
        </div>

        <!-- ตารางแสดงข้อมูลลูกค้า -->
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>FirstName</th>
                    <th>lastName</th>
                    <th>Email</th>
                    <th>Phone.</th>
                    <th class="text-center">Delete User</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="student in students" :key="student.student_id">
                    <td>{{ student.student_id }}</td>
                    <td>{{ student.first_name }}</td>
                    <td>{{ student.last_name }}</td>
                    <td>{{ student.email }}</td>
                    <td>{{ student.phone }}</td>
                    <td class="text-center">
                        <button class="btn btn-danger btn-sm" @click="deleteStudent(student.student_id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Loading -->
        <div v-if="loading" class="text-center">
            <p>loading data...</p>
        </div>

        <!-- Error -->
        <div v-if="error" class="alert alert-danger">
            {{ error }}
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";

export default {
    name: "StudentList",
    setup() {
        const students = ref([]);
        const loading = ref(true);
        const error = ref(null);

        // ฟังก์ชันดึงข้อมูลจาก API ด้วย GET
        const fetchStudents = async () => {
            try {
                const response = await fetch("http://localhost/67713669/api_php/show_students.php", {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json"
                    }
                });

                if (!response.ok) {
                    throw new Error("Cannot fetch data from server");
                }

                const result = await response.json();
                if (result.success) {
                    students.value = result.data;
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
            fetchStudents();
        });
        const deleteStudent = async (id) => {
            if (!confirm("Do you want to deleted this data?")) return;

            try {
                const response = await fetch("http://localhost/67713669/api_php/api_student.php", {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ student_id: id })
                });

                const result = await response.json();

                if (result.success) {
                    students.value = students.value.filter(c => c.student_id !== id);
                    alert(result.message);
                } else {
                    alert(result.message);
                }

            } catch (err) {
                alert("Something went wrong.: " + err.message);
            }
        };


        return {
            students,
            loading,
            deleteStudent,
            error
        };
    }
};
</script>
