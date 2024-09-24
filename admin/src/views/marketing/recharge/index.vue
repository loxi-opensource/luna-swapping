<template>
    <el-card shadow="never" class="!border-none">
        <div class="text-xl font-medium mb-[20px]">充值功能</div>
        <el-form ref="formRef" :model="pagerData" label-width="84px">
            <el-form-item label="状态功能" prop="name">
                <div>
                    <el-radio-group v-model="pagerData.status" class="ml-4">
                        <el-radio :label="1">开启</el-radio>
                        <el-radio :label="0">关闭</el-radio>
                    </el-radio-group>
                    <div class="form-tips">关闭后，移动端和PC端的充值入口将不会显示</div>
                </div>
            </el-form-item>
        </el-form>
    </el-card>
    <el-card shadow="never" class="!border-none mt-4">
        <div v-perms="['recharge.recharge_package/add', 'recharge.recharge_package/add:edit']">
            <router-link :to="getRoutePath('recharge.recharge_package/add:edit')">
                <el-button type="primary">
                    <template #icon>
                        <icon name="el-icon-Plus" />
                    </template>
                    新增充值套餐
                </el-button>
            </router-link>
        </div>

        <el-table size="large" :data="pager.lists" class="mt-4">
            <el-table-column label="套餐封面" min-width="120">
                <template #default="{ row }">
                    <el-image
                        :src="row.image"
                        fit="cover"
                        class="w-[80px] h-[48px]"
                        :preview-src-list="[row.image]"
                        preview-teleported
                    ></el-image>
                </template>
            </el-table-column>
            <el-table-column label="套餐名称" min-width="120" prop="name"> </el-table-column>
            <el-table-column label="套餐描述" min-width="200" prop="describe"> </el-table-column>
            <el-table-column label="套餐价格" min-width="120">
                <template #default="{ row }"> ¥{{ row.sell_price }} </template>
            </el-table-column>
            <el-table-column label="套餐状态" min-width="120">
                <template #default="{ row }">
                    <el-switch
                        :active-value="1"
                        :inactive-value="0"
                        v-model="row.status"
                        @change="changeStatus(row.id)"
                    ></el-switch>
                </template>
            </el-table-column>
            <el-table-column label="是否推荐" min-width="120">
                <template #default="{ row }">
                    <el-switch
                        :active-value="1"
                        :inactive-value="0"
                        v-model="row.is_recommend"
                        @change="changeRecommend(row.id)"
                    ></el-switch>
                </template>
            </el-table-column>
            <el-table-column label="对话次数" min-width="100" prop="number"> </el-table-column>
            <el-table-column label="绘画次数" min-width="100" prop="draw_number"> </el-table-column>
            <el-table-column label="额外赠送" min-width="100">
                <template #default="{ row }">
                    <el-tag v-if="row.is_give == 1" type="success">{{ row.is_give_desc }}</el-tag>
                    <el-tag v-if="row.is_give == 0" type="danger">{{ row.is_give_desc }}</el-tag>
                </template>
            </el-table-column>

            <el-table-column label="赠送对话次数" min-width="130" prop="give_number">
            </el-table-column>
            <el-table-column label="赠送绘画次数" min-width="130" prop="give_draw_number">
            </el-table-column>
            <el-table-column label="操作" width="120" fixed="right">
                <template #default="{ row }">
                    <el-button type="primary" link>
                        <router-link
                            v-perms="[
                                'recharge.recharge_package/edit',
                                'recharge.recharge_package/add:edit'
                            ]"
                            :to="{
                                path: getRoutePath('recharge.recharge_package/add:edit'),
                                query: {
                                    id: row.id,
                                    mode: 'edit'
                                }
                            }"
                        >
                            编辑
                        </router-link>
                    </el-button>
                    <el-button
                        v-perms="['recharge.recharge_package/del']"
                        type="danger"
                        link
                        @click="handleDelete(row.id)"
                    >
                        删除
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
    </el-card>
    <footer-btns v-perms="['recharge.recharge_package/setConfig']">
        <el-button type="primary" @click="handleSave">保存</el-button>
    </footer-btns>
</template>
<script setup lang="ts">
import {
    getRechargeConfig,
    setRechargeConfig,
    getRechargeLists,
    rechargeStatus,
    rechargeRecommend,
    rechargeDelete
} from '@/api/marketing/recharge'

import { usePaging } from '@/hooks/usePaging'
import { getRoutePath } from '@/router'
import feedback from '@/utils/feedback'

const pagerData = ref({
    status: 1
})
const getData = async () => {
    pagerData.value = await getRechargeConfig()
}

const handleSave = async () => {
    await setRechargeConfig(pagerData.value)
    getData()
}
const { pager, getLists } = usePaging({
    fetchFun: getRechargeLists
})

//修改状态
const changeStatus = async (id: any) => {
    await rechargeStatus({ id })
    getLists()
}
const changeRecommend = async (id: any) => {
    await rechargeRecommend({ id })
    getLists()
}

//删除
const handleDelete = async (id: number) => {
    await feedback.confirm('确定要删除？')
    await rechargeDelete({ id })
    getLists()
}

getData()
getLists()
</script>
