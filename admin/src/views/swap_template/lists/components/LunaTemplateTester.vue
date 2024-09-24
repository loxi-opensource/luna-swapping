<script setup lang="ts">
import Popup from "@/components/popup/index.vue";
import type { FormInstance } from "element-plus";
import feedback from "@/utils/feedback";
import type { PropType } from "vue";
import { watch } from "vue";
import { MagicStick } from "@element-plus/icons-vue";
import { submitTaskV3, taskStatus } from "@/api/luna";
import LunaImageUploader, {
  type UploadSuccessData,
} from "./LunaImageUploader.vue";
import config from "@/config";
import LunaImageShower from "./LunaImageShower.vue";
import LoadingSvg from "./LoadingSvg.vue";

const emit = defineEmits(["success", "close"]);
const formRef = shallowRef<FormInstance>();
const popupRef = shallowRef<InstanceType<typeof Popup>>();

interface TargetFile {
  id: string;
  url: string;
  faceList: any[];
}

const props = defineProps({
  targetFile: {
    type: Object as PropType<TargetFile>,
    default: () => ({
      id: "",
      url: "",
      faceList: [],
    }),
  },
});

const formData = reactive({
  userFileId: "",
  targetFileId: props.targetFile.id,
});

watch(
  () => props.targetFile.id,
  (newId) => {
    formData.targetFileId = newId; // 更新 formData 中的值
  }
);

const handleSubmit = async () => {
  clearInterval(pollTimer);
  emit("success");
};

const open = () => {
  popupRef.value?.open();
};

const handleClose = () => {
  resultFileUrl.value = "";
  clearInterval(pollTimer);
  emit("close");
};

let pollTimer: number | undefined;
const resultFileUrl = ref("");
const ossHost = config.lunaOssBaseUrl;
const generationLoading = ref(false);
const generationLoadingText = computed(() => {
  return generationLoading.value
    ? "正在处理中..."
    : "上传用户图，点击【立即生成】";
});
const onGenerate = () => {
  if (!formData.userFileId) {
    feedback.msgError("请先上传用户图");
    return;
  }

  console.log("onGenerate", formData);
  generationLoading.value = true;
  const templateFaceMapping = [
    {
      targetFileId: props.targetFile.id,
      mapping: {
        [props.targetFile.faceList[0].id]: userFile.value.faceList[0].id,
      },
    },
  ];
  submitTaskV3(templateFaceMapping).then((res) => {
    console.log("submitTask", res);
    // 轮询任务状态
    const taskId = res.messageId;
    const pollTask = () => {
      taskStatus({
        messageId: taskId,
      }).then((response) => {
        if (!response?.status) {
          return;
        }
        if (response.status === 1) {
          feedback.msgSuccess("生成成功");
          clearInterval(pollTimer);
          resultFileUrl.value =
            ossHost + "/" + response.messageList[0].sourceFilePath;
          generationLoading.value = false;
        }
        if (response.status === -1 || response.status === -2) {
          // 消息状态 -1：失败 0：等待中 1：成功 -2：未找到人脸
          feedback.msgError(
            "生成失败请重试 " + response.status == "-1"
              ? "-1失败"
              : "-2未找到人脸" + " " + response.errorMsg || "-"
          );
          clearInterval(pollTimer);
          generationLoading.value = false;
        }
      });
    };
    pollTimer = setInterval(pollTask, 1000);
  });
};

const userFile = ref(null);
const handleUploadSuccess = (data: UploadSuccessData) => {
  userFile.value = data;
  console.log("userfile", userFile.value);
  formData.userFileId = data.fileId;
};

defineExpose({
  open,
});
</script>

<template>
  <div class="edit-popup">
    <popup
      ref="popupRef"
      title="换脸测试"
      :async="true"
      width="950px"
      @confirm="handleSubmit"
      @close="handleClose"
    >
      <div class="flex gap-4">
        <el-form
          ref="formRef"
          :model="formData"
          label-width="84px"
          class="w-1/2"
        >
          <el-form-item
            label="目标图"
            prop="targetFileId"
            required
            class="h-[260px] w-full"
          >
            <luna-image-shower
              :is-material-file="true"
              :fileId="props.targetFile.id"
              :fileUrl="props.targetFile.url"
              :faceData="props.targetFile.faceList"
              class="w-full h-full"
            />
          </el-form-item>
          <el-form-item
            label="用户图"
            prop="userFileId"
            required
            class="h-[300px] w-full"
          >
            <luna-image-uploader
              :is-material-file="false"
              @uploadSuccess="handleUploadSuccess"
              class="w-full h-full"
            />
          </el-form-item>
        </el-form>
        <el-card class="flex-1 h-[578px]">
          <div class="flex flex-col gap-2.5 items-center justify-center h-full">
            <el-image
              v-if="resultFileUrl"
              :src="resultFileUrl"
              fit="cover"
              class="w-[375px] h-[500px] rounded-lg"
            ></el-image>
            <loading-svg
              :loading-text="generationLoadingText"
              class="w-[375px] h-[500px]"
              v-else
            />
            <div class="flex justify-between w-[360px] items-center">
              <span class="text-lg">结果图</span>
              <el-button
                type="primary"
                @click="onGenerate"
                :icon="MagicStick"
                :loading="generationLoading"
              >
                {{
                  generationLoading ? "AI换脸中，请稍等20秒左右" : "立即生成"
                }}
              </el-button>
            </div>
          </div>
        </el-card>
      </div>
    </popup>
  </div>
</template>
