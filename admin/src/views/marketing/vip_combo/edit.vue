<template>
  <div class="edit-popup">
    <popup
      ref="popupRef"
      :title="popupTitle"
      :async="true"
      width="550px"
      @confirm="handleSubmit"
      @close="handleClose"
    >
      <el-form
        ref="formRef"
        :model="formData"
        label-width="84px"
        :rules="formRules"
      >
        <!-- 套餐名称 -->
        <el-form-item label="套餐名称" prop="name">
          <div class="flex w-full">
            <el-input
              v-model="formData.name"
              placeholder="请输入套餐名称，如：月度会员、季度会员"
              clearable
              class="w-[360px]"
            />
          </div>
        </el-form-item>
        <!-- 套餐时长 -->
        <el-form-item prop="duration">
          <template #label>
            <div>
              <span class="text-error">*</span>
              套餐时长
            </div>
          </template>
          <div class="flex">
            <el-input
              v-model="formData.duration"
              placeholder="请输入整数"
              clearable
              class="w-[360px]"
              :disabled="!!formData.is_perpetual"
            >
              <template #append>个月</template>
            </el-input>
            <el-checkbox
              class="ml-[4px]"
              v-model="formData.is_perpetual"
              :true-label="1"
              :false-label="0"
              >永久</el-checkbox
            >
          </div>
        </el-form-item>
        <!-- 实际售价 -->
        <el-form-item label="实际售价" prop="sell_price">
          <div class="flex">
            <el-input
              v-model="formData.sell_price"
              placeholder="请输入实际售价"
              clearable
              class="w-[360px]"
            >
              <template #append>元</template>
            </el-input>
          </div>
        </el-form-item>
        <!-- 划线价 -->
        <el-form-item label="划线价">
          <div class="w-[360px]">
            <el-input
              v-model="formData.lineation_price"
              clearable
              class="w-[360px]"
              placeholder="请输入划线价"
            >
              <template #append>元</template>
            </el-input>
          </div>
        </el-form-item>
        <!-- 挽回优惠 -->
        <el-form-item label="挽回优惠">
          <div>
            <el-radio-group v-model="formData.is_retrieve" class="ml-4">
              <el-radio :label="1">开启</el-radio>
              <el-radio :label="0">关闭</el-radio>
            </el-radio-group>
            <div class="form-tips flex">
              用户返回上一页点击放弃支付时弹出的优惠金额
              <el-popover placement="right" :width="200" trigger="hover">
                <template #reference>
                  <el-button link type="primary">查看</el-button>
                </template>
                <img src="./images/vip_coupon.jpg" />
              </el-popover>
            </div>
          </div>
        </el-form-item>
        <!-- 优惠金额 -->
        <el-form-item
          label="优惠金额"
          v-if="formData.is_retrieve == 1"
          prop="retrieve_amount"
        >
          <div>
            <el-input
              v-model="formData.retrieve_amount"
              clearable
              class="w-[360px]"
              placeholder="请输入优惠金额"
            >
              <template #append>元</template>
            </el-input>
            <div class="form-tips">
              开启挽回优惠后，用户付款金额=实际售价-优惠金额
            </div>
          </div>
        </el-form-item>
        <!-- 排序 -->
        <el-form-item label="排序">
          <div>
            <el-input class="w-[360px]" v-model="formData.sort"></el-input>
            <div class="form-tips">默认为0，数值越大越排前面</div>
          </div>
        </el-form-item>
        <!-- 是否上架 -->
        <el-form-item label="是否上架" prop="status">
          <div>
            <el-switch
              v-model="formData.status"
              :active-value="1"
              :inactive-value="0"
            />
          </div>
        </el-form-item>
      </el-form>
    </popup>
  </div>
</template>
<script lang="ts" setup>
import type { FormInstance } from "element-plus";
import Popup from "@/components/popup/index.vue";
import { addMenmber, detialMenmber, editlMenmber } from "@/api/marketing/vip";
const emit = defineEmits(["success", "close"]);
const formRef = shallowRef<FormInstance>();
const popupRef = shallowRef<InstanceType<typeof Popup>>();
const mode = ref("add");
const popupTitle = computed(() => {
  return mode.value == "edit" ? "编辑会员套餐" : "新增会员套餐";
});
const formData = ref({
  id: "",
  name: "",
  duration: "",
  is_perpetual: 0,
  sell_price: "",
  lineation_price: "",
  is_retrieve: 0,
  retrieve_amount: "",
  sort: 0,
  status: 1,
});

const formRules = reactive({
  name: [
    {
      required: true,
      message: "请输入套餐名称",
      trigger: ["blur"],
    },
  ],
  duration: [
    {
      validator: (rule: object, value: string, callback: any) => {
        if (!formData.value.is_perpetual && !formData.value.duration) {
          return callback(new Error("请输入套餐时长"));
        }
        callback();
      },
      trigger: ["blur", "change"],
    },
  ],
  sell_price: [
    {
      required: true,
      message: "请输入实际售价",
      trigger: ["blur"],
    },
  ],
  retrieve_amount: [
    {
      required: true,
      message: "请输入优惠金额",
      trigger: ["blur"],
    },
  ],
  status: [
    {
      required: true,
      message: "是否上架",
      trigger: ["blur"],
    },
  ],
});

const handleSubmit = async () => {
  await formRef.value?.validate();
  mode.value == "edit"
    ? await editlMenmber(formData.value)
    : await addMenmber(formData.value);
  popupRef.value?.close();
  emit("success");
};

const open = (type = "add") => {
  mode.value = type;
  popupRef.value?.open();
};

const setFormData = async (row: any) => {
  const data = await detialMenmber({
    id: row.id,
  });
  for (const key in formData) {
    if (data[key] != null && data[key] != undefined) {
      //@ts-ignore
      formData[key] = data[key];
    }
  }
};
const getDetail = async (id: number) => {
  const data = await detialMenmber({
    id,
  });
  formData.value = data;
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
