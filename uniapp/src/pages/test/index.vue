<script setup>
import { ref } from "vue";
import LunaImageFaceSelector from "../../components/LunaImageFaceSelector.vue";

const faceList = ref([
  {
    boundingBoxHeight: 0.268044,
    boundingBoxLeft: 0.0403212,
    boundingBoxTop: 0.0962504,
    boundingBoxWidth: 0.321089,
    gender: "male",
    h: 0.268044,
    id: 655950,
    race: "asian",
    w: 0.321089,
    x: 0.0403212,
    y: 0.0962504,
    is_default: 1,
  },
  {
    boundingBoxHeight: 0.274572,
    boundingBoxLeft: 0.429663,
    boundingBoxTop: 0.199694,
    boundingBoxWidth: 0.306176,
    gender: "female",
    h: 0.274572,
    id: 655948,
    race: "asian",
    w: 0.306176,
    x: 0.429663,
    y: 0.199694,
    is_default: 0,
  },
]);
const imageUrl = ref(
  "https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/libraries/点对点/db45b4e9a5b6448ea3734b55e8f3f86f.webp"
);

const renderHeight = 900;
const renderWidth = 600;

const onSelectFace = (face) => {
  console.log(face, "on select face");
};
</script>

<template>
  <view>测试</view>
  <luna-image-face-selector
    :faceList="faceList"
    :imageUrl="imageUrl"
    :renderHeight="renderHeight"
    :renderWidth="renderWidth"
    @selectFace="onSelectFace"
  ></luna-image-face-selector>
</template>

<style scoped lang="scss"></style>
