<template>
  <div class="edit-popup">
    <popup
      ref="popupRef"
      title="玩法策略 / 关联模板组"
      :async="true"
      width="920px"
      @confirm="handleSubmit"
      @close="handleClose"
    >
      <div class="flex flex-wrap">
        <el-form inline>
          <el-form-item label="玩法:">
            <span class="font-bold">{{ usageDetails.name }}</span>
          </el-form-item>
          <el-form-item label="已关联模板组数量:">
            <span class="font-bold text-xl">
              {{ usageDetails.group_cnt }}个
            </span>
          </el-form-item>
        </el-form>
      </div>
      <div>
        <el-form ref="formRef" class="mt-4" :model="queryParams" :inline="true">
          <el-form-item label="玩法名称">
            <el-input v-model="queryParams.name"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button plain @click="resetPage">查询</el-button>
            <el-button
              @click="
                () => {
                  queryParams.name = '';
                  getLists();
                }
              "
              >重置
            </el-button>
            <el-button
              type="primary"
              :icon="Plus"
              @click="handleAddTemplateToGroup(queryParams.id)"
            >
              添加模版
            </el-button>
          </el-form-item>
        </el-form>
        <div>
          <el-table
            size="large"
            height="400px"
            v-loading="pager.loading"
            :data="pager.lists"
            empty-text="点击上方按钮【添加模版组】开始添加吧"
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
            <el-table-column label="排序" prop="sort" width="80" />
            <el-table-column label="添加时间" prop="create_time" width="180" />
            <el-table-column label="操作" fixed="right" width="200">
              <template #default="{ row }">
                <el-button
                  size="small"
                  type="primary"
                  @click="handleEdit(row)"
                  :icon="EditPen"
                  >排序
                </el-button>
                <el-button
                  size="small"
                  type="danger"
                  :icon="Delete"
                  @click="handleDelete(row.relation_id)"
                  >移除
                </el-button>
              </template>
            </el-table-column>
          </el-table>
          <div class="flex justify-end mt-4">
            <pagination v-model="pager" @change="getLists" />
          </div>
        </div>
      </div>
    </popup>
    <strategy-group-add
      v-if="showDetails"
      ref="addChildRef"
      @success="getLists"
      @close="showDetails = false"
    />
    <strategy-group-edit
      v-if="showEdit"
      ref="editRef"
      @success="getLists"
      @close="showEdit = false"
    />
  </div>
</template>
<script lang="ts" setup>
import type { FormInstance } from "element-plus";
import Popup from "@/components/popup/index.vue";
import { usePaging } from "@/hooks/usePaging";
import {
  removeGroup,
  strategyDetail,
  groupListsInStrategy,
} from "@/api/swap_template/strategy";
import StrategyGroupAdd from "./StrategyGroupAdd.vue";
import StrategyGroupEdit from "./StrategyGroupEdit.vue";
import { Delete, EditPen, Plus } from "@element-plus/icons-vue";
import feedback from "@/utils/feedback";

const showDetails = ref<boolean>(true);
const addChildRef = shallowRef<InstanceType<typeof StrategyGroupAdd>>();
const handleAddTemplateToGroup = async (id: number) => {
  showDetails.value = true;
  await nextTick();
  addChildRef.value.open(id);
};

const emit = defineEmits(["success", "close"]);
//表单ref
const formRef = shallowRef<FormInstance>();
//弹框ref
const popupRef = shallowRef<InstanceType<typeof Popup>>();
//使用详情
const usageDetails = ref<any>({
  name: "",
  group_cnt: 0,
});

//表单数据
const queryParams = reactive<any>({
  id: "",
  name: "",
});

const { pager, getLists, resetPage, resetParams } = usePaging({
  fetchFun: groupListsInStrategy,
  params: queryParams,
});

//获取使用详情
const getUsageDetails = async (id: number) => {
  try {
    const data = await strategyDetail({ id });
    usageDetails.value = data;
  } catch (error) {
    console.log("获取使用详情=>", error);
  }
};

//提交表单
const handleSubmit = async () => {
  try {
    popupRef.value?.close();
    emit("success");
  } catch (error) {
    return error;
  }
};

const handleClose = () => {
  emit("close");
};

//弹框ref
const showEdit = ref<boolean>(false);
const editRef = shallowRef<InstanceType<typeof GroupTemplateEdit>>();
// 编辑
const handleEdit = async (data: any) => {
  showEdit.value = true;
  await nextTick();
  editRef.value?.open();
  editRef.value?.setFormData(data);
};

const handleDelete = async (relation_id: number) => {
  await feedback.confirm("确定要移除？");
  await removeGroup({ relation_id });
  getLists();
};

const open = (id: number) => {
  queryParams.id = id;
  popupRef.value?.open();
  getUsageDetails(id);
  getLists();
};

defineExpose({ open });
</script>
