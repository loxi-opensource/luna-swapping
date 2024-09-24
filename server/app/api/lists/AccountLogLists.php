<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\api\lists;

use app\common\enum\user\AccountLogEnum;
use app\common\model\user\UserAccountLog;


/**
 * 账户流水列表
 * Class AccountLogLists
 * @package app\shopapi\lists
 */
class AccountLogLists extends BaseApiDataLists
{

    /**
     * @notes 搜索条件
     * @return array
     * @author 段誉
     * @date 2023/2/24 14:43
     */
    public function queryWhere()
    {
        // 指定用户
        $where[] = ['user_id', '=', $this->userId];


        // 明细类型
        $type = (isset($this->params['type']) && $this->params['type'] != '') ? $this->params['type'] : 'um';
        switch ($type) {
            case 'money':
                $where[] = ['change_type', 'in', AccountLogEnum::getUserMoneyChangeType()];
                break;
            case 'draw':
                $where[] = ['change_type', 'in', AccountLogEnum::getBalanceDrawChangeType()];
                break;
            case 'um':
            default:
                $where[] = ['change_type', 'in', AccountLogEnum::getBalanceChangeType()];
                break;
        }

        // 变动类型
        if (isset($this->params['change_type']) && $this->params['change_type'] != '') {
            $where[] = ['change_type', '=', $this->params['change_type']];
        }

        // 动作
        if (!empty($this->params['action'])) {
            $where[] = ['action', '=', $this->params['action']];
        }

        if (!empty($this->params['start_time'])) {
            $where[] = ['create_time', '>=', strtotime($this->params['start_time'])];
        }

        if (!empty($this->params['end_time'])) {
            $where[] = ['create_time', '<=', strtotime($this->params['end_time'])];
        }

        return $where;
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2023/2/24 14:43
     */
    public function lists(): array
    {
        $field = 'change_type,change_amount,left_amount,action,create_time,remark,source_sn';
        $lists = UserAccountLog::field($field)
            ->where($this->queryWhere())
            ->order('id', 'desc')
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();

        foreach ($lists as &$item) {
            $item['change_type_desc'] = AccountLogEnum::getChangeTypeDesc($item['change_type']);
            $symbol = $item['action'] == AccountLogEnum::DEC ? '-' : '+';
            $item['change_amount_desc'] = $symbol . $item['change_amount'];
        }

        return $lists;
    }


    /**
     * @notes 获取数量
     * @return int
     * @author 段誉
     * @date 2023/2/24 14:44
     */
    public function count(): int
    {
        return UserAccountLog::where($this->queryWhere())->count();
    }
}