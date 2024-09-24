<template>
  <div class="workbench">
    <div class="lg:flex">
      <el-card class="!border-none mb-4 lg:mr-4 lg:w-[400px]" shadow="never">
        <template #header>
          <span class="card-title">版本信息</span>
        </template>
        <div>
          <div class="flex leading-9">
            <div class="w-20">网站名称</div>
            <span>{{ config.web_name }}</span>
          </div>
        </div>
      </el-card>
      <el-card class="!border-none mb-4 flex-1" shadow="never">
        <template #header>
          <div>
            <span class="card-title">今日数据</span>
            <span class="ml-4 text-xs text-tx-secondary">
              更新时间：{{ workbenchData.today.time }}
            </span>
          </div>
        </template>

        <div class="flex flex-wrap">
          <div class="w-1/2 md:w-1/4">
            <div class="leading-10">今日收入</div>
            <div class="text-6xl">{{ workbenchData.today.today_sales }}</div>
            <div class="text-base">
              <span class="text-[#111111]"
                >昨日: {{ workbenchData.today.yesterday_sales }}</span
              >
              <span
                class="ml-3 text-[#ff2735]"
                v-if="
                  workbenchData.today.today_sales >
                  workbenchData.today.yesterday_sales
                "
              >
                涨:
                {{
                  getPrice(
                    workbenchData.today.today_sales -
                      workbenchData.today.yesterday_sales
                  )
                }}
              </span>
              <span
                class="ml-3 text-[#389e0d]"
                v-if="
                  workbenchData.today.today_sales <
                  workbenchData.today.yesterday_sales
                "
              >
                降:
                {{
                  getPrice(
                    workbenchData.today.yesterday_sales -
                      workbenchData.today.today_sales
                  )
                }}
              </span>
            </div>
          </div>
          <div class="w-1/2 md:w-1/4">
            <div class="leading-10">订单数量</div>
            <div class="text-6xl">
              {{ workbenchData.today.today_order_num }}
            </div>
            <div class="text-base">
              <span class="text-[#111111]"
                >昨日: {{ workbenchData.today.yesterday_order_num }}</span
              >
              <span
                class="ml-3 text-[#ff2735]"
                v-if="
                  workbenchData.today.today_order_num >
                  workbenchData.today.yesterday_order_num
                "
              >
                涨:
                {{
                  (
                    workbenchData.today.today_order_num -
                    workbenchData.today.yesterday_order_num
                  ).toFixed(0)
                }}
              </span>
              <span
                class="ml-3 text-[#389e0d]"
                v-if="
                  workbenchData.today.today_order_num <
                  workbenchData.today.yesterday_order_num
                "
              >
                降:
                {{
                  (
                    workbenchData.today.yesterday_order_num -
                    workbenchData.today.today_order_num
                  ).toFixed(0)
                }}
              </span>
            </div>
          </div>
          <div class="w-1/2 md:w-1/4">
            <div class="leading-10">新增用户</div>
            <div class="text-6xl">{{ workbenchData.today.today_new_user }}</div>
            <div class="text-base">
              <span class="text-[#111111]"
                >昨日: {{ workbenchData.today.yesterday_new_user }}</span
              >
              <span
                class="ml-3 text-[#ff2735]"
                v-if="
                  workbenchData.today.today_new_user >
                  workbenchData.today.yesterday_new_user
                "
              >
                涨:
                {{
                  (
                    workbenchData.today.today_new_user -
                    workbenchData.today.yesterday_new_user
                  ).toFixed(0)
                }}
              </span>
              <span
                class="ml-3 text-[#389e0d]"
                v-if="
                  workbenchData.today.today_new_user <
                  workbenchData.today.yesterday_new_user
                "
              >
                降:
                {{
                  (
                    workbenchData.today.yesterday_new_user -
                    workbenchData.today.today_new_user
                  ).toFixed(0)
                }}
              </span>
            </div>
          </div>
          <div class="w-1/2 md:w-1/4">
            <div class="leading-10">新增访问量</div>
            <div class="text-6xl">{{ workbenchData.today.today_visitor }}</div>
            <div class="text-base">
              <span class="text-[#111111]"
                >昨日: {{ workbenchData.today.yesterday_visitor }}</span
              >
              <span
                class="ml-3 text-[#ff2735]"
                v-if="
                  workbenchData.today.today_visitor >
                  workbenchData.today.yesterday_visitor
                "
              >
                涨:
                {{
                  (
                    workbenchData.today.today_visitor -
                    workbenchData.today.yesterday_visitor
                  ).toFixed(0)
                }}
              </span>
              <span
                class="ml-3 text-[#389e0d]"
                v-if="
                  workbenchData.today.today_visitor <
                  workbenchData.today.yesterday_visitor
                "
              >
                降:
                {{
                  (
                    workbenchData.today.yesterday_visitor -
                    workbenchData.today.today_visitor
                  ).toFixed(0)
                }}
              </span>
            </div>
          </div>
        </div>
      </el-card>
    </div>
    <div class="function lg:flex">
      <el-card class="!border-none lg:flex-1 lg:ml-4 mb-4" shadow="never">
        <template #header>
          <span>常用功能</span>
        </template>
        <div class="flex flex-wrap">
          <div
            v-for="item in workbenchData.menu"
            class="flex flex-col items-center w-1/4 md:flex-1"
            :key="item"
          >
            <router-link :to="item.url" class="flex flex-col items-center mb-3">
              <image-contain width="40px" height="40px" :src="item?.image" />
              <div class="mt-2">{{ item.name }}</div>
            </router-link>
          </div>
        </div>
      </el-card>
      <el-card class="!border-none lg:flex-1 mb-4" shadow="never">
        <template #header>
          <div>
            <span class="card-title">分销概况</span>
          </div>
        </template>
        <div class="flex flex-wrap text-center">
          <div class="w-1/2 md:w-1/4">
            <router-link to="/yingyong/distribution/distributor">
              <div class="text-6xl">{{ workbenchData.wait.distributor }}</div>
              <div class="leading-10">分销商</div>
            </router-link>
          </div>
          <div class="w-1/2 md:w-1/4">
            <router-link to="/yingyong/distribution/apply">
              <div class="text-6xl">
                {{ workbenchData.wait.distributor_apply }}
              </div>
              <div class="leading-10">分销申请</div>
            </router-link>
          </div>
          <div class="w-1/2 md:w-1/4">
            <router-link to="/yingyong/distribution/order">
              <div class="text-6xl">
                {{ workbenchData.wait.distributor_order }}
              </div>
              <div class="leading-10">分销订单</div>
            </router-link>
          </div>
          <div class="w-1/2 md:w-1/4">
            <router-link to="/yingyong/distribution/distribution/withdraw">
              <div class="text-6xl">
                {{ workbenchData.wait.withdraw_apply }}
              </div>
              <div class="leading-10">提现申请</div>
            </router-link>
          </div>
        </div>
      </el-card>
    </div>
    <div class="lg:flex">
      <el-card class="!border-none mb-4 lg:flex-1" shadow="never">
        <template #header>
          <span>销售额趋势图</span>
        </template>
        <div>
          <v-charts
            style="height: 350px"
            :option="workbenchData.businessOption"
            :autoresize="true"
          />
        </div>
      </el-card>
      <el-card class="!border-none lg:ml-4 mb-4 lg:flex-1" shadow="never">
        <template #header>
          <span>访问量趋势图</span>
        </template>
        <div>
          <v-charts
            style="height: 350px"
            :option="workbenchData.visitorOption"
            :autoresize="true"
          />
        </div>
      </el-card>
    </div>
  </div>
</template>

<script lang="ts" setup name="workbench">
import { getWorkbench, checkVersion } from "@/api/app";
import useAppStore from "@/stores/modules/app";
import feedback from "@/utils/feedback";
import vCharts from "vue-echarts";

const router = useRouter();
const appStore = useAppStore();
const config = computed(() => appStore.config);
//有/无新版本
const newVersion = ref(false);
// 表单数据
const workbenchData: any = reactive({
  wait: {
    distributor: "",
    distributor_apply: "",
    distributor_order: "",
    withdraw_apply: "",
  },
  version: {
    version: "", // 版本号
    website: "", // 官网
    based: "",
    channel: {
      gitee: "",
      website: "",
    },
  },
  support: [],
  today: {
    today_new_user: 0,
    today_order_num: 0,
    today_sales: 0,
    today_visitor: 0,
    yesterday_new_user: 0,
    yesterday_order_num: 0,
    yesterday_sales: 0,
    yesterday_visitor: 0,
  }, // 今日数据
  menu: [], // 常用功能
  visitor: [], // 访问量
  article: [], // 文章阅读量

  visitorOption: {
    xAxis: {
      type: "category",
      data: [0],
    },
    yAxis: {
      type: "value",
    },
    legend: {
      data: ["访问量"],
    },
    itemStyle: {
      // 点的颜色。
      color: "red",
    },
    tooltip: {
      trigger: "axis",
    },
    series: [
      {
        name: "访问量",
        data: [0],
        type: "line",
        smooth: true,
      },
    ],
  },
  businessOption: {
    xAxis: {
      type: "category",
      data: [0],
    },
    yAxis: {
      type: "value",
    },
    legend: {
      data: ["销售额"],
    },
    itemStyle: {
      // 点的颜色。
      color: "red",
    },
    tooltip: {
      trigger: "axis",
    },
    series: [
      {
        name: "销售额",
        data: [0],
        type: "line",
        smooth: true,
      },
    ],
  },
});

// 获取工作台主页数据
const getData = () => {
  getWorkbench()
    .then((res: any) => {
      workbenchData.version = res.version;
      workbenchData.today = res.today;
      workbenchData.menu = res.menu;
      workbenchData.visitor = res.visitor;
      workbenchData.support = res.support;
      workbenchData.wait = res.wait;

      // 清空echarts 数据
      workbenchData.visitorOption.xAxis.data = [];
      workbenchData.visitorOption.series[0].data = [];

      // 写入从后台拿来的数据
      res.visitor.date.reverse().forEach((item: any) => {
        workbenchData.visitorOption.xAxis.data.push(item);
      });
      res.visitor.list[0].data.reverse().forEach((item: any) => {
        workbenchData.visitorOption.series[0].data.push(item);
      });
      res.business.date.reverse().forEach((item: any) => {
        workbenchData.businessOption.xAxis.data.push(item);
      });
      res.business.list[0].data.reverse().forEach((item: any) => {
        workbenchData.businessOption.series[0].data.push(item);
      });
    })
    .catch((err: any) => {
      console.log("err", err);
    });
};
const concatServer = () => {
  window.open(
    "https://doc.weixin.qq.com/doc/w3_ALYAlwbZADMXTU25MyMQ1CennD5nI?scode=AHwA3AcqAAYExiDwkqALYAlwbZADM"
  );
};

//跳转至更新页面
const toUpdate = () => {
  router.push("/setting/system/update");
};

//检测新版本
const checkNewVersion = async () => {
  const { result } = await checkVersion();
  newVersion.value = result;
};

const isValidDecimal = (decimal: string) => {
  // 检查小数点后是否有两位
  if (!/^\d+\.\d{2}$/.test(decimal)) {
    return false;
  }
  // 检查小数是否以 10,20,30,40,50,60,70,80,90 结尾
  if (/0$/.test(decimal)) {
    return false;
  }
  return true;
};

const getPrice = (price: string) => {
  if (isValidDecimal(price)) {
    return price.toFixed(2);
  } else {
    return price.toFixed(1);
  }
};

onMounted(() => {
  getData();
  checkNewVersion();
});
</script>

<style lang="scss" scoped></style>
