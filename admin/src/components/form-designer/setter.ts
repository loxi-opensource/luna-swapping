import { isObject, isString } from '@vue/shared'
import type { Component } from 'vue'
import type { PropsItem } from './props'
import * as setters from './setters'
export interface SetterProps {
    setters?: PropsItem[]
    [x: string]: any
    /**
     * on开头为传入事件函数
     */
    /**
     * get:开头为动态获取值，必须为函数，函数接收所有的props
     */
}

export interface SetterConfig {
    /**
     * 设置器名称，渲染哪个设置器
     */
    name: string
    /**
     * 传递给 setter 的属性
     */
    props?: SetterProps

    /**
     * setter 的初始值
     * initialValue 可能要和 defaultValue 二选一
     */
    initialValue?: any
}
export type SetterType = SetterConfig | string

const setterMaps: Map<string, Component> = new Map(Object.entries(setters))

console.log(setterMaps)

/**
 * @description 获取setter
 * @param name
 * @returns
 */
export function getSetter(name: string) {
    return setterMaps.get(name) || null
}

/**
 * @description 获取所有的setter
 * @returns
 */
export function getSetterMap() {
    return setterMaps
}

export function createSetter(setterConfig: SetterConfig | string) {
    const name = getSetterName(setterConfig)
    const setter = getSetter(name)
    if (!setter) return null
    return setter
}

export function getSetterProps(setter: SetterType, current?: string): null | any {
    if (isString(setter)) {
        return null
    }

    if (isObject(setter)) {
        if (current) {
            return setter.name === current ? setter.props : null
        } else {
            return setter.props
        }
    }

    return null
}

export function getSetterName(setter: SetterType): string {
    return isObject(setter) ? setter.name : setter
}
