<template>
    <el-form ref="formRef" label-width="105px">
        <el-form-item label="类型" prop="name">
            <div class="flex flex-wrap mx-[-5px]">
                <div
                    v-for="(item, index) in materials"
                    class="cursor-pointer px-4 border border-br border-solid rounded m-[5px]"
                    :key="index"
                    :class="{
                        'border-primary text-primary': currentIndex === index
                    }"
                    @click="selectWidget(index)"
                >
                    {{ item.title }}
                </div>
            </div>
        </el-form-item>
        <template v-for="prop in currentWidget?.props">
            <el-form-item
                v-if="showSetter(prop.condition)"
                :key="prop.name"
                :label="prop.label"
                :prop="prop.name"
            >
                <div class="flex-1">
                    <SetterComponent
                        v-model="formModel.props"
                        :setter-name="getSetterName(prop.setter)"
                        :props-item="prop"
                    />
                    <div class="form-tips">{{ prop.tip }}</div>
                </div>
            </el-form-item>
        </template>
    </el-form>
</template>
<script lang="ts" setup>
import type { FormInstance } from 'element-plus'
import type { WidgetMeta, WidgetNormalization } from './material'
import { parseProps, type ConditionType } from './props'
import { isFunction } from 'lodash-es'
import { parseStringToFunction } from './parse-fun'
import { getSetterName } from './setter'
import { SetterComponent } from './setter-component'
import { uniqueId } from '@/utils/unique-id'
const materials = Object.values(
    import.meta.glob('./material/*.ts', {
        eager: true
    })
)
    .map((module: any) => module?.default || module?.meta)
    .sort((a, b) => a.sort - b.sort)

const props = withDefaults(
    defineProps<{
        modelValue: WidgetNormalization
    }>(),
    {}
)
const emit = defineEmits<{
    (event: 'update:modelValue', value: WidgetNormalization): void
}>()

const formModel = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value)
    }
})

const formRef = shallowRef<FormInstance>()

const currentIndex = computed({
    get() {
        const current = materials.findIndex((material) => material.name === formModel.value.name)
        currentWidget.value = materials[current]
        return current !== -1 ? current : 0
    },
    set(value) {
        currentWidget.value = materials[value]
        formModel.value = {
            name: currentWidget.value?.name!,
            title: currentWidget.value?.title!,
            id: formModel.value.id || uniqueId(),
            props: parseProps(currentWidget.value?.props || {})
        }
    }
})

const currentWidget = ref<WidgetMeta>()
const selectWidget = (index: number) => {
    currentIndex.value = index
}
watchEffect(() => {
    if (!formModel.value?.id) {
        selectWidget(0)
    }
})
const showSetter = (condition?: ConditionType) => {
    if (!condition) return true
    if (isFunction(condition)) {
        return condition(formModel)
    }
    if (condition.type == 'JSFunction') {
        return parseStringToFunction(condition.value)(formModel)
    }
    return true
}
</script>
