<template>
  <el-card>
    <el-upload
      ref="uploadRef"
      :action="`${baseUrl}/api/userMessage/checkUserImageUpload`"
      :headers="{
        jwtheader: cache.get(LUNA_TOKEN_KEY),
      }"
      :data="{
        isMaterialFile: isMaterialFile ? 1 : 0,
      }"
      :on-error="onError"
      :on-success="onSuccess"
      :disabled="loading"
      :before-upload="onBeforeUpload"
      accept="image/*"
      :limit="1"
      :show-file-list="false"
    >
      <el-button type="primary" v-if="!uploadFileUrl" :loading="loading">
        {{ loading ? "上传检测中，请稍等10秒左右" : "上传图片" }}
      </el-button>
      <el-button type="warning" @click="onClearUploadFile" v-else>
        重新上传
      </el-button>
    </el-upload>
    <div class="flex gap-4 mt-2">
      <face-rectangle-drawer
        v-if="uploadFileUrl"
        :face-data="faceData"
        width="160"
        :image-src="uploadFileUrl"
      ></face-rectangle-drawer>
      <div class="flex flex-col justify-between flex-1">
        <div class="flex gap-1 items-center">
          <el-input
            v-model="uploadFileId"
            placeholder="可直接指定算法侧文件ID"
          />
          <el-button plain :icon="Refresh" @click="getFileInfo"></el-button>
        </div>
        <div
          v-if="uploadFileUrl && faceData.length"
          class="text-ellipsis line-clamp-3"
        >
          人脸位置：{{ faceData }}
        </div>
      </div>
    </div>
  </el-card>
</template>

<script lang="ts" setup>
import { ref, onMounted, defineProps, defineEmits } from "vue";
import cache from "@/utils/cache";
import { LUNA_TOKEN_KEY } from "@/enums/cacheEnums";
import { getLunaToken } from "@/api/app";
import feedback from "@/utils/feedback";
import { getMaterialFaceList, getUserFaceList } from "@/api/luna";
import FaceRectangleDrawer from "./FaceRectangleDrawer.vue";
import config from "@/config";
import { Refresh } from "@element-plus/icons-vue";
const props = defineProps<{
  isMaterialFile: boolean;
}>();

export interface Face {
  h: number;
  w: number;
  x: number;
  y: number;
  boundingBoxHeight: number;
  boundingBoxLeft: number;
  boundingBoxTop: number;
  boundingBoxWidth: number;
  gender: string;
  id: number;
  is_default: number;
  race: string;
}

export interface UploadSuccessData {
  fileId: string;
  fileUrl: string;
  faceList: Face[];
}

const emit = defineEmits<{
  (e: "uploadSuccess", data: UploadSuccessData): void;
}>();

const baseUrl = config.lunaBaseUrl;
const uploadFileUrl = ref("");
const faceData = ref([]);
const loading = ref(false);
const uploadRef = ref();
const uploadFileId = ref("");
const ossHost = config.lunaOssBaseUrl;

const onError = (err: Error) => {
  loading.value = false;
  console.log("onError", err);
  feedback.msgError(
    "上传失败" +
      (err.name == "UploadAjaxError"
        ? "：算法服务连接失败，尝试关闭VPN再试试"
        : err)
  );
};

const setResultDataThenEmits = (res: any) => {
  console.log("setResultDataThenEmits", res);
  uploadFileId.value = res.id;
  uploadFileUrl.value = ossHost + "/" + res.filePath;
  faceData.value = res.fileFaceList.map(
    (item: any, index) =>
      ({
        h: item.boundingBoxHeight,
        w: item.boundingBoxWidth,
        x: item.boundingBoxLeft,
        y: item.boundingBoxTop,
        is_default: index === 0 ? 1 : 0,
        ...item,
      } as Face)
  );

  // Emit the upload success event
  emit("uploadSuccess", {
    fileId: uploadFileId.value,
    fileUrl: uploadFileUrl.value,
    faceList: faceData.value,
  });
};

const getFileInfo = () => {
  const getFileInfoFunc = props.isMaterialFile
    ? getMaterialFaceList
    : getUserFaceList;
  getFileInfoFunc({ id: uploadFileId.value }).then((res: any) => {
    setResultDataThenEmits(res);
  });
};

const onSuccess = (res: any) => {
  uploadRef.value?.clearFiles();
  loading.value = false;

  if (res.code !== 1) {
    feedback.msgError("上传失败：" + res.message);
    return;
  }

  setResultDataThenEmits(res.data);
};

const onBeforeUpload = () => {
  loading.value = true;
};

const onClearUploadFile = () => {
  uploadFileId.value = "";
  uploadFileUrl.value = "";
  uploadRef.value?.clearFiles();
};

onMounted(() => {
  getLunaToken().then((res) => {
    console.log("getLunaToken", res);
    cache.set(LUNA_TOKEN_KEY, res.token);
  });
});
</script>
