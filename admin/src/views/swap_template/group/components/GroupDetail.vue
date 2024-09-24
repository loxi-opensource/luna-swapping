<template>
  <div class="edit-popup">
    <popup
      ref="popupRef"
      title="模板组 / 模板列表"
      :async="true"
      width="920px"
      @confirm="handleSubmit"
      @close="handleClose"
    >
      <div class="flex flex-wrap">
        <el-form-item label-width="90px" label="模板组:">
          {{ usageDetails.name }}
        </el-form-item>
        <el-form-item label-width="90px" label="模板数量:">
          {{ usageDetails.template_cnt }}
        </el-form-item>
      </div>
      <div>
        <el-form ref="formRef" class="mt-4" :model="queryParams" :inline="true">
          <el-form-item label="模版名称">
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
            empty-text="点击上方按钮【添加模版】开始添加吧"
          >
            <el-table-column label="ID" prop="id" width="60" />
            <el-table-column label="模板类型" width="100">
              <template #default="{ row }">
                {{ row.is_collection ? "合辑" : "单张" }}
              </template>
            </el-table-column>
            <el-table-column label="名称" prop="name" width="140" />
            <el-table-column
              label="算法端ID"
              prop="up_template_id"
              width="100"
            />
            <el-table-column label="封面图">
              <template #default="{ row }">
                <image-preview-inside-cell
                  :imageUrl="row.image_url"
                  :originWidth="60"
                  :popUpwidth="260"
                />
              </template>
            </el-table-column>
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
    <group-template-add
      v-if="showDetails"
      ref="addChildRef"
      @success="getLists"
      @close="showDetails = false"
    />
    <group-template-edit
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
  removeTemplate,
  templateGroupDetail,
  templateListsInGroup,
} from "@/api/swap_template/template_group";
import GroupTemplateAdd from "./GroupTemplateAdd.vue";
import GroupTemplateEdit from "./GroupTemplateEdit.vue";
import { Delete, EditPen, Plus } from "@element-plus/icons-vue";
import feedback from "@/utils/feedback";

const showDetails = ref<boolean>(true);
const addChildRef = shallowRef<InstanceType<typeof GroupTemplateAdd>>();
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
  template_cnt: 0,
});

//表单数据
const queryParams = reactive<any>({
  id: "",
  name: "",
});

const { pager, getLists, resetPage, resetParams } = usePaging({
  fetchFun: templateListsInGroup,
  params: queryParams,
});

//获取使用详情
const getUsageDetails = async (id: number) => {
  try {
    const data = await templateGroupDetail({ id });
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
  await removeTemplate({ relation_id });
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
