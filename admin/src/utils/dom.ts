//dom操作相关
/**
 * 在指定光标插入字符串
 * @param element
 */
export function setRangeText(element: HTMLTextAreaElement | HTMLInputElement, text: string) {
    element.focus()
    if (element.selectionStart !== undefined) {
        const startPos = element.selectionStart!
        const endPos = element.selectionEnd!
        if (typeof element.setRangeText !== undefined) {
            element.setRangeText(text)
        } else {
            element.value =
                element.value.substring(0, startPos) +
                text +
                element.value.substring(endPos, element.value.length)
        }
        element.selectionStart = startPos + text.length
        element.selectionEnd = startPos + text.length
    } else {
        element.value += text
    }
    return element.value
}
