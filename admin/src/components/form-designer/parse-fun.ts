export const parseStringToFunction = (str: string) => {
    if (typeof str !== 'string') {
        return str
    }
    let fn
    const func = `return function() {
    const self = this
    try {
      return (${str}).apply(self, arguments)
    } catch(e) {
      console.log(e)
    }
  }`
    try {
        fn = new Function(func)()
    } catch (error) {
        console.error(error)
    }
    return fn
}
