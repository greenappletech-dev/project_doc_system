<template>
    <div class="container mt-4">
        <div class="d-flex justify-content-center">
            <h2 class="mb-4">Delivery Viewer</h2>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="project_id">Select Distric</label>
                            <select name="" id="" class="form-control" v-model="dataValues.district_id" @change="getProjects()">
                                <option v-for="district in districts" :value="district.id">{{ district.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="project_id">Select Project</label>
                            <select name="" id="" class="form-control" v-model="dataValues.project_id">
                                <option v-for="project in projects" :value="project.id">{{ project.name }}</option>
                            </select>
                            <span v-if="errors && errors.project_id" class="text-danger">{{ errors.project_id[0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Date From</label>
                            <input type="date" class="form-control" v-model="dataValues.date_from">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Date To</label>
                            <input type="date" class="form-control" v-model="dataValues.date_to">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <button class="btn btn-primary" @click="gatherData();">Refresh</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-body">
                <v-client-table
                :data="tableData"
                :columns="columns"
                :options="options"
                >
                <template slot="actions" slot-scope="{ row }">
                    <button class="custom_btn" @click="openModal(row)"><img :src="row.photo" alt="preview photo" width="50"></button>
                </template>
                </v-client-table>
            </div>
        </div>

        <!-- Modal for Viewing Image -->
        <div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delivery Photo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img v-if="selectedPhoto" :src="selectedPhoto" class="img-fluid rounded" alt="Delivery Photo">
                        <p v-else class="text-muted">No photo available.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['from_date', 'to_date', 'districts', 'district_id', 'project_id'],
    data() {
        return {
            tableData: [],
            projects: [],
            selectedPhoto: null,
            columns: ['notice_type_name', 'project_name', 'address', 'beneficiary_name', 'date_captured', 'collector_name', 'actions'],
            options: {
                headings: {
                    notice_type_name: 'Notice Type',
                    project_name: 'Project Name',
                    beneficiary_name: 'Beneficiary Name',
                    date_captured: 'Date Captured',
                    collector_name: 'Collector Name',
                    actions: 'Actions'
                },
                sortable: ['notice_type_name', 'project_name', 'address', 'beneficiary_name', 'date_captured', 'collector_name'],
                filterable: ['beneficiary_name'],
                texts:{
                    filter: "Search" 
                }
            },
            dataValues:{
                district_id: this.district_id > 0 ? this.district_id : '',
                project_id: this.project_id > 0 ? this.project_id : '',
                date_from: this.from_date,
                date_to: this.to_date
            },
            errors:[]
        };
    },
    methods: {
        openModal(row) {
            this.selectedPhoto = row.photo ? `/${row.photo}` : '';
            $('#photoModal').modal('show');
        },
        getProjects(){
            axios.get(`notice-viewer/gather_projects/${this.dataValues.district_id}`)
            .then(response => {
                this.projects = response.data.data;
                if(this.dataValues.project_id != ''){
                    this.gatherData();
                }
            })
        },
        gatherData(){
            axios.post('notice-viewer/gather_data', this.dataValues)
            .then(response => {
                this.tableData = response.data.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        closeModal() {
            this.selectedPhoto = null;
            $('#photoModal').modal('hide');
        }
    },
    mounted() {
        if(this.dataValues.district_id != ''){
            this.getProjects();
        }
    }
};
</script>

<style scoped>
.modal-body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}
.custom_btn{
    background: none;
    border: none;
    cursor: pointer;
}
</style>
