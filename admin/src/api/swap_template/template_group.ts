import request from "@/utils/request";

// 模板组列表
export function templateGroupLists(params: any) {
  return request.get({ url: "/swap_template.template_group/lists", params });
}

// 不在指定分组中的模板列表
export function templateListsNotInGroup(params: any) {
  return request.get({
    url: "/swap_template.template_group/notInGroupTemplateLists",
    params,
  });
}

// 指定分组中的模板列表
export function templateListsInGroup(params: any) {
  return request.get({
    url: "/swap_template.template_group/inGroupTemplateLists",
    params,
  });
}

// 模板组详情
export function templateGroupDetail(params: any) {
  return request.get({ url: "/swap_template.template_group/detail", params });
}

// 新增模板组
export function templateGroupAdd(params: any) {
  return request.post({ url: "/swap_template.template_group/add", params });
}

// 编辑模板组
export function templateGroupEdit(params: any) {
  return request.post({ url: "/swap_template.template_group/edit", params });
}

// 编辑模板排序等关联信息
export function templateRelationEdit(params: any) {
  return request.post({
    url: "/swap_template.template_group/editRelation",
    params,
  });
}

// 批量添加模板到模板组
export function addTemplates(params: any) {
  return request.post({
    url: "/swap_template.template_group/addTemplates",
    params,
  });
}

// 从模板组移除模板
export function removeTemplate(params: any) {
  return request.post({
    url: "/swap_template.template_group/removeTemplate",
    params,
  });
}
