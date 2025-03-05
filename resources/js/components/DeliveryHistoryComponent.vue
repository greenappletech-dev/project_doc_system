<template>
    <div class="container mt-4">
        <div class="d-flex justify-content-center">
            <h2 class="mb-4">Delivery Viewer</h2>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <v-client-table
                :data="tableData"
                :columns="columns"
                :options="options"
                >
                <template slot="actions" slot-scope="{ row }">
                    <button class="btn btn-primary" @click="openModal(row)">View</button>
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
    props: ['deliveries'],
    data() {
        return {
            tableData: this.deliveries,
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
                }
            }
        };
    },
    methods: {
        openModal(row) {
            this.selectedPhoto = row.photo ? `/${row.photo}` : '';
            $('#photoModal').modal('show');
        },
        closeModal() {
            this.selectedPhoto = null;
            $('#photoModal').modal('hide');
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
</style>
