<template>
    <Card>
        <template #title>
            <h3>File Upload</h3>
        </template>

        <div class="file-upload">
            <div class="upload-box">
                <button class="browse-button" @click="triggerFileInput">
                    Browse files
                </button>

                <input
                    type="file"
                    id="fileInput"
                    style="display: none;"
                    @change="handleFileChange"
                    accept=".xlsx"
                >

                <div
                    class="upload-area"
                    @click="triggerFileInput"
                    @dragover.prevent @drop="handleDrop"
                >
                    <p>Click the file & drop it here (drag & drop)</p>
                </div>
            </div>

            <p class="file-name">{{ fileName }}</p>
        </div>
    </Card>

</template>

<script>
import Card from "@/includes/Card.vue";

export default {
    components: {Card},
    data() {
        return {
            fileName: ''
        };
    },
    methods: {
        triggerFileInput() {
            document.getElementById('fileInput').click();
        },
        handleFileChange(event) {
            const file = event.target.files[0];
            this.fileName = file.name;
            this.uploadFile(file);
        },
        handleDrop(event) {
            event.preventDefault();
            const file = event.dataTransfer.files[0];
            this.fileName = file.name;
            this.uploadFile(file);
        },
        uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);

            $.ajax({
                url: '/api/upload',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: response => {
                    this.$emit('file-uploaded', response.data);
                    Bus.emit('refetch-transactions');
                    Bus.emit('refetch-chart');
                    Bus.emit('refetch-sums');
                },
                error: () => {
                    alert('File upload failed');
                }
            });
        }
    }
};
</script>

<style scoped>
    .file-upload {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .upload-box {
        display: flex;
        align-items: flex-start;
        width: 100%;
        padding: 20px;
        text-align: center;
    }

    .upload-box > * {
        margin-right: 10px;
    }

    .browse-button {
        background-color: #4caf50;
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        margin-bottom: 10px;
        align-self: flex-start;
    }

    .browse-button:hover {
        background-color: #45a049;
    }

    .upload-area {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #4caf50;
        padding: 20px 0 20px 0;
        cursor: pointer;
        margin-bottom: 10px;
        width: 100%;
    }

    .upload-area p {
        margin: 0;
    }

    .file-name {
        margin: 0;
        align-self: flex-start;
    }
</style>
