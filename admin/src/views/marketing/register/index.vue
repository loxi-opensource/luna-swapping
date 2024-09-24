<template>
  <div class="">
    <el-form
      ref="ruleFormRef"
      :rules="rules"
      :model="formData"
      label-width="140px"
    >
      <el-card shadow="never" class="!border-none">
        <div class="text-xl font-medium mb-[20px]">注册奖励</div>
        <el-form-item label="功能状态" prop="name">
          <div>
            <el-radio-group v-model="formData.status" class="ml-4">
              <el-radio :label="1">开启</el-radio>
              <el-radio :label="0">关闭</el-radio>
            </el-radio-group>
            <div class="form-tips">关闭后，新用户注册将不赠送免费张数</div>
          </div>
        </el-form-item>

        <el-form-item label="作图张数" prop="reward">
          <div>
            <el-input v-model="formData.reward">
              <template #append>条</template>
            </el-input>
            <div class="form-tips">
              新用户注册，免费赠送作图张数；填写0或者为空则表示不赠送
            </div>
          </div>
        </el-form-item>
      </el-card>
    </el-form>
  </div>
  <footer-btns v-perms="['setting.user.user/setRegisterReward']">
    <el-button type="primary" @click="handleSubmit(ruleFormRef)"
      >保存</el-button
    >
  </footer-btns>
</template>
<script setup lang="ts">
import type { FormInstance, FormRules } from "element-plus";
import {
  getRegisterReward,
  setRegisterReward,
} from "@/api/marketing/distribution";

interface formDataInter {
  reward: number | string;
  reward_draw: number | string;
  status: number;
}

//表单ref
const ruleFormRef = ref<FormInstance>();
const formData = ref<formDataInter>({
  reward: "",
  reward_draw: "",
  status: 1,
});

//表单校验规则
const rules = reactive<FormRules>({
  // reward: [{ required: true, message: '请输入新用户注册免费送', trigger: 'blur' }]
});

/**
 * 初始化数据
 */
const getData = async () => {
  formData.value = await getRegisterReward();
};
getData();
/**
 * 提交数据
 */
const handleSubmit = async (formEl: FormInstance | undefined) => {
  if (!formEl) {
    console.log(formEl);
    return;
  }
  try {
    await formEl.validate();
    await setRegisterReward(formData.value);
    await getData();
  } catch (error) {
    console.log(error);
  }
};
</script>
