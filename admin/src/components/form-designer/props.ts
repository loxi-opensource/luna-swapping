import { isArray, isFunction } from 'lodash-es'
import type { SetterType } from './setter'

const propsType: Record<string, any> = {
    string: '',
    boolean: false,
    number: 0,
    array: () => [],
    object: () => ({})
}

export interface PropsItem extends PropsBase {
    setter: SetterType
}

export type ConditionType =
    | {
          type: 'JSFunction'
          value: string
      }
    | ((_arg: any) => boolean)
/**
 * @description prop配置
 */
export interface PropsBase {
    /**
     * prop字段名
     */
    name: string
    /**
     * prop标签名
     */
    label?: string
    /**
     * prop类型，可以根据类型生成默认值
     */
    type: string
    /**
     * 提示文案
     */
    tip?: string
    /**
     * 显示条件，一般可以根据props的值判断是否现在该设置器
     */
    condition?: ConditionType
    /**
     * 默认值
     */
    defaultValue?: any
    /**
     * 动态获取值
     */
    getValue?: (props: any) => any
    /**
     * 动态设置
     */
    setValue?: (props: any, value: any) => any
}

export function parseProps(propsMate: PropsItem[] | Record<string, any>, props: any = {}) {
    if (!isArray(propsMate)) {
        return propsMate
    }
    for (const prop of propsMate) {
        if (prop.name) {
            // 解析props
            let defaultValue: any = null
            if (prop.defaultValue !== undefined) {
                defaultValue = prop.defaultValue
            } else {
                defaultValue = propsType[prop.type] ?? null
            }
            props[prop.name] = isFunction(defaultValue) ? defaultValue() : defaultValue
        }
    }
    return props
}
