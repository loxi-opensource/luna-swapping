<template>
    <el-card class="!border-none" shadow="never">
        <el-button type="primary" @click="handleAdd" v-perms="['member.member_benefits/add']">
            新增会员权益
        </el-button>
        <el-table class="mt-4" size="large" v-loading="pager.loading" :data="pager.lists">
            <el-table-column label="权益图片">
                <template #default="{ row }">
                    <el-image :src="row.image" class="w-[44px] h-[44px]"></el-image>
                </template>
            </el-table-column>
            <el-table-column label="权益名称" prop="name"></el-table-column>
            <el-table-column label="权益描述" prop="describe"></el-table-column>
            <el-table-column label="状态" v-perms="['member.member_benefits/status']">
                <template #default="{ row }">
                    <el-switch
                        v-model="row.status"
                        :active-value="1"
                        :inactive-value="0"
                        @change="changeStatus(row.id)"
                    ></el-switch>
                </template>
            </el-table-column>
            <el-table-column label="排序" prop="sort"></el-table-column>
            <el-table-column label="操作" width="120" fixed="right">
                <template #default="{ row }">
                    <el-button
                        v-perms="['member.member_benefits/edit']"
                        type="primary"
                        link
                        @click="handleEdit(row)"
                    >
                        编辑
                    </el-button>
                    <el-button
                        v-perms="['member.member_benefits/del']"
                        type="danger"
                        link
                        @click="handleDelete(row.id)"
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
    <edit-popup v-if="showEdit" ref="editRef" @success="getLists" @close="showEdit = false" />
</template>

<script setup lang="ts">
import { usePaging } from '@/hooks/usePaging'
import { shallowRef } from 'vue'
import EditPopup from './edit.vue'
import { getMemberBenefits, memberBenefitsDelete, memberBenefitsStatus } from '@/api/marketing/vip'
import feedback from '@/utils/feedback'

const editRef = shallowRef()

const { pager, getLists } = usePaging({
    fetchFun: getMemberBenefits
})
const showEdit = ref(false)
//添加
const handleAdd = async () => {
    showEdit.value = true
    await nextTick()
    editRef.value?.open('add')
}

const handleEdit = async (data: any) => {
    showEdit.value = true
    await nextTick()
    editRef.value?.open('edit')
    editRef.value?.getDetail(data)
}

const changeStatus = (id: any) => {
    memberBenefitsStatus({ id })
}

const handleDelete = async (id: number) => {
    await feedback.confirm('确定要删除？')
    await memberBenefitsDelete({ id })
    getLists()
}
getLists()
</script>

<style scoped lang="scss"></style>
