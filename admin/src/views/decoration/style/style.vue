<template>
  <el-card shadow="never" class="!border-none px-[20px]">
    <div>
      <div class="text-xl font-medium">系统主题色</div>
      <div class="mt-[20px] flex">
        <div
          class="py-[18px] px-[20px] flex items-center text-[14px] shadow rounded-lg mr-[20px]"
          :class="{
            isSelect: isSelect == item.id,
            isnSelect: isSelect != item.id,
          }"
          v-for="(item, index) in themeColors"
          :key="index"
          @click="selectThemeColor(item)"
        >
          <div
            class="rounded-full h-[34px] w-[34px]"
            :style="`background: linear-gradient(to right, ${item.color1}, ${item.color2})`"
          ></div>
          <div class="ml-[14px]">{{ item.colorName }}</div>
        </div>
      </div>
    </div>
    <div>
      <div class="text-xl font-medium mt-[40px]">样式设置</div>
      <el-form label-width="140px" class="mt-[20px]">
        <el-form-item label="导航顶部文字颜色">
          <div>
            <el-radio-group v-model="formData.topTextColor">
              <el-radio label="white" size="large">白色</el-radio>
              <el-radio label="black" size="large">黑色</el-radio>
            </el-radio-group>
            <div>
              <span class="form-tips">页面导航栏文字的颜色</span>
              <el-popover placement="right" width="auto" trigger="hover">
                <template #reference>
                  <span class="text-sm ml-2 text-primary">查看示例</span>
                </template>
                <template #default>
                  <img
                    src="@/assets/images/task_page.png"
                    class="w-[200px] h-[230px]"
                  />
                </template>
              </el-popover>
            </div>
          </div>
        </el-form-item>
        <el-form-item label="导航顶部背景颜色">
          <div>
            <color-picker
              :defaultColor="navigationBarDefault"
              v-model="formData.navigationBarColor"
            ></color-picker>
            <div>
              <span class="form-tips"
                >页面顶部导航栏背景颜色，不设置则跟随主题色</span
              >
              <el-popover placement="right" width="auto" trigger="hover">
                <template #reference>
                  <span class="text-sm ml-2 text-primary">查看示例</span>
                </template>
                <template #default>
                  <img
                    src="@/assets/images/task_page.png"
                    class="w-[200px] h-[230px]"
                  />
                </template>
              </el-popover>
            </div>
          </div>
        </el-form-item>
        <el-form-item label="自定义主题颜色" v-if="isSelect == 5">
          <div>
            <color-picker
              v-model="formData.themeColor1"
              default-color="#F8F8F8"
            ></color-picker>
            <color-picker
              v-model="formData.themeColor2"
              default-color="#F8F8F8"
              class="mt-2"
            ></color-picker>
            <div>
              <span class="form-tips"
                >自定义主题渐变色，用于按钮类和主要文字</span
              >
            </div>
          </div>
        </el-form-item>
        <el-form-item label="按钮文字颜色" v-if="isSelect == 5">
          <div>
            <el-radio-group v-model="formData.buttonColor">
              <el-radio label="white" size="large">白色（默认）</el-radio>
              <el-radio label="black" size="large">黑色</el-radio>
            </el-radio-group>
          </div>
        </el-form-item>
      </el-form>
    </div>
    <div>
      <div class="flex" v-if="isSelect == '1'">
        <img class="w-[200px]" src="@/assets/images/blue1.png" />
        <img class="w-[200px] ml-[30px]" src="@/assets/images/blue2.png" />
        <img class="w-[200px] ml-[30px]" src="@/assets/images/blue3.png" />
      </div>
      <div class="flex" v-if="isSelect == '2'">
        <img class="w-[200px]" src="@/assets/images/green1.png" />
        <img class="w-[200px] ml-[30px]" src="@/assets/images/green2.png" />
        <img class="w-[200px] ml-[30px]" src="@/assets/images/green3.png" />
      </div>
      <div class="flex" v-if="isSelect == '3'">
        <img class="w-[200px]" src="@/assets/images/purple1.png" />
        <img class="w-[200px] ml-[30px]" src="@/assets/images/purple2.png" />
        <img class="w-[200px] ml-[30px]" src="@/assets/images/purple3.png" />
      </div>
      <div class="flex" v-if="isSelect == '4'">
        <img class="w-[200px]" src="@/assets/images/yellow1.png" />
        <img class="w-[200px] ml-[30px]" src="@/assets/images/yellow2.png" />
        <img class="w-[200px] ml-[30px]" src="@/assets/images/yellow3.png" />
      </div>
    </div>
  </el-card>
  <footer-btns class="mt-4" :fixed="true" v-perms="['decorate:style:save']">
    <el-button type="primary" @click="setData">保存</el-button>
  </footer-btns>
</template>
<script setup lang="ts">
import { getDecoratePages, setDecoratePages } from "@/api/decoration";
//主题色接口
interface IthemeColors {
  id: number;
  colorName: string;
  color1: string;
  color2: string;
}

//主题色
const themeColors = ref<IthemeColors[]>([
  {
    id: 1,
    colorName: "科技蓝",
    color1: "#2F80ED",
    color2: "#56CCF2",
  },
  { id: 2, colorName: "偏绿蓝", color1: "#02AAB0", color2: "#00CDAC" },
  { id: 3, colorName: "梦幻紫", color1: "#A74BFD", color2: "#CB60FF" },
  { id: 4, colorName: "高级黄", color1: "#FFC94D", color2: "#FFB814" },
  { id: 5, colorName: "自定义", color1: "#F8F8F8", color2: "#F5F5F5" },
]);
//被选中的主题
const isSelect = ref<number>(1);
//导航栏默认背景颜色
const navigationBarDefault = ref<string>("");

//选择主题颜色
const selectThemeColor = (value: any) => {
  isSelect.value = value.id;
  formData.value.themeColorId = value.id;
  if (value.id != 5) {
    formData.value.themeColor1 = value.color1;
    formData.value.themeColor2 = value.color2;
  }
  // formData.value.navigationBarColor = value.color1
  navigationBarDefault.value = value.color1;
  formData.value.buttonColor = "white";
  if (value.id == 4) {
    formData.value.buttonColor = "black";
  }
};
//表单数据
const formData = ref({
  themeColorId: 1,
  topTextColor: "1",
  navigationBarColor: "",
  themeColor1: "",
  themeColor2: "",
  buttonColor: "1",
});

//获取数据
const getData = async () => {
  const res = await getDecoratePages({ id: 4 });
  formData.value = JSON.parse(res.data);
  isSelect.value = formData.value.themeColorId;
};

//保存数据
const setData = async () => {
  await setDecoratePages({
    id: 4,
    type: 4,
    data: JSON.stringify(formData.value),
  });
  getData();
};

//初始化数据
onMounted(async () => {
  await getData();
  selectThemeColor(themeColors.value[formData.value.themeColorId - 1]);
});
</script>
<style lang="scss" scoped>
.isSelect {
  border: 1px solid #4153ff;
}

.isnSelect {
  border: 1px solid transparent;
}
</style>
