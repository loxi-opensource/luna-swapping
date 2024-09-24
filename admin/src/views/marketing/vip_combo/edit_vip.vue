<template>
  <div>
    <el-card class="!border-none" shadow="never">
      <el-page-header :content="title" @back="$router.back()" />
    </el-card>
    <el-form
      class="mt-4"
      ref="formRef"
      :model="formData"
      label-width="120px"
      :rules="formRules"
    >
      <el-card shadow="never" class="!border-none">
        <div class="text-lg font-medium">套餐信息</div>
        <div class="mt-4">
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
        </div>
      </el-card>
      <el-card shadow="never" class="!border-none mt-4">
        <div class="text-lg font-medium">会员权益</div>
        <div class="mt-4">
          <el-form-item label="会员权益">
            <el-checkbox-group v-model="formData.benefits_ids">
              <el-checkbox
                v-for="(item, index) in optionsData.benefitsLists"
                :key="index"
                :label="item.id"
              >
                {{ item.name }}
              </el-checkbox>
            </el-checkbox-group>
          </el-form-item>
        </div>
      </el-card>
      <el-card shadow="never" class="!border-none mt-4">
        <div class="text-lg font-medium">上限设置</div>
        <div class="mt-4">
          <el-form-item label="每日对话上限">
            <div>
              <el-input
                v-model="formData.chat_limit"
                placeholder="请输入每日对话上限次数"
                class="w-[360px]"
              ></el-input>
              <div class="form-tips">请输入大于0的次数，不填则表示不限制</div>
            </div>
          </el-form-item>
        </div>
      </el-card>
      <el-card shadow="never" class="!border-none mt-4">
        <div class="text-lg font-medium">额外赠送</div>
        <div class="mt-4">
          <el-form-item label="赠送对话条数">
            <div>
              <el-input
                v-model="formData.give_chat_number"
                placeholder="为空或者填0表示不赠送"
                class="w-[360px]"
              >
                <template #append>条</template>
              </el-input>
            </div>
          </el-form-item>
          <el-form-item label="赠送绘画条数">
            <div>
              <el-input
                v-model="formData.give_draw_number"
                placeholder="为空或者填0表示不赠送"
                class="w-[360px]"
              >
                <template #append>条</template>
              </el-input>
            </div>
          </el-form-item>
        </div>
      </el-card>
      <footer-btns>
        <el-button type="primary" @click="handleSubmit">保存</el-button>
      </footer-btns>
    </el-form>
  </div>
</template>

<script setup lang="ts">
import {
  addMenmber,
  detialMenmber,
  editlMenmber,
  getBenefitsListsAll,
} from "@/api/marketing/vip";
import { useDictOptions } from "@/hooks/useDictOptions";

const router = useRouter();
const { query } = useRoute();
const title = computed(() => {
  return query.id ? "编辑会员套餐" : "新增会员套餐";
});
//表单ref
const formRef = shallowRef();

//表单数据
const formData = ref({
  id: "",
  name: "",
  duration: "",
  is_perpetual: 0,
  sell_price: "",
  lineation_price: "",
  give_draw_number: "",
  is_retrieve: 0,
  retrieve_amount: "",
  benefits_ids: [],
  sort: 0,
  status: 1,
  chat_limit: "",
  give_chat_number: "",
});

//表单校验规则
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

const { optionsData } = useDictOptions<{
  benefitsLists: any[];
}>({
  benefitsLists: {
    api: getBenefitsListsAll,
  },
});
//获取套餐详情
const getDetail = async (id: number) => {
  const data = await detialMenmber({
    id,
  });
  formData.value = data;
};

//提交
const handleSubmit = async () => {
  await formRef.value?.validate();
  query.id
    ? await editlMenmber(formData.value)
    : await addMenmber(formData.value);
  router.back();
};

onMounted(() => {
  query.id && getDetail(Number(query.id));
});
</script>

<style scoped lang="scss"></style>
