<template>
  <div>
    <el-card shadow="never" class="!border-none">
      <el-form ref="formRef" :model="queryParams" :inline="true">
        <el-form-item label="换脸模式">
          <el-segmented
            v-model="queryParams.is_group_swap"
            :options="optionsIsGroupSwap"
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
        <el-form-item>
          <el-button type="primary" @click="resetPage">查询</el-button>
          <el-button @click="resetParams">重置</el-button>
        </el-form-item>
      </el-form>
      <el-form inline>
        <el-form-item label="模板类型">
          <el-segmented
            v-model="queryParams.is_collection"
            :options="optionsIsCollection"
            @change="resetPage"
          >
            <template #default="{ item }">
              <div>{{ item.label }}</div>
            </template>
          </el-segmented>
        </el-form-item>
      </el-form>
    </el-card>
    <el-card shadow="never" class="!border-none mt-[10px]">
      <div>
        <el-button type="primary" @click="handleAddTemplateGroup()">
          <template #icon>
            <icon name="el-icon-Plus" />
          </template>
          新增
        </el-button>
      </div>
      <el-table
        size="large"
        class="mt-4"
        v-loading="pager.loading"
        :data="pager.lists"
      >
        <el-table-column label="ID" prop="id" width="100" />
        <el-table-column label="模板组类型" width="120">
          <template #default="{ row }">
            {{ row.is_collection ? "合辑" : "单张" }}
          </template>
        </el-table-column>
        <el-table-column label="名称" prop="name" width="140" />
        <el-table-column label="换脸模式" width="120">
          <template #default="{ row }">
            {{ row.is_group_swap ? "多人换脸" : "单人换脸" }}
          </template>
        </el-table-column>
        <el-table-column label="模板数量" prop="template_cnt" width="100" />
        <el-table-column label="状态" prop="sort" width="80">
          <template #default="{ row }">
            <el-tag v-if="row.status" type="success">开启</el-tag>
            <el-tag v-else type="danger">禁用</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="创建时间" prop="create_time" width="180" />
        <el-table-column label="操作" min-width="300">
          <template #default="{ row }">
            <div>
              <el-button
                type="danger"
                link
                :icon="Right"
                @click="handleOpenGroupDetail(row.id)"
              >
                模板列表
              </el-button>
              <el-button
                type="primary"
                link
                @click="handleEdit(row)"
                :icon="EditPen"
              >
                编辑
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
    <group-detail
      v-if="showDetails"
      ref="detailsRef"
      @close="showDetails = false"
    />
  </div>
</template>
<script lang="ts" setup name="redeemCodeList">
import { usePaging } from "@/hooks/usePaging";
import { templateGroupLists } from "@/api/swap_template/template_group";

import EditPopup from "./edit.vue";
import GroupDetail from "./components/GroupDetail.vue";
import { EditPen, Right } from "@element-plus/icons-vue";

const queryParams = reactive({
  name: "",
  is_collection: "",
  is_group_swap: "",
});

const optionsIsCollection = [
  { label: "全部", value: "" },
  { label: "单张模板", value: 0 },
  { label: "合辑模板", value: 1 },
];
const optionsIsGroupSwap = [
  { label: "全部", value: "" },
  { label: "单人换脸", value: 0 },
  { label: "多人换脸", value: 1 },
];

//弹框ref
const showEdit = ref<boolean>(false);
const editRef = shallowRef<InstanceType<typeof EditPopup>>();

//打开弹框
const handleAddTemplateGroup = async () => {
  showEdit.value = true;
  await nextTick();
  editRef.value?.open();
};

//弹框ref
const showDetails = ref<boolean>(true);
const detailsRef = shallowRef<InstanceType<typeof GroupDetail>>();
const handleOpenGroupDetail = async (id: number) => {
  showDetails.value = true;
  await nextTick();
  detailsRef.value.open(id);
};

// 编辑
const handleEdit = async (data: any) => {
  showEdit.value = true;
  await nextTick();
  editRef.value?.open("edit");
  editRef.value?.getDetail(data);
};

const { pager, getLists, resetPage, resetParams } = usePaging({
  fetchFun: templateGroupLists,
  params: queryParams,
});

getLists();
</script>
