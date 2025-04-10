<template>
    <div class="container mt-4">
        <div class="d-flex justify-content-center">
            <h2 class="mb-4">Account Documentation</h2>
        </div>

        <!-- Success Notification -->
        <div v-if="showSuccessMessage" class="alert alert-success text-center">
            Documentation saved successfully!
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Documentation Type</label>
                            <select class="form-control" v-model="dataValues.demand_id" @change="saveToLocalStorage">
                                <option v-for=" docs in documenttion_types"  :value="docs.id">{{ docs.name }}</option>
                            </select>
                            <small v-if="errors.demand_id" class="text-danger">{{ errors.demand_id }}</small>
                        </div>

                        <div class="form-group">
                            <label>Select District</label>
                            <select class="form-control" v-model="dataValues.district_id" @change="selectDistrict(dataValues.district_id)">
                                <option v-for="district in districts" :value="district.id">{{ district.name }}</option>
                            </select>
                            <small v-if="errors.district_id" class="text-danger">{{ errors.district_id }}</small>
                        </div>

                        <div class="form-group">
                            <label>Select Project</label>
                            <Select2 class=" select2 custom-select-style" v-model="dataValues.project_id" 
                            :options="projectList" @change="myChangeProject(dataValues.project_id)" />
                            <small v-if="errors.project_id" class="text-danger">{{ errors.project_id }}</small>
                        </div>

                        <div class="form-group">
                            <label>Search Beneficiary</label>
                            <Select2 class="select2" v-model="dataValues.beneficiary_id" 
                            :options="beneficiaries" @change="updateAddress" />
                            <small v-if="errors.beneficiary_id" class="text-danger">{{ errors.beneficiary_id }}</small>
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" class="form-control" v-model="dataValues.address" disabled />
                        </div>

                        <div class="form-group">
                            <label>ComCode</label>
                            <input type="text" class="form-control" v-model="dataValues.com_code" disabled />
                        </div>
                    </div>

                    <div class="col-md-6 text-center">
                        <h5>Capture Photo</h5>
                        <div v-if="dataValues.temp_photoURL !== null">
                            <img :src="dataValues.temp_photoURL" alt="photo-preview" class="captured-photo">
                        </div>
                        <div v-else>
                            <video v-if="!dataValues.capturedPhotoURL" ref="camera" autoplay class="camera-preview"></video>

                            <img v-if="dataValues.capturedPhotoURL" :src="dataValues.capturedPhotoURL" class="captured-photo" />


                            <canvas ref="canvas" class="d-none"></canvas>
                        </div>

                        <div class="mt-3" v-if="dataValues.temp_photoURL !== null">
                            <button class="btn btn-warning btn-sm mx-1" @click="retakePhoto">
                                Retake Photo
                            </button>
                        </div>
                        
                        <div class="mt-3" v-else>
                            <button v-if="!cameraActive && !dataValues.capturedPhotoURL" class="btn btn-success btn-sm mx-1" @click="startCamera">
                                Start Camera
                            </button>
                            <button v-if="cameraActive && !dataValues.capturedPhotoURL" class="btn btn-primary btn-sm mx-1" @click="capturePhoto">
                                Capture Photo
                            </button>
                            <button v-if="dataValues.capturedPhotoURL" class="btn btn-warning btn-sm mx-1" @click="retakePhoto">
                                Retake Photo
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button v-if="this.dataValues.temp_photoURL==null" class="btn btn-primary" @click="storeData">Save</button>
            </div>
        </div>
        <div class="vld-parent">
            <loading :active="isLoading"
                     :can-cancel="false"
                     :is-full-page="fullPage"></loading>
            <!--            :on-cancel="onCancel"-->
        </div>
    </div>
</template>

<script>
import Select2 from 'v-select2-component';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    props: ['districts', 'documenttion_types'],
    data() {
        return {
            cameraActive: false,
            showSuccessMessage: false,
            myValue: '',
            errors:{},
            myOptions: ['op1', 'op2', 'op3'],
            dataValues: {
                demand_id: '',
                district_id: '',
                project_id: '',
                beneficiary_id: '',
                address: '', 
                com_code:'',
                photo: null,
                capturedPhotoURL: null,
                delivered_id: null,
                temp_photoURL: null,
            },
            projectList: [],
            beneficiaries: [],
            cameraStream: null,
            demandNotices: [],
            isLoading: false,
            fullPage: true,
        };
    },
    components: { Select2, Loading },
    created() {
        this.loadFromLocalStorage();
        this.fetchDemandNotices();
    },
    methods: {
        saveToLocalStorage() {
            localStorage.setItem('dataValues', JSON.stringify({
                district_id : this.dataValues.district_id,
                demand_id: this.dataValues.demand_id,
                project_id: this.dataValues.project_id,
                // beneficiary_id: this.dataValues.beneficiary_id,
                // address: this.dataValues.address
            }));
        },
        loadFromLocalStorage() {
            const savedData = JSON.parse(localStorage.getItem('dataValues'));
            if (savedData) {
                this.dataValues = { ...this.dataValues, ...savedData };
                this.selectDistrict(this.dataValues.district_id);
                this.myChangeProject(this.dataValues.project_id);
            }
        },
        selectDistrict(district_id) {
            axios.get(`deliveries/gather_project/${district_id}`)
                .then(response => {
                    this.projectList = response.data.data;
                    this.saveToLocalStorage();
                });
        },
        myChangeProject(project_id) {
            this.dataValues.address='';
            this.dataValues.com_code='';
            axios.get(`deliveries/gather_beneficiaries/${project_id}/${this.dataValues.demand_id}`)
                .then(response => {
                    this.beneficiaries = response.data.data;
                    this.saveToLocalStorage();
                });
        },
        updateAddress() { 
            const selectedBeneficiary = this.beneficiaries.find(b => b.id == this.dataValues.beneficiary_id);
            if (selectedBeneficiary) {
                this.dataValues.address = selectedBeneficiary.address;
                this.dataValues.com_code = selectedBeneficiary.com_code;
                this.dataValues.temp_photoURL = selectedBeneficiary.delivered_photo;
                this.dataValues.delivered_id = selectedBeneficiary.delivered_id;
            } else {
                console.warn("Selected beneficiary not found!");
                this.dataValues.address = '';
            }
            this.saveToLocalStorage();
        },
        async startCamera() {
            try {
                const constraints = {
                    video: {
                        facingMode: { exact: "environment" }, // Use back camera
                    },
                };
                const videoElement = this.$refs.camera;
                this.cameraStream = await navigator.mediaDevices.getUserMedia( constraints);
                videoElement.srcObject = this.cameraStream;
                this.cameraActive = true; // Show Capture Photo button
            } catch (error) {
                console.error("Error accessing the camera:", error);
                alert("Unable to access the camera. Please check your browser permissions.");
            }
},

        async capturePhoto() {
            const canvas = this.$refs.canvas;
            const context = canvas.getContext('2d');
            const video = this.$refs.camera;

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            this.dataValues.capturedPhotoURL = canvas.toDataURL('image/png');

            const blob = await fetch(this.dataValues.capturedPhotoURL).then(res => res.blob());
            this.dataValues.photo = new File([blob], "delivery_photo.png", { type: "image/png" });

            this.stopCamera();
            this.cameraActive = false; 
        },

        retakePhoto() {
            this.dataValues.temp_photoURL = null;
            this.dataValues.capturedPhotoURL = null;
            this.dataValues.photo = null;
            this.startCamera();
        },
        stopCamera() {
            if (this.cameraStream) {
                this.cameraStream.getTracks().forEach(track => track.stop());
                this.cameraStream = null;
            }
        },
        storeData() {
            
            this.errors = {};

                if (!this.dataValues.demand_id) {
                    this.errors.demand_id = "Please select a type of notice.";
                }
                if (!this.dataValues.district_id) {
                    this.errors.district_id = "Please select a district.";
                }
                if (!this.dataValues.project_id) {
                    this.errors.project_id = "Please select a project.";
                }
                if (!this.dataValues.beneficiary_id) {
                    this.errors.beneficiary_id = "Please select a beneficiary.";
                }
                if (!this.dataValues.photo) {
                    this.errors.photo = "Please capture a delivery photo.";
                }

                if (Object.keys(this.errors).length > 0) {
                    return;
                }

            let confirmBox = confirm('Are you sure you want to save this data ?');
            if(confirmBox){
            this.isLoading = true;
            const formData = new FormData();
            formData.append('demand_id', this.dataValues.demand_id);
            formData.append('district_id', this.dataValues.district_id);
            formData.append('project_id', this.dataValues.project_id);
            formData.append('beneficiary_id', this.dataValues.beneficiary_id);
            formData.append('delivered_id', this.dataValues.delivered_id);

            if (this.dataValues.photo) {
                formData.append('photo', this.dataValues.photo, 'delivery_photo.png');
            }

            axios.post('/deliveries/store', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
            .then(response => {
                console.log(response);
                this.isLoading = false;
                this.showSuccessMessage = true;

                setTimeout(() => {
                    window.location.reload();
                }, 5000);

                this.resetForm();
            })
            .catch(error => {
                console.log(error);
                alert('Error saving delivery.');
            });
        }
        },
        resetForm() {
            window.location.reload();
            this.dataValues.photo = null;
            this.dataValues.capturedPhotoURL = null;
            this.startCamera();
        },
        fetchDemandNotices() {
            axios.get('/demandnotice/show')
                .then(response => {
                    this.demandNotices = response.data.data;
                })
                .catch(error => {
                    console.error("Error fetching demand notices:", error);
                });
        },
        mounted () {
            this.selectDistrict();
            this.myChangeProject();
        },
    },
};
</script>

<style scoped>
    .custom-select {
    width: 100% !important; /* Custom width */
    height: 45px !important; /* Custom height */
    border: 2px solid #4CAF50 !important; /* Green border */
    border-radius: 8px !important; /* Rounded corners */
    font-size: 16px;
    }
/* Style dropdown */
    .select2-container--default .select2-dropdown {
    background-color: #f9f9f9;
    border: 1px solid #4CAF50;
    }

    /* Style dropdown items */
    .select2-container--default .select2-results__option {
    padding: 10px;
    font-size: 16px;
    }

    /* Highlight hover effect */
    .select2-container--default .select2-results__option--highlighted {
    background-color: #4CAF50 !important;
    color: white !important;
    }
/* .select2{
    width: 100%;
    margin-bottom: 10px;
    align-items: center;
    display: block;
    width: 100%;
    height: calc(2.25rem + 2px);
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    box-shadow: inset 0 0 0 transparent;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
} */
.camera-preview, .captured-photo {
    width: 100%;
    height: 340px;
    border: 2px solid #ddd;
    border-radius: 8px;
    object-fit: cover;
}
</style>