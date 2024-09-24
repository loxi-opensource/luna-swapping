truncate table ai_swap_template;
truncate table ai_swap_template_group;
truncate table ai_swap_strategy;
truncate table ai_swap_template_group_relation;
truncate table ai_swap_strategy_group_relation;
truncate table ai_swap_template_collection_relation;

# 导入官方模板
insert into loxi_luna_test.ai_swap_template (name, is_collection, up_template_id, image_url, status, create_time, update_time, delete_time, face_list)
select
    concat(tagName,'_', promptType,'_',  replace(substring_index(filePath, '/', -1),'.webp','')) as name,
    0 as is_collection,
    tagFileId as up_template_id,
    concat('https://iart-user-upload-file.oss-cn-hangzhou.aliyuncs.com/', filePath) as image_url,
    1 as status,
    null as create_time,
    null as update_time,
    null as delete_time,
    null as face_list
from loxi_luna_test.ai_luna_tag_files_pic;

# 所有海外模板 - 英文命名
SELECT count(*) FROM ai_swap_template WHERE name REGEXP '^[a-zA-Z0-9]' and is_collection=0;
# 所有国内模板 - 中文命名
SELECT count(*) FROM ai_swap_template WHERE name REGEXP '^[\\u4e00-\\u9fa5]' and is_collection=0;


# 初始化一对一换脸的默认人脸
-- 创建临时表
CREATE TEMPORARY TABLE temp_ids AS
SELECT ast.id
FROM ai_swap_template_group_relation gr
         LEFT JOIN loxi_luna_test.ai_swap_template ast ON gr.template_id = ast.id
WHERE
    gr.group_id IN (
        SELECT id
        FROM ai_swap_template_group
        WHERE is_collection = 0
    );

-- 更新目标表
UPDATE loxi_luna_test.ai_swap_template
SET face_list = JSON_SET(face_list, '$[0].is_default', 1)
WHERE id IN (SELECT id FROM temp_ids);

select * from temp_ids;

-- 删除临时表（可选）
DROP TEMPORARY TABLE temp_ids;

update ai_swap_template
set create_time=1725120000
where id>0;
