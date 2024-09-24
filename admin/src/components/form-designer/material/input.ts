import type { WidgetMeta } from '../material'

const meta: WidgetMeta = {
    name: 'WidgetInput',
    title: '单行文本',
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
            name: 'defaultValue',
            label: '默认值',
            type: 'string',
            condition: () => false,
            setter: 'String',
            defaultValue: ''
        },
        {
            name: 'placeholder',
            label: '示例文字',
            type: 'string',
            setter: {
                name: 'String',
                props: {
                    placeholder: '请输入'
                }
            }
        },
        {
            name: 'maxlength',
            label: '最大输入长度',
            type: 'number',
            setter: {
                name: 'Number',
                props: {
                    min: 0
                }
            },
            defaultValue: 200
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
    sort: 1
}

export default meta
