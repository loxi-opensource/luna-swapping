<template>
  <div class="edit-popup">
    <popup
      ref="popupRef"
      :title="popupTitle"
      :async="true"
      width="800px"
      @confirm="handleSubmit"
      @close="handleClose"
    >
      <el-form
        ref="formRef"
        :model="formData"
        label-width="84px"
        :rules="formRules"
      >
        <el-form-item
          label="目标图"
          prop="up_template_id"
          required
          v-if="!formData.is_collection"
        >
          <div class="flex items-center gap-4 w-5/6 min-w-[50%]">
            <luna-image-uploader
              :is-material-file="true"
              @uploadSuccess="handleUploadSuccess"
              v-if="mode == 'add'"
            />
            <luna-image-shower
              :is-material-file="true"
              :file-id="formData.up_template_id"
              :file-url="formData.image_url"
              :face-data="formData.face_list"
              v-if="formData.image_url && mode == 'edit'"
            />
            <el-button
              @click="handleOpenTester()"
              v-if="formData.image_url && formData.up_template_id"
              type="primary"
              plain
              :icon="MagicStick"
            >
              测试
            </el-button>
          </div>
        </el-form-item>
        <el-form-item v-else label="封面图" prop="image_url" required>
          <material-picker
            v-model="formData.image_url"
            :limit="1"
          ></material-picker>
        </el-form-item>
        <el-form-item label="模板名称" prop="name">
          <el-input
            v-model="formData.name"
            placeholder="请输入模板名称"
            clearable
            class="w-1/3"
          />
        </el-form-item>
        <el-form-item label="模板类型" prop="status">
          <el-radio-group
            v-model="formData.is_collection"
            :disabled="mode == 'edit'"
          >
            <el-radio :label="0">单张模板</el-radio>
            <el-radio :label="1">合辑模板</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="状态" prop="status">
          <el-radio-group v-model="formData.status">
            <el-radio :label="1">开启</el-radio>
            <el-radio :label="0">禁用</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
    </popup>
    <luna-template-tester
      v-if="showTester"
      ref="testerRef"
      :target-file="{
        id: formData.up_template_id,
        url: formData.image_url,
        faceList: formData.face_list,
      }"
    ></luna-template-tester>
  </div>
</template>
<script lang="ts" setup>
import type { FormInstance } from "element-plus";
import {
  templateAdd,
  templateDetail,
  templateEdit,
} from "@/api/swap_template/template";
import Popup from "@/components/popup/index.vue";
import LunaImageUploader from "./components/LunaImageUploader.vue";
import type { UploadSuccessData } from "./components/lunaImageUploader.vue";
import LunaTemplateTester from "./components/LunaTemplateTester.vue";
import { MagicStick } from "@element-plus/icons-vue";
import LunaImageShower from "./components/LunaImageShower.vue";

//弹框ref
const showTester = ref<boolean>(true);
const testerRef = shallowRef<InstanceType<typeof LunaTemplateTester>>();
const handleOpenTester = async () => {
  showTester.value = true;
  await nextTick();
  testerRef.value.open();
};

const emit = defineEmits(["success", "close"]);
const formRef = shallowRef<FormInstance>();
const popupRef = shallowRef<InstanceType<typeof Popup>>();
const mode = ref("add");
const popupTitle = computed(() => {
  return mode.value == "edit" ? "编辑模板" : "新增模板";
});
const formData = reactive({
  id: "",
  name: "",
  status: 1,
  is_collection: 0,
  image_url: "",
  up_template_id: "",
  face_list: [],
});

const formRules = {
  name: [
    {
      required: true,
      message: "请输入",
      trigger: ["blur"],
    },
  ],
};
const handleUploadSuccess = (data: UploadSuccessData) => {
  formData.image_url = data.fileUrl;
  formData.up_template_id = data.fileId;
  formData.face_list = data.faceList;
};

const handleSubmit = async () => {
  await formRef.value?.validate();
  mode.value == "edit"
    ? await templateEdit(formData)
    : await templateAdd(formData);
  popupRef.value?.close();
  emit("success");
};

const open = (type = "add") => {
  mode.value = type;
  popupRef.value?.open();
};

const setFormData = (data: Record<any, any>) => {
  for (const key in formData) {
    if (data[key] != null && data[key] != undefined) {
      //@ts-ignore
      formData[key] = data[key];
    }
  }
};

const getDetail = async (row: Record<string, any>) => {
  const data = await templateDetail({
    id: row.id,
  });
  setFormData(data);
};

const handleClose = () => {
  emit("close");
};

defineExpose({
  open,
  setFormData,
  getDetail,
});
</script>
