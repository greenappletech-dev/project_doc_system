<template>
    <div class="p-2">
        <div class="m-4 mt-4">
            <div class="mb-1">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" v-on:click="create">
                        <i class="fas fa-plus"></i> Create New
                    </button>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><b>Billing Notices List</b></div>
                <div class="card-body">
                    <v-client-table 
                    :data="data" 
                    :columns="columns" 
                    :options="options">
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

            <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <h5 class="modal-title bg-primary text-center pt-2 pb-2" style="font-weight:bold;">
                            {{ isEdit ? 'Update Demand Notice' : 'Create Demand Notice'}}
                        </h5>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Billing Notice</label>
                                <input type="text" class="form-control" id="name" v-model="name"
                                    :class="{ 'border border-danger' : errors.name }"
                                />
                                <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button @click="closeModal" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button v-if="!isEdit" v-on:click="createRecord" type="button" class="btn btn-primary">Save changes</button>
                            <button v-else v-on:click="updateRecord" type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
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
            });
        },
        closeModal() {
            $("#create-modal").modal("hide");
        },
        create() {
            this.isEdit = false;
            this.init();
            $("#create-modal").modal("show");
        },
        createRecord() {
            axios.post('/demandnotice/store', { name: this.name })
                .then(response => {
                    this.$fire({
                        title: 'Successfully Saved!',
                        text: response.data.message,
                        type: 'success',
                        timer: 3000,
                    });
                    this.show();
                    this.init();
                    this.closeModal();
                })
                .catch(error => {
                    this.$fire({
                        title: 'Error Saving',
                        text: error.response.data.message,
                        type: 'warning',
                        timer: 3000,
                    });
                    this.errors = error.response.data.errors;
                });
        },
        edit(data) {
            this.isEdit = true;
            this.id = data.row.id;
            this.name = data.row.name;
            $("#create-modal").modal("show");
        },
        updateRecord() {
            axios.put('/demandnotice/update/' + this.id, { name: this.name })
                .then(response => {
                    this.$fire({
                        title: 'Successfully Updated!',
                        text: response.data.message,
                        type: 'success',
                        timer: 3000,
                    });
                    this.show();
                    this.init();
                    $('#create-modal').modal('hide');
                })
                .catch(error => {
                    this.$fire({
                        title: 'Error Saving',
                        text: error.response.data.message,
                        type: 'warning',
                        timer: 3000,
                    });
                    this.errors = error.response.data.errors;
                });
        },
        destroy(data) {
            if (confirm('Are you sure you want to delete this record?')) {
                axios.delete('/demandnotice/destroy/' + data.row.id)
                    .then(response => {
                        this.$fire({
                            title: 'Successfully Deleted!',
                            text: response.data.message,
                            type: 'success',
                            timer: 3000,
                        });
                        this.show();
                    })
                    .catch(error => {
                        this.$fire({
                            title: 'Error',
                            text: error.response ? error.response.data.message : 'Internal Server Error',
                            type: 'warning',
                            timer: 3000,
                        });
                        this.errors = error.response?.data.errors || [];
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

.form-check {
    margin-top: 0.5rem;
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
