import { computed, defineComponent, h } from 'vue'
import { createSetter, getSetterProps } from './setter'
import type { PropType } from 'vue'
import { isFunction } from '@vue/shared'
import type { PropsItem } from './props'

const EVENT_TAG = 'on'
const GETTER_TAG = 'get:'
export const SetterComponent = defineComponent({
    props: {
        modelValue: {
            type: Object as PropType<Record<string, any>>,
            required: true
        },
        setterName: {
            type: String
        },
        propsItem: {
            type: Object as PropType<PropsItem>,
            required: true
        }
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        // const designer = useDesigner();
        const propsModelGet = computed(() => {
            const value = props.modelValue[props.propsItem.name]
            if (props.propsItem.getValue) {
                return props.propsItem.getValue(props.modelValue) ?? value
            }
            return value
        })
        const propsModelSet = (value: any) => {
            const newModel = props.modelValue
            newModel[props.propsItem.name] = value
            if (props.propsItem.setValue) {
                props.propsItem.setValue(props.modelValue, value)
            }
            emit('update:modelValue', props.modelValue)
        }

        const setterProps = computed(() => {
            const setterProps = getSetterProps(props.propsItem.setter, props.setterName)
            for (const key in setterProps) {
                if (isFunction(setterProps[key])) {
                    const func = setterProps[key]
                    if (key.startsWith(EVENT_TAG)) {
                        setterProps[key] = function ($event: any) {
                            func($event, props.modelValue)
                        }
                    } else if (key.startsWith(GETTER_TAG)) {
                        setterProps[key.replace(GETTER_TAG, '')] = func(props.modelValue)
                    }
                }
            }
            return setterProps
        })

        return () => {
            if (!props.setterName) return null
            const setter = createSetter(props.setterName)
            if (!setter) return null
            return h(setter, {
                modelValue: propsModelGet.value,
                'onUpdate:modelValue': propsModelSet,
                ...setterProps.value
            })
        }
    }
})
