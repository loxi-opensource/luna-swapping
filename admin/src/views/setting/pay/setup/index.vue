<template>
  <div class="hot-search">
    <el-card class="!border-none" shadow="never">
      <template #header>
        <span class="text-lg font-bold">支付设置</span>
      </template>
      <el-form ref="formRef" :model="formData" label-width="100px">
        <el-form-item label="ios支付功能">
          <div>
            <el-radio-group v-model="formData.is_open" @change="changePay">
              <el-radio :label="1">允许支付</el-radio>
              <el-radio :label="0">不允许支付</el-radio>
            </el-radio-group>
            <div class="form-tips mt-0">该设置仅对IOS系统有效</div>
          </div>
        </el-form-item>
        <el-form-item label="按钮文字">
          <div>
            <el-input
              class="w-[280px]"
              v-model="formData.tips"
              placeholder="请输入"
              :clearable="true"
            >
            </el-input>
          </div>
        </el-form-item>
      </el-form>
    </el-card>

    <footer-btns v-perms="['setting.pay.PayConfig/setIosPayConfig']">
      <el-button type="primary" @click="handleSave">保存</el-button>
    </footer-btns>
  </div>
</template>

<script setup lang="ts" name="paySetup">
import { getIosPayConfig, setIosPayConfig } from "@/api/setting/pay";

const formData = reactive({
  is_open: 1,
  tips: "",
});

const changePay = (value: number) => {
  if (value == 1) {
    formData.tips = "立即支付";
  } else {
    formData.tips = "由于相关规范IOS暂不支持购买";
  }
};

// 获取登录注册数据
const getData = async () => {
  try {
    const data = await getIosPayConfig();
    for (const key in formData) {
      //@ts-ignore
      formData[key] = data[key];
    }
  } catch (error) {
    console.log("获取=>", error);
  }
};

const handleSave = async () => {
  try {
    await setIosPayConfig(formData);
    getData();
  } catch (error) {
    console.log("保存=>", error);
  }
};

getData();
</script>

<style lang="scss" scoped></style>
