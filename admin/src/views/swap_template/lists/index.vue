<template>
  <div>
    <el-card shadow="never" class="!border-none">
      <el-form inline>
        <el-form-item label="素材来源">
          <el-segmented
            v-model="queryParams.is_official"
            :options="optionsIsOfficial"
            @change="resetPage"
          >
            <template #default="{ item }">
              <div>{{ item.label }}</div>
            </template>
          </el-segmented>
        </el-form-item>
        <el-form-item label="名称">
          <el-input
            v-model="queryParams.name"
            placeholder="请输入"
            class="w-[140px]"
          />
        </el-form-item>
        <el-form-item label="算法端ID">
          <el-input
            v-model="queryParams.up_template_id"
            placeholder="请输入"
            class="w-[140px]"
          />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="resetPage">查询</el-button>
          <el-button @click="resetParams">重置</el-button>
        </el-form-item>
      </el-form>
      <el-form inline>
        <el-form-item label="模板类型">
          <!--          <el-segmented-->
          <!--            v-model="queryParams.is_collection"-->
          <!--            :options="optionsIsCollection"-->
          <!--            @change="resetPage"-->
          <!--          >-->
          <!--            <template #default="{ item }">-->
          <!--              <div>{{ item.label }}</div>-->
          <!--            </template>-->
          <!--          </el-segmented>-->
          <el-select
            v-model="queryParams.is_collection"
            placeholder="请选择"
            @change="resetPage"
            class="!w-[200px]"
          >
            <el-option
              v-for="item in optionsIsCollection"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-icon><InfoFilled /></el-icon>
          <span class="ml-2 text-info">
            合辑模板由多个相似风格的单张模版组成，可以用于随机盲盒玩法
          </span>
        </el-form-item>
      </el-form>
    </el-card>
    <el-card shadow="never" class="!border-none mt-[14px]">
      <div>
        <el-button type="primary" @click="handleAddTemplate()">
          <template #icon>
            <icon name="el-icon-Plus" />
          </template>
          新增模板
        </el-button>
      </div>
      <el-table
        size="large"
        class="mt-4"
        v-loading="pager.loading"
        :data="pager.lists"
      >
        <el-table-column label="ID" prop="id" />
        <el-table-column label="模板类型" min-width="120">
          <template #default="{ row }">
            <div class="flex gap-2">
              <el-tag v-if="row.is_official" type="success">官方</el-tag>
              <el-tag v-if="row.is_collection" type="warning">合辑</el-tag>
              <el-tag v-else type="info">单张</el-tag>
            </div>
          </template>
        </el-table-column>
        <el-table-column
          label="名称"
          prop="name"
          min-width="180"
          show-overflow-tooltip
        />
        <el-table-column label="封面图">
          <template #default="{ row }">
            <image-preview-inside-cell
              :imageUrl="row.image_url"
              :originWidth="80"
              :popUpwidth="400"
            />
          </template>
        </el-table-column>
        <el-table-column label="状态" prop="status" min-width="80">
          <template #default="{ row }">
            <el-tag v-if="row.status" type="success">开启</el-tag>
            <el-tag v-else type="danger">禁用</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          label="关联模板组"
          min-width="100"
          show-overflow-tooltip
        >
          <template #default="{ row }">
            {{
              row.template_groups && row.template_groups.length
                ? row.template_groups.map((item: any) => item.name).join(", ")
                : "-"
            }}
          </template>
        </el-table-column>
        <el-table-column
          label="子模版数量"
          prop="child_template_cnt"
          min-width="100"
        >
          <template #default="{ row }">
            {{ row.child_template_cnt || "-" }}
          </template>
        </el-table-column>
        <el-table-column label="算法端ID" prop="up_template_id" min-width="100">
          <template #default="{ row }">
            {{ row.up_template_id || "-" }}
          </template>
        </el-table-column>
        <el-table-column label="创建时间" prop="create_time" min-width="180" />
        <el-table-column label="操作" width="140" fixed="right">
          <template #default="{ row }">
            <div class="flex flex-col gap-2 justify-start items-start">
              <el-button
                type="danger"
                link
                :icon="FolderOpened"
                @click="handleUsageDetails(row.id)"
                v-if="row.is_collection"
                style="margin-left: 0"
              >
                子模版库
              </el-button>
              <el-button
                v-else
                @click="handleOpenTester(row)"
                type="warning"
                link
                :icon="MagicStick"
              >
                换脸测试
              </el-button>
              <el-button
                type="primary"
                @click="handleEdit(row)"
                :icon="EditPen"
                link
                style="margin-left: 0"
              >
                编辑模板
              </el-button>
            </div>
          </template>
        </el-table-column>
      </el-table>
      <div class="flex justify-end mt-4">
        <pagination v-model="pager" @change="getLists" />
      </div>
    </el-card>
    <edit-popup
      v-if="showEdit"
      ref="editRef"
      @success="getLists"
      @close="showEdit = false"
    />
    <collection-detail
      v-if="showDetails"
      ref="detailsRef"
      @success="getLists"
      @close="showDetails = false"
    />
    <luna-template-tester
      v-if="showTester"
      ref="testerRef"
      :target-file="targetFile"
    ></luna-template-tester>
  </div>
</template>
<script lang="ts" setup name="templateList">
import { usePaging } from "@/hooks/usePaging";
import { templateLists } from "@/api/swap_template/template";

import EditPopup from "./edit.vue";
import CollectionDetail from "./components/CollectionDetail.vue";
import LunaTemplateTester from "./components/LunaTemplateTester.vue";
import {
  EditPen,
  FolderOpened,
  InfoFilled,
  MagicStick,
} from "@element-plus/icons-vue";

const targetFile = ref();

const queryParams = reactive({
  name: "",
  up_template_id: "",
  is_collection: 0,
  is_official: 1,
});

const optionsIsCollection = [
  { label: "单张模板", value: 0 },
  { label: "合辑模板", value: 1 },
  { label: "全部", value: "" },
];
const optionsIsOfficial = [
  { label: "Luna官方", value: 1 },
  { label: "后台添加", value: 0 },
  { label: "全部", value: "" },
];

//弹框ref
const showEdit = ref<boolean>(false);
const editRef = shallowRef<InstanceType<typeof EditPopup>>();
const showDetails = ref<boolean>(true);
const detailsRef = shallowRef<InstanceType<typeof CollectionDetail>>();
const showTester = ref<boolean>(true);
const testerRef = shallowRef<InstanceType<typeof LunaTemplateTester>>();

//打开弹框
const handleAddTemplate = async () => {
  showEdit.value = true;
  await nextTick();
  editRef.value?.open();
};

const handleUsageDetails = async (id: number) => {
  showDetails.value = true;
  await nextTick();
  detailsRef.value.open(id);
};

const handleOpenTester = async (row) => {
  targetFile.value = {
    id: row.up_template_id,
    url: row.image_url,
    faceList: row.face_list,
  };
  showTester.value = true;
  await nextTick();
  testerRef.value.open();
};

// 编辑
const handleEdit = async (data: any) => {
  showEdit.value = true;
  await nextTick();
  editRef.value?.open("edit");
  editRef.value?.getDetail(data);
};

const { pager, getLists, resetPage, resetParams } = usePaging({
  fetchFun: templateLists,
  params: queryParams,
});

getLists();
</script>
