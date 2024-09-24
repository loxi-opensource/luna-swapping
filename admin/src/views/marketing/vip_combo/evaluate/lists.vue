<template>
  <el-card shadow="never" class="!border-none mt-4">
    <el-form
      ref="formRef"
      class="mb-[-16px]"
      :model="queryParams"
      :inline="true"
    >
      <el-form-item label="用户信息">
        <el-input
          class="w-[280px]"
          v-model="queryParams.user_info"
          placeholder="请输入用户ID编号/用户昵称"
          clearable
          @keyup.enter="resetPage"
        />
      </el-form-item>
      <el-form-item label="评价套餐">
        <el-select class="!w-[280px]" v-model="queryParams.member_package_id">
          <el-option value="">全部</el-option>
          <el-option
            v-for="item in optionsData.menber.lists"
            :key="item.id"
            :value="item.id"
            :label="item.name"
          ></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="评价等级">
        <el-select class="!w-[280px]" v-model="queryParams.comment_level">
          <el-option value="">全部</el-option>
          <el-option :value="1" label="好评">好评</el-option>
          <el-option :value="2" label="中评">中评</el-option>
          <el-option :value="3" label="差评">差评</el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="评价类型">
        <el-select class="!w-[280px]" v-model="queryParams.type">
          <el-option value="">全部</el-option>
          <el-option :value="1" label="虚拟评价">虚拟评价</el-option>
          <el-option :value="2" label="用户评价">用户评价</el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="评价状态">
        <el-select class="!w-[280px]" v-model="queryParams.status">
          <el-option value="">全部</el-option>
          <el-option :value="1" label="显示">显示</el-option>
          <el-option :value="0" label="隐藏">隐藏</el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="resetPage">查询</el-button>
        <el-button @click="resetParams">重置</el-button>
      </el-form-item>
    </el-form>
  </el-card>

  <el-card shadow="never" class="!border-none mt-4">
    <el-button
      v-perms="['member.member_package_comment/add']"
      type="primary"
      @click="handleAdd"
    >
      新增虚拟评价
    </el-button>

    <el-table
      size="large"
      v-loading="pager.loading"
      :data="pager.lists"
      class="mt-2"
    >
      <el-table-column label="评价用户" min-width="120">
        <template #default="{ row }">
          <div class="flex items-center">
            <el-avatar :src="row.image" :size="50" />
            <span class="ml-2">{{ row.name }}</span>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="评价套餐" min-width="120" prop="member_package">
      </el-table-column>
      <el-table-column label="评价等级" min-width="100">
        <template #default="{ row }">
          {{ row.comment_level_desc }}
          <div>
            <el-rate v-model="row.comment_level" disabled size="large" />
          </div>
        </template>
      </el-table-column>
      <el-table-column label="评价内容" prop="comment_content" min-width="120">
        <template #default="{ row }">
          <el-tooltip :content="row.comment_content" placement="top-start">
            <div class="ml-[10px] text_hidden">{{ row.comment_content }}</div>
          </el-tooltip>
        </template>
      </el-table-column>
      <el-table-column label="评价类型" prop="type_desc" min-width="100" />
      <el-table-column label="是否显示" min-width="100">
        <template #default="{ row }">
          <el-switch
            v-model="row.status"
            :active-value="1"
            :inactive-value="0"
          ></el-switch>
        </template>
      </el-table-column>
      <el-table-column label="评价时间" prop="create_time" min-width="100" />
      <el-table-column label="操作" width="120" fixed="right">
        <template #default="{ row }">
          <el-button
            v-perms="['member.member_package_comment/del']"
            type="danger"
            link
            @click="handleDel(row.id)"
          >
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </el-card>
  <Edit
    v-if="showEdit"
    ref="editRef"
    @success="getLists"
    @close="showEdit = false"
  />
</template>
<script setup lang="ts">
import Edit from "./edit.vue";
import { getcommentLists, Delcomment } from "@/api/marketing/evaluate";
import feedback from "@/utils/feedback";
import { usePaging } from "@/hooks/usePaging";
import { useDictOptions } from "@/hooks/useDictOptions";
import { getmenmberLists } from "@/api/marketing/vip";

/**
 * 处理弹出框
 */
const editRef = shallowRef<InstanceType<typeof Edit>>();
const showEdit = ref(false);
const handleAdd = async () => {
  showEdit.value = true;
  await nextTick();
  editRef.value?.open("add");
};
/**
 * 获取初始化数据
 */
const queryParams = reactive({
  user_info: "",
  member_package_id: "",
  comment_level: "",
  type: "",
  status: "",
});
const { pager, getLists, resetPage, resetParams } = usePaging({
  fetchFun: getcommentLists,
  params: queryParams,
});
getLists();
/**
 * 删除数据
 */
const handleDel = async (id: number) => {
  await feedback.confirm("确认删除？");
  await Delcomment({ id });
  getLists();
};
/**
 * 选择框数据
 */
const { optionsData } = useDictOptions<{
  menber: any[];
}>({
  menber: {
    api: getmenmberLists,
  },
});
</script>
