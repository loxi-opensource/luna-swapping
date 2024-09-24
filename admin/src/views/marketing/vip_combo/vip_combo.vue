<template>
    <el-card shadow="never" class="!border-none" v-perms="['member.member_package/getConfig']">
        <div class="font-bold text-[14px]">开通会员功能</div>
        <el-form ref="formRef" :model="formData" label-width="84px" class="mt-[10px]">
            <el-form-item label="功能状态" prop="name">
                <div>
                    <div>
                        <el-radio-group v-model="formData.status" class="ml-4">
                            <el-radio :label="1">开启</el-radio>
                            <el-radio :label="0">关闭</el-radio>
                        </el-radio-group>
                    </div>
                    <div class="form-tips">关闭后，移动端和PC端的会员入口将不会显示</div>
                </div>
            </el-form-item>
        </el-form>
    </el-card>
    <el-card shadow="never" class="!border-none mt-4">
        <router-link
            v-perms="['member.member_package/add:edit', 'member.member_package/add']"
            :to="getRoutePath('member.member_package/add:edit')"
        >
            <el-button type="primary" class="mb-[10px]"> 新增会员套餐 </el-button>
        </router-link>

        <el-table size="large" v-loading="pager.loading" :data="pager.lists">
            <el-table-column label="套餐名称" prop="name" min-width="100" />
            <el-table-column label="套餐时长" min-width="120">
                <template #default="{ row }">
                    <span v-if="row.is_perpetual">永久</span>
                    <span v-else>{{ row.duration }}个月</span>
                </template>
            </el-table-column>
            <el-table-column label="销售价格" min-width="120">
                <template #default="{ row }"> ￥{{ row.sell_price }} </template>
            </el-table-column>
            <el-table-column label="购买人数" prop="buy_num" min-width="100" />
            <el-table-column
                label="是否上架"
                min-width="120"
                v-perms="['member.member_package/status']"
            >
                <template #default="{ row }">
                    <el-switch
                        :active-value="1"
                        :inactive-value="0"
                        v-model="row.status"
                        @click="handleupdate(row.id)"
                    ></el-switch>
                </template>
            </el-table-column>
            <el-table-column
                label="是否默认"
                prop="account"
                min-width="120"
                v-perms="['member.member_package/default']"
            >
                <template #default="{ row }">
                    <el-switch
                        :active-value="1"
                        :inactive-value="0"
                        v-model="row.is_default"
                        @click="handledefault(row.id)"
                    ></el-switch>
                </template>
            </el-table-column>
            <el-table-column label="排序" prop="sort" min-width="100" />
            <el-table-column label="操作" width="120" fixed="right">
                <template #default="{ row }">
                    <el-button type="primary" link>
                        <router-link
                            v-perms="[
                                'member.member_package/add:edit',
                                'member.member_package/edit'
                            ]"
                            :to="{
                                path: getRoutePath('member.member_package/add:edit'),
                                query: {
                                    id: row.id
                                }
                            }"
                        >
                            编辑
                        </router-link>
                    </el-button>
                    <el-button
                        v-perms="['member.member_package/del']"
                        type="danger"
                        link
                        @click="handleDel(row.id)"
                    >
                        删除
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <div class="flex justify-end mt-4">
            <pagination v-model="pager" @change="getLists" />
        </div>
    </el-card>
    <footer-btns v-perms="['member.member_package/setConfig']">
        <el-button type="primary" @click="handleSave">保存</el-button>
    </footer-btns>
    <Edit v-if="showEdit" ref="editRef" @success="getLists" @close="showEdit = false" />
</template>
<script setup lang="ts">
import Edit from './edit.vue'
import { usePaging } from '@/hooks/usePaging'
import {
    getmenmberLists,
    delMenmber,
    updatestatus,
    updatedefault,
    getConfig,
    setConfig
} from '@/api/marketing/vip'
import feedback from '@/utils/feedback'
import router, { getRoutePath } from '@/router'
/**
 * 处理弹出框
 */
const editRef = shallowRef<InstanceType<typeof Edit>>()
const showEdit = ref(false)
const handleAdd = async () => {
    showEdit.value = true
    await nextTick()
    editRef.value?.open('add')
}
/**
 * 获取初始化数据
 */
const queryParams = reactive({})
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: getmenmberLists,
    params: queryParams
})
getLists()
/**
 * 删除数据
 */
const handleDel = async (id: number) => {
    await feedback.confirm('确认删除？')
    await delMenmber({ id })
    formData.value = await getConfig()
    getLists()
}
/**
 * 处理编辑
 */
const handleEdit = async (id: number) => {
    showEdit.value = true
    // router.push(`/marketing/vipcombo/edit_vip?id=${id}`)
    await nextTick()
    editRef.value?.open('edit')
    editRef.value?.getDetail(id)
}
const handleupdate = async (id: number) => {
    await updatestatus({ id })
    getLists()
}
const handledefault = async (id: number) => {
    await updatedefault({ id })
    getLists()
}
//获取开通会员功能
const getvipConfig = async () => {
    formData.value = await getConfig()
}

const formData = ref({ status: 1 })
const handleSave = async () => {
    await setConfig(formData.value)
    getLists()
}

onMounted(() => {
    getvipConfig()
})
</script>
