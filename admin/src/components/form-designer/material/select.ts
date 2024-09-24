import type { WidgetMeta } from '../material'

const meta: WidgetMeta = {
    name: 'WidgetSelect',
    title: '下拉选项',
    props: [
        {
            name: 'title',
            label: '字段标题',
            type: 'string',
            setter: {
                name: 'String',
                props: {
                    placeholder: '请输入字段标题'
                }
            }
        },
        {
            name: 'options',
            label: '选项',
            type: 'array',
            tip: '每一行一个选项，每行最多不超过50个字，最多50行',
            setter: {
                name: 'String',
                props: {
                    placeholder: `示例：A\nB\nC`,
                    type: 'textarea',
                    rows: 4,
                    onChange(value: any, props: any) {
                        //去重+去除''
                        props.options = Array.from(new Set(props.options)).filter((s) => s)
                    }
                }
            },
            getValue(props) {
                return props.options.join('\n')
            },
            setValue(props, value) {
                props.options = value.split('\n')
            },
            defaultValue: []
        },
        {
            name: 'defaultValue',
            label: '默认值',
            type: 'string',
            setter: {
                name: 'Select',
                props: {
                    'get:options'(props: any) {
                        return props.options
                    },
                    clearable: true
                }
            }
        },
        {
            name: 'isRequired',
            label: '是否必填',
            type: 'boolean',
            setter: {
                name: 'Bool'
            },
            defaultValue: false
        }
    ],
    sort: 3
}

export default meta
