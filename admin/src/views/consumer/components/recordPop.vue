<template>
  <Popup ref="popRef" title="会员开通记录" width="700px">
    <el-table :data="pager.lists">
      <el-table-column label="套餐名称" prop="package_name"></el-table-column>
      <el-table-column
        label="到期时间"
        prop="member_end_time_desc"
      ></el-table-column>
      <el-table-column label="购买来源" prop="channel_desc"></el-table-column>
      <el-table-column
        label="退款状态"
        prop="refund_status_desc"
      ></el-table-column>
      <el-table-column label="操作人" prop="operate_desc"></el-table-column>
      <el-table-column label="记录时间" prop="create_time"></el-table-column>
    </el-table>
    <div class="flex justify-end mt-4">
      <pagination v-model="pager" @change="getLists" />
    </div>
  </Popup>
</template>

<script lang="ts" setup>
import Popup from "@/components/popup/index.vue";
import { getOpenVipRecord } from "@/api/consumer";
import { usePaging } from "@/hooks/usePaging";

const popRef = shallowRef();
const userId = ref({
  id: "",
});

const tableData: any = ref();

const open = async (id: any) => {
  await nextTick();
  popRef.value?.open();
  userId.value.id = id;
  getLists();
};

const { pager, getLists, resetPage, resetParams } = usePaging({
  fetchFun: getOpenVipRecord,
  params: userId.value,
});

//获取数据
// const getData = async (id: number) => {
//     tableData.value = await getOpenVipRecord({ id })
//     console.log(tableData.value)
// }

defineExpose({ open });
</script>
