<template>
  <div class="form-group bg-green-500">
    <label>{{ $t('Upload an Image for Slidehow') }}</label>
    <div class="image-uploader">
      <input type="file" accept="image/*" @change="onFileChange">
      <div v-if="imageData" class="preview">
        <img :src="imageData" alt="Image Preview">
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      imageData: null,
    };
  },
  methods: {
    onFileChange(event) {
      const file = event.target.files[0];
      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.imageData = e.target.result;
        };
        reader.readAsDataURL(file);
      } else {
        this.imageData = null;
        alert('Please select a valid image file.');
      }
    },
  },
};
</script>

<style scoped>
  .image-uploader {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .preview {
    margin-top: 10px;
  }

  .preview img {
    max-width: 100%;
    max-height: 300px;
    border: 1px solid #ddd;
    padding: 5px;
    background-color: #f9f9f9;
  }
</style>
