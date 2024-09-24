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
      <el-form ref="formRef" :model="formData" label-width="84px">
        <el-form-item label="排序" prop="sort">
          <div>
            <el-input-number v-model="formData.sort" :min="0" :max="9999" />
            <div class="form-tips">默认为0， 数值越大越排前</div>
          </div>
        </el-form-item>
      </el-form>
    </popup>
  </div>
</template>
<script lang="ts" setup>
import type { FormInstance } from "element-plus";
import { templateRelationEdit } from "@/api/swap_template/template_group";
import Popup from "@/components/popup/index.vue";

const emit = defineEmits(["success", "close"]);
const formRef = shallowRef<FormInstance>();
const popupRef = shallowRef<InstanceType<typeof Popup>>();
const popupTitle = ref("修改排序");
const formData = reactive({
  relation_id: "",
  sort: "",
});

const handleSubmit = async () => {
  await formRef.value?.validate();
  await templateRelationEdit(formData);
  popupRef.value?.close();
  emit("success");
};

const open = () => {
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

const handleClose = () => {
  emit("close");
};

defineExpose({
  open,
  setFormData,
});
</script>
