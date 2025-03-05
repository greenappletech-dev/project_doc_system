<template>
    <div class="p-2">
        <div class="m-4 mt-4">
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary" v-on:click="create">
                    <i class="fas fa-plus"></i> Create New
                </button>
            </div>
            <div class="card mb-4">
                <div class="card-header"><b>Type of Notices List</b></div>
                <div class="card-body">
                    <v-client-table :data="data" :columns="columns" :options="options">
                        <template slot="actions" slot-scope="row">
                            <button class="btn btn-outline-warning" v-on:click="edit(row)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger" v-on:click="destroy(row)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </template>
                    </v-client-table>
                </div>
            </div>

            <!-- Modal for Creating & Editing -->
            <div class="modal" id="create-demand">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <h5 class="modal-title bg-primary text-center pt-2 pb-2" style="font-weight:bold;">
                            {{ isEdit ? 'Update Notice' : 'Create Notice'}}
                        </h5>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Notice Name</label>
                                <input type="text" class="form-control" id="name" v-model="name"
                                    :class="{ 'border border-danger' : errors.name }"
                                />
                                <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button @click="closeModal" type="button" class="btn btn-secondary">Close</button>
                            <button v-if="!isEdit" v-on:click="createRecord" type="button" class="btn btn-primary">Save</button>
                            <button v-else v-on:click="updateRecord" type="button" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2";

export default {
    data() {
        return {
            isEdit: false,
            errors: [],
            id: '',
            name: '',
            data: [],
            columns: ['id', 'name', 'actions'],
            options: {
                headings: {
                    id: 'ID',
                    name: 'Demand Notice',
                    actions: 'Actions'
                },
                filterable: false,
            },
        };
    },
    methods: {
        init() {
            this.name = '';
            this.errors = [];
        },
        show() {
            axios.get('/demandnotice/show').then(response => {
                this.data = response.data.data;
            }).catch(error => {
                console.error('Error fetching data:', error);
            });
        },
        closeModal() {
            $('#create-demand').modal('hide');
            this.init();
        },
        create() {
            this.isEdit = false;
            this.init();
            $('#create-demand').modal('show');
        },
        createRecord() {
            axios.post('/demandnotice/store', { name: this.name })
                .then(response => {
                    Swal.fire("Success!", "Notice has been created.", "success");
                    this.show();
                    this.closeModal();
                })
                .catch(error => {
                    Swal.fire("Error!", "Failed to create demand notice.", "error");
                    this.errors = error.response.data.errors;
                });
        },
        edit(data) {
            this.isEdit = true;
            this.id = data.row.id;
            this.name = data.row.name;
            $('#create-demand').modal('show');
        },
        updateRecord() {
            axios.put(`/demandnotice/update/${this.id}`, { name: this.name })
                .then(response => {
                    Swal.fire("Updated!", "Notice has been updated.", "success");
                    this.show();
                    this.closeModal();
                })
                .catch(error => {
                    Swal.fire("Error!", "Failed to update demand notice.", "error");
                    this.errors = error.response.data.errors;
                });
        },
        destroy(data) {
            if(confirm('Are you sure you want to delete!')){
                axios.get('/demandnotice/destroy/' + data.row.id)                
                .then(response => {
                    Swal.fire("Success!", "Notice has been deleted.", "success");
                    this.show();
                    this.closeModal();
                })
                .catch(error => {
                    Swal.fire("Error!", "Failed to delete demand notice.", "error");
                    this.errors = error.response.data.errors;
                });
        }
    },
},
    mounted() {
        this.show();
        this.init();
    }
};
</script>

<style scoped>
.p-5 {
    padding: 3rem !important;
}

.card {
    margin-top: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.breadcrumb {
    background-color: white;
    font-size: 18px;
    font-weight: bold;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: '>';
    color: #6c757d;
    padding: 0 0.5rem;
}
</style>
