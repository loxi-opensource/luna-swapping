import request from "@/utils/request";

// 玩法列表
export function strategyLists(params: any) {
  return request.get({ url: "/swap_template.strategy/lists", params });
}

// 不在指定玩法中的模板组列表
export function groupListsNotInStrategy(params: any) {
  return request.get({
    url: "/swap_template.strategy/notInStrategyGroupLists",
    params,
  });
}

// 指定玩法中的模板组列表
export function groupListsInStrategy(params: any) {
  return request.get({
    url: "/swap_template.strategy/inStrategyGroupLists",
    params,
  });
}

// 玩法详情
export function strategyDetail(params: any) {
  return request.get({ url: "/swap_template.strategy/detail", params });
}

// 新增玩法
export function strategyAdd(params: any) {
  return request.post({ url: "/swap_template.strategy/add", params });
}

// 编辑玩法
export function strategyEdit(params: any) {
  return request.post({ url: "/swap_template.strategy/edit", params });
}

// 编辑模板组排序等关联信息
export function strategyRelationEdit(params: any) {
  return request.post({ url: "/swap_template.strategy/editRelation", params });
}

// 批量添加模板组到玩法
export function addGroups(params: any) {
  return request.post({ url: "/swap_template.strategy/addGroups", params });
}

// 从玩法移除模板组
export function removeGroup(params: any) {
  return request.post({ url: "/swap_template.strategy/removeGroup", params });
}
