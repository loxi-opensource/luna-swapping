<template>
    <div>
        <el-card class="!border-none" shadow="never">
            <el-page-header :content="title" @back="$router.back()" />
        </el-card>
        <el-form
            class="ls-form mt-4"
            ref="formRef"
            :rules="rules"
            :model="formData"
            label-width="120px"
            v-loading="loading"
        >
            <el-card shadow="never" class="!border-none">
                <div class="text-xl font-medium mb-[20px]">套餐信息</div>
                <el-form-item label="封面" prop="image">
                    <div>
                        <material-picker v-model="formData.image" :limit="1" />
                        <div class="form-tips">建议尺寸：440px*260px</div>
                    </div>
                </el-form-item>
                <el-form-item label="套餐名称" prop="name">
                    <div class="w-[380px]">
                        <el-input placeholder="请输入套餐名称" v-model="formData.name"></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="套餐描述" prop="describe">
                    <div class="w-[380px]">
                        <el-input
                            placeholder="请输入套餐介绍信息，用于对外展示"
                            type="textarea"
                            :rows="4"
                            v-model="formData.describe"
                        ></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="套餐价格" prop="sell_price">
                    <div class="w-[380px]">
                        <el-input
                            v-model="formData.sell_price"
                            type="number"
                            clearable
                            placeholder="请输入实际售价"
                        >
                            <template #append>元</template>
                        </el-input>
                    </div>
                </el-form-item>
                <el-form-item label="排序" prop="sort">
                    <div>
                        <el-input-number v-model="formData.sort" :min="0" :max="9999" />
                        <div class="form-tips">默认为0，数值越大越排前面</div>
                    </div>
                </el-form-item>
                <el-form-item label="状态" prop="status" required>
                    <el-switch v-model="formData.status" :active-value="1" :inactive-value="0" />
                </el-form-item>
            </el-card>
            <el-card shadow="never" class="!border-none mt-4">
                <div class="text-xl font-medium mb-[20px]">套餐内容</div>

                <el-form-item label="对话次数" prop="number">
                    <div class="w-[380px]">
                        <el-input
                            v-model="formData.number"
                            type="number"
                            clearable
                            placeholder="请输入对话次数"
                        >
                            <template #append>次</template>
                        </el-input>
                    </div>
                </el-form-item>
                <el-form-item label="绘画次数" prop="draw_number">
                    <div class="w-[380px]">
                        <el-input
                            v-model="formData.draw_number"
                            type="number"
                            clearable
                            placeholder="请输入绘画次数"
                        >
                            <template #append>次</template>
                        </el-input>
                    </div>
                </el-form-item>
                <el-form-item label="开启赠送" prop="is_give" required>
                    <el-switch v-model="formData.is_give" :active-value="1" :inactive-value="0" />
                </el-form-item>
                <template v-if="formData.is_give == 1">
                    <el-form-item label="赠送对话" prop="give_number">
                        <div class="w-[380px]">
                            <el-input
                                v-model="formData.give_number"
                                type="number"
                                clearable
                                placeholder="请输入赠送对话次数"
                            >
                                <template #append>次</template>
                            </el-input>
                        </div>
                    </el-form-item>
                    <el-form-item label="赠送绘画" prop="give_draw_number">
                        <div class="w-[380px]">
                            <el-input
                                v-model="formData.give_draw_number"
                                type="number"
                                clearable
                                placeholder="请输入赠送绘画次数"
                            >
                                <template #append>次</template>
                            </el-input>
                        </div>
                    </el-form-item>
                </template>
            </el-card>
        </el-form>
        <footer-btns>
            <el-button type="primary" @click="handleSave">保存</el-button>
        </footer-btns>
    </div>
</template>
<script lang="ts" setup>
import type { FormInstance } from 'element-plus'
import { rechargeEdit, rechargeAdd, getRechargeDetail } from '@/api/marketing/recharge'
const formRef = shallowRef<FormInstance>()
const { query } = useRoute()
const router = useRouter()
const title = computed(() => {
    return query.mode == 'edit' ? '编辑充值套餐' : '新增充值套餐'
})

//表单数据
const formData = reactive({
    id: '',
    name: '',
    image: '',
    describe: '',
    sell_price: '',
    sort: 0,
    status: 1,
    number: '',
    draw_number: '',
    is_give: 0,
    give_number: '',
    give_draw_number: ''
})

//表单校验规则
const rules = {
    name: [
        {
            required: true,
            message: '请输入套餐名称'
        }
    ],
    image: [
        {
            required: true,
            message: '请选择套餐封面'
        }
    ],
    describe: [
        {
            required: true,
            message: '请输入套餐描述'
        }
    ],
    sell_price: [
        {
            required: true,
            message: '请输入套餐价格'
        }
    ],
    number: [
        {
            required: true,
            message: '请输入对话次数'
        }
    ],
    draw_number: [
        {
            required: true,
            message: '请输入绘画次数'
        }
    ]
}

//提交
const handleSave = async () => {
    await formRef.value?.validate()

    query.mode == 'edit' ? await rechargeEdit(formData) : await rechargeAdd(formData)
    router.back()
}
const loading = ref(false)
const getDetail = async () => {
    if (!query.id) return
    loading.value = true
    const data = await getRechargeDetail({
        id: query.id
    })
    Object.keys(data).forEach((key) => {
        //@ts-ignore
        formData[key] = data[key]
    })
    loading.value = false
}

getDetail()
</script>
