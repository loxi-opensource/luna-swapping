<template>
  <div>
    <el-card class="!border-none" shadow="never">
      <el-alert class="text-xxl" type="warning" :closable="false" show-icon>
        <template v-slot:title>
          <div class="py-[3px]">温馨提示：</div>
          <div class="py-[3px]">
            1.版本更新需要逐个版本更新，
            <span class="text-[#f56c6c]">
              更新前请备份好系统和数据库，更新成功后需要强制刷新站点；
            </span>
          </div>
          <div class="py-[3px]">
            2.系统没有做二次开发，可以直接选择在线更新功能；
          </div>
          <div class="py-[3px]">
            3.系统已做二次开发，进行了功能修改，请谨慎选择在线更新功能，推荐以更新包的形式手动更新；
          </div>
          <!-- <div class="py-[3px]">
                        4.由于更换新域名原因，1.3.1及以下版本，需修改server/app/adminapi/logic/settings/system/UpgradeLogic.php文件中的BASE_URL为https://server.likeshop.cn
                        否则系统更新列表会报错。
                    </div> -->
        </template>
      </el-alert>
    </el-card>
    <el-card class="!border-none mt-4" shadow="never" v-loading="pager.loading">
      <div>
        <el-table :data="pager.lists" size="large" border>
          <el-table-column
            prop="publish_time"
            label="发布日期"
            min-width="180px"
          >
          </el-table-column>
          <el-table-column label="版本号" min-width="160px">
            <template v-slot="scope">
              <div class="flex items-center">
                <div class="mr-2">{{ scope.row.version_no }}</div>
                <el-tag
                  v-if="scope.$index == 0 && pager.page == 1"
                  size="small"
                  type="primary"
                >
                  最新
                </el-tag>
              </div>
              <div class="">
                {{ scope.row.version_str }}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="" label="版本内容" min-width="220px">
            <template v-slot="scope">
              <div class="" v-for="(item, index) in scope.row.add" :key="index">
                {{ item }}
              </div>
              <div
                class=""
                v-for="(item, index) in scope.row.optimize"
                :key="index"
              >
                {{ item }}
              </div>
              <div
                class=""
                v-for="(item, index) in scope.row.repair"
                :key="index"
              >
                {{ item }}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="notice" label="注意事项" min-width="220px">
            <template v-slot="scope">
              <div
                class="text-[red]"
                v-for="(item, index) in scope.row.notice"
                :key="index"
              >
                {{ item }}
              </div>
            </template>
          </el-table-column>
          <el-table-column label="操作" width="160px" fixed="right">
            <template v-slot="scope">
              <div class="operation flex flex-wrap">
                <div v-perms="['setting.system.upgrade/upgrade']">
                  <el-button
                    v-if="scope.row.able_update == 1"
                    type="primary"
                    link
                    @click="onOuterVisible(scope.row.id)"
                  >
                    一键更新
                  </el-button>
                </div>
                <div v-perms="['setting.system.upgrade/downloadPkg']">
                  <el-button
                    v-if="scope.row.integral_package_link"
                    type="primary"
                    link
                    @click="toPackage(scope.row.id, 6)"
                  >
                    下载完整安装包
                  </el-button>
                </div>
                <div v-perms="['setting.system.upgrade/downloadPkg']">
                  <el-button
                    v-if="scope.row.package_link"
                    type="primary"
                    link
                    @click="toPackage(scope.row.id, 2)"
                  >
                    下载服务端更新包
                  </el-button>
                </div>
                <div v-perms="['setting.system.upgrade/downloadPkg']">
                  <el-button
                    v-if="scope.row.pc_package_link"
                    type="primary"
                    link
                    @click="toPackage(scope.row.id, 3)"
                  >
                    下载pc端更新包
                  </el-button>
                </div>
                <div v-perms="['setting.system.upgrade/downloadPkg']">
                  <el-button
                    v-if="scope.row.uniapp_package_link"
                    type="primary"
                    link
                    @click="toPackage(scope.row.id, 4)"
                  >
                    下载uniapp更新包
                  </el-button>
                </div>
                <div v-perms="['setting.system.upgrade/downloadPkg']">
                  <el-button
                    v-if="scope.row.web_package_link"
                    type="primary"
                    link
                    @click="toPackage(scope.row.id, 5)"
                  >
                    下载后台前端更新包
                  </el-button>
                </div>
              </div>
            </template>
          </el-table-column>
        </el-table>
      </div>
      <div class="flex mt-4 justify-end">
        <pagination v-model="pager" @change="getLists" />
      </div>
    </el-card>
    <el-dialog
      v-model="state.outerVisible"
      width="400px"
      title="系统是否进行二次开发"
      center
    >
      <div class="flex flex-col justify-between items-center">
        <div class="flex flex-col pb-8">
          <div>
            <el-button class="w-full" type="primary" @click="notSecondaryDev">
              未做二次开发，直接更新
            </el-button>
          </div>
          <div class="mt-2.5">
            <el-button class="w-full" @click="secondaryDev"
              >已做二次开发</el-button
            >
          </div>
          <div class="mt-2.5">
            <el-button class="w-full" @click="state.outerVisible = false">
              取消更新
            </el-button>
          </div>
        </div>
      </div>
    </el-dialog>
    <el-dialog width="400px" v-model="state.innerVisible" append-to-body center>
      <div>
        <div v-if="state.isSecondaryDev == false" style="text-align: center">
          一键更新导致的系统问题，欢迎前往社区反馈，请做好系统备份！
        </div>
        <div v-else style="text-align: center">
          <div>二次开发后请谨慎使用一键更新功能！</div>
          <div>
            二次开发后一键更新导致的系统问题，官方无法处理，请做好系统备份！
          </div>
        </div>
      </div>
      <template v-slot:footer>
        <div>
          <el-button type="primary" @click="confirmUpdate">
            确定更新
          </el-button>
          <el-button @click="state.innerVisible = false"> 取消更新 </el-button>
        </div>
        <div class="mt-2.5" v-if="state.isSecondaryDev">
          <el-button @click="state.innerVisible = false">
            下载更新包，手动更新
          </el-button>
        </div>
      </template>
    </el-dialog>
    <el-dialog
      width="300px"
      v-model="state.threeVisible"
      append-to-body
      title="更新中"
      center
      :close-on-click-modal="false"
      :close-on-press-escape="false"
    >
      <div class="pt-10" style="height: 200px; text-align: center">
        <div
          v-loading="state.loading"
          element-loading-text="系统更新中，更新完毕后会自行刷新页面 切勿关闭窗口或刷新页面"
        ></div>
      </div>
    </el-dialog>
  </div>
</template>
<script setup lang="ts">
import {
  getUpgradeLists,
  upgrade,
  upgradeDownloadPkg,
} from "@/api/setting/update";
import { useLockFn } from "@/hooks/useLockFn";
import { usePaging } from "@/hooks/usePaging";

const state = reactive({
  id: "",
  outerVisible: false,
  loading: false,
  isSecondaryDev: false,
  threeVisible: false,
  innerVisible: false,
});

const { pager, getLists } = usePaging({
  fetchFun: getUpgradeLists,
});

const { lockFn: toPackage } = useLockFn(async (id: any, type: any) => {
  const res = await upgradeDownloadPkg({
    id: id,
    update_type: type, // 2:服务端更新包下载，3:pc端更新包下载，4:uniapp更新包下载,5:后台前端更新包下载, 6:完整包更新
  });
  if (type == 6) {
    window.open(res.line, "_blank");
  } else {
    window.location.href = res.line;
  }
});

const onOuterVisible = (id: any) => {
  state.outerVisible = true;
  state.id = id;
};
const confirmUpdate = () => {
  state.innerVisible = false;
  state.threeVisible = true;
  systemUpgrade();
};

// 未做二次开发
const notSecondaryDev = () => {
  state.outerVisible = false;
  state.isSecondaryDev = false;
  state.innerVisible = true;
};
// 已做二次开发
const secondaryDev = () => {
  state.outerVisible = false;
  state.isSecondaryDev = true;
  state.innerVisible = true;
};

const { lockFn: systemUpgrade } = useLockFn(async () => {
  try {
    state.loading = true;
    await upgrade({
      id: state.id,
      update_type: 1, // 1:一键更新
    });
    getLists();
  } catch (error) {}
  state.threeVisible = false;
  state.innerVisible = false;
  state.outerVisible = false;
});
onMounted(() => {
  getLists();
});
</script>
