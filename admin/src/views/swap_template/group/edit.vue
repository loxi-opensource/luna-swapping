<template>
  <div class="edit-popup">
    <popup
      ref="popupRef"
      :title="popupTitle"
      :async="true"
      width="400px"
      @confirm="handleSubmit"
      @close="handleClose"
    >
      <el-form
        ref="formRef"
        :model="formData"
        label-width="84px"
        :rules="formRules"
      >
        <el-form-item label="名称" prop="name">
          <el-input
            v-model="formData.name"
            placeholder="请输入名称"
            clearable
          />
        </el-form-item>
        <el-form-item label="类型" prop="is_collection">
          <el-radio-group
            v-model="formData.is_collection"
            :disabled="mode == 'edit'"
          >
            <el-radio :label="0">单张模板</el-radio>
            <el-radio :label="1">合辑模板</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="换脸模式" prop="is_group_swap">
          <el-radio-group
            v-model="formData.is_group_swap"
            :disabled="mode == 'edit'"
          >
            <el-radio :label="0">单人换脸</el-radio>
            <el-radio :label="1">多人换脸</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="状态" prop="status">
          <el-radio-group v-model="formData.status">
            <el-radio :label="1">开启</el-radio>
            <el-radio :label="0">禁用</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <el-card v-if="mode == 'edit'">
        <div class="flex items-center justify-between gap-2">
          <span class="text-xl"> 当前模板数量：{{ templateCnt }} </span>
          <el-button
            type="success"
            @click="handleOpenGroupDetail(formData.id)"
            :icon="Right"
          >
            模板列表
          </el-button>
        </div>
      </el-card>
    </popup>
    <group-detail
      v-if="showDetails"
      ref="detailsRef"
      @close="showDetails = false"
    />
  </div>
</template>
<script lang="ts" setup>
import type { FormInstance } from "element-plus";
import {
  templateGroupAdd,
  templateGroupDetail,
  templateGroupEdit,
} from "@/api/swap_template/template_group";
import Popup from "@/components/popup/index.vue";
import { Right } from "@element-plus/icons-vue";
import GroupDetail from "./components/GroupDetail.vue";

//弹框ref
const showDetails = ref<boolean>(true);
const detailsRef = shallowRef<InstanceType<typeof GroupDetail>>();
const handleOpenGroupDetail = async (id: number) => {
  showDetails.value = true;
  await nextTick();
  detailsRef.value.open(id);
};

const emit = defineEmits(["success", "close"]);
const formRef = shallowRef<FormInstance>();
const popupRef = shallowRef<InstanceType<typeof Popup>>();
const mode = ref("add");
const popupTitle = computed(() => {
  return mode.value == "edit" ? "编辑模板组" : "新增模板组";
});
const formData = reactive({
  id: "",
  name: "",
  status: 1,
  is_collection: 0,
  is_group_swap: 0,
});

// 多人换脸模式，模板类型一定是单张模板
watch(
  () => formData.is_group_swap,
  (val) => {
    if (val == 1) {
      formData.is_collection = 0;
    }
  }
);
// 合辑模板，模板类型一定是单人换脸
watch(
  () => formData.is_collection,
  (val) => {
    if (val == 1) {
      formData.is_group_swap = 0;
    }
  }
);

const formRules = {
  name: [
    {
      required: true,
      message: "请输入",
      trigger: ["blur"],
    },
  ],
};

const handleSubmit = async () => {
  await formRef.value?.validate();
  mode.value == "edit"
    ? await templateGroupEdit(formData)
    : await templateGroupAdd(formData);
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
const templateCnt = ref(0);
const getDetail = async (row: Record<string, any>) => {
  const data = await templateGroupDetail({
    id: row.id,
  });
  setFormData(data);
  templateCnt.value = data.template_cnt;
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
