import request from "@/utils/request";

// 模板列表
export function templateLists(params: any) {
  return request.get({ url: "/swap_template.template/lists", params });
}

// 指定合辑的子模板列表
export function templateListsInCollection(params: any) {
  return request.get({
    url: "/swap_template.template/inCollectionTemplateLists",
    params,
  });
}

// 不在在指定合辑中的模板
export function templateListsNotInCollection(params: any) {
  return request.get({
    url: "/swap_template.template/notInCollectionTemplateLists",
    params,
  });
}

// 添加子模版
export function addChildTemplates(params: any) {
  return request.post({ url: "/swap_template.template/addChild", params });
}

// 从合辑模板中移除子模板
export function removeChild(params: any) {
  return request.post({ url: "/swap_template.template/removeChild", params });
}

// 模板详情
export function templateDetail(params: any) {
  return request.get({ url: "/swap_template.template/detail", params });
}

// 新增模板
export function templateAdd(params: any) {
  return request.post({ url: "/swap_template.template/add", params });
}

// 编辑模板
export function templateEdit(params: any) {
  return request.post({ url: "/swap_template.template/edit", params });
}
