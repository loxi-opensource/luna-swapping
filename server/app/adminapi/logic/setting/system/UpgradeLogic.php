<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------


namespace app\adminapi\logic\setting\system;


use app\common\logic\BaseLogic;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Log;
use WpOrg\Requests\Requests;

/**
 * 升级逻辑
 * Class UpgradeLogic
 * @package app\adminapi\logic\settings\system
 */
class UpgradeLogic extends BaseLogic
{

    const BASE_URL = 'https://server.mddai.cn';

    const PRODUCT_CODE = '339cbed464ba5c70eafa8df34ceefc94';

    /**
     * @notes 格式化列表数据
     * @param $lists
     * @return array
     * @author 段誉
     * @date 2021/8/14 17:18
     */
    public static function formatLists($lists, $pageNo)
    {
        $localData = local_version();
        $localVersion = $localData['version'];

        foreach ($lists as $k => $item) {
            //版本描述
            $lists[$k]['version_str'] = '';
            $lists[$k]['able_update'] = 0;
            if ($localVersion == $item['version_no']) {
                $lists[$k]['version_str'] = '您的系统当前处于此版本';
            }
            if ($localVersion < $item['version_no']) {
                $lists[$k]['version_str'] = '系统可更新至此版本';
                $lists[$k]['able_update'] = 1;
            }

            //最新的版本号标志
            $lists[$k]['new_version'] = 0;

            //注意,是否需要重新发布描述
            $lists[$k]['notice'] = [];
            if ($item['uniapp_publish'] == 1) {
                $lists[$k]['notice'][] = '更新至当前版本后需重新发布手机端前端前台';
            }
            if ($item['pc_admin_publish'] == 1) {
                $lists[$k]['notice'][] = '更新至当前版本后需重新发布前端PC后台';
            }
            if ($item['pc_shop_publish'] == 1) {
                $lists[$k]['notice'][] = '更新至当前版本后需重新发布前端PC前台';
            }
            //
            $lists[$k]['notice'][] = $item['publish_content'] ?? '';

            //处理更新内容信息
            $contents = $item['update_content'];
            $add = [];
            $optimize = [];
            $repair = [];
            $contentDesc = [];
            if (!empty($contents)) {
                foreach ($contents as $content) {
                    if ($content['type'] == 1) {
                        $add[] = '新增:' . $content['update_function'];
                    }
                    if ($content['type'] == 2) {
                        $optimize[] = '优化:' . $content['update_function'];
                    }
                    if ($content['type'] == 3) {
                        $repair[] = '修复:' . $content['update_function'];
                    }
                }
                $contentDesc = array_merge($add, $optimize, $repair);
            }
            $lists[$k]['add'] = $add;
            $lists[$k]['optimize'] = $optimize;
            $lists[$k]['repair'] = $repair;
            $lists[$k]['content_desc'] = $contentDesc;
            unset($lists[$k]['update_content']);
        }
        $lists[0]['new_version'] = ($pageNo == 1) ? 1 : 0;

        return $lists;
    }



    /**
     * @notes 更新操作
     * @param $params
     * @return bool|string
     * @author 段誉
     * @date 2021/8/14 17:19
     */
    public static function upgrade($params)
    {
//        ini_set('max_execution_time', 120);
        set_time_limit(120);
        $openBasedir = ini_get('open_basedir');
        if(strpos($openBasedir, "server") !== false) {
            self::$error = '请临时关闭服务器本站点的跨域攻击设置，并重启 nginx、PHP，具体参考相关升级文档';
            return false;
        }

        // 授权验证
        $params['link'] = "package_link";
        $result = self::verify($params);
        if (!$result['has_permission']) {
            self::$error = !empty($result['msg']) ? $result['msg'] : '请先联系客服获取授权';
            // 写日志
            self::addlog($params['id'], $params['update_type'], false);
            return false;
        }

        // 本地更新包路径
        $localUpgradeDir = ROOT_PATH . '/upgrade/';

        // 本地更新临时文件
        $tempDir = ROOT_PATH . '/upgrade/temp/';

        // 更新成功或失败的标识
        $flag = true;

        Db::startTrans();
        try {
            // 远程下载链接
            $remoteUrl = $result['link'];

            if (!is_dir($localUpgradeDir)) {
                mkdir(iconv("UTF-8", "GBK", $localUpgradeDir), 0777, true);
            }

            //下载更新压缩包保存到本地
            $remoteData = self::downFile($remoteUrl, $localUpgradeDir);
            if (false === $remoteData) {
                throw new \Exception('获取文件错误');
            }

            //解压缩
            del_target_dir($tempDir, true);//解压前先删除上个版本的解压的文件
            if (false === unzip($remoteData['save_path'], $tempDir)) {
                throw new \Exception('解压文件错误');
            }

            //更新sql->更新数据类型
            if (false === self::upgradeSql($tempDir . 'sql/data/')) {
                throw new \Exception('更新数据库数据失败');
            }

            //更新文件
            if (false === self::upgradeFile($tempDir . 'project/server/', self::getProjectPath())) {
                throw new \Exception('更新文件失败');
            }

            Db::commit();
        } catch (\Exception $e) {

            Db::rollback();
            self::$error = $e->getMessage();

            //错误日志
            $params['error'] = $e->getMessage();
            // 标识更新失败
            $flag = false;
        }

        if ($flag) {
            try {
                //更新sql->更新数据结构
                if (false === self::upgradeSql($tempDir . 'sql/structure/')) {
                    throw new \Exception('更新数据库结构失败');
                }
            } catch (\Exception $e) {
                self::$error = $e->getMessage();
                //错误日志
                $params['error'] = $e->getMessage();
                // 标识更新失败
                $flag = false;
            }
        }

        //删除临时文件(压缩包不删除,删除解压的文件)
        if ($flag && false === del_target_dir($tempDir, true)) {
            Log::write('删除系统更新临时文件失败');
        }

        // 增加日志
        self::addlog($params['id'], $params['update_type'], $flag);

        return $flag;
    }

    /**
     * @notes 授权验证
     * @param $params
     * @author Tab
     * @date 2021/10/26 17:12
     */
    public static function verify($params)
    {
        $domain = request()->host(true);
        $remoteUrl = self::BASE_URL . "/indexapi/version/verify";
        $remoteUrl .= "?domain=" .$domain."&type=2&version_id=".$params['id']."&link=".$params['link'];
        $remoteUrl .= "&action=verify&product_code=" . self::PRODUCT_CODE;
        $result = Requests::get($remoteUrl);
        $result = json_decode($result->body, true);
        $result = $result['data'] ?? ['has_permission' => false, 'link' => '', 'msg' => ''];
        return $result;
    }


    /**
     * @notes 获取远程版本数据
     * @param null $pageNo
     * @param null $pageSize
     * @return array|mixed
     * @author 段誉
     * @date 2021/8/14 17:20
     */
    public static function getRemoteVersion($pageNo = null, $pageSize = null)
    {
        $cacheVersion = Cache::get('version_lists' . $pageNo);
        if (!empty($cacheVersion)) {
            return $cacheVersion;
        }
        if (empty($pageNo) || empty($pageSize)) {
            $remoteUrl = self::BASE_URL . "/indexapi/version/lists?type=2&page=1";
        } else {
            $remoteUrl = self::BASE_URL . "/indexapi/version/lists?type=2&page_no=$pageNo&page_size=$pageSize&page=1";
        }
        $remoteUrl .= "&action=lists&product_code=" . self::PRODUCT_CODE;
        $result = Requests::get($remoteUrl);
        $result = json_decode($result->body, true);
        $result = $result['data'] ?? [];
        Cache::set('version_lists' . $pageNo, $result, 1800);
        return $result;
    }


    /**
     * @notes 更新包下载链接
     * @param $params
     * @return array|false
     * @author 段誉
     * @date 2022/3/25 17:50
     */
    public static function getPkgLine($params)
    {
        $map = [
            1 => 'package_link', //一键更新类型 : 服務端更新包
            2 => 'package_link', //服務端更新包
            3 => 'pc_package_link',  //pc端更新包
            4 => 'uniapp_package_link',  //uniapp更新包
            5 => 'web_package_link', //后台前端更新包
            6 => 'integral_package_link',  //完整包
            8 => 'kefu_package_link', //客服更新包
        ];
        $params['link'] = $map[$params['update_type']] ?? '未知类型';

        // 授权验证
        $result = self::verify($params);
        if (!$result['has_permission']) {
            self::$error = !empty($result['msg']) ? $result['msg'] : '请先联系客服获取授权';
            // 写日志
            self::addlog($params['id'], $params['update_type'], false);
            return false;
        }
        //增加日志记录
        self::addlog($params['id'], $params['update_type']);
        //更新包下载链接
        return ['line' => $result['link']];
    }


    /**
     * @notes 添加日志
     * @param $versionId //版本id
     * @param $updateType //更新类型
     * @param bool $status //更新状态
     * @return bool|\Requests_Response
     * @author 段誉
     * @date 2021/10/9 14:48
     */
    public static function addlog($versionId, $updateType, $status = true)
    {
        //版本信息
        $versionData = self::getVersionDataById($versionId);
        $domain = request()->host(true);
        try {
            $paramsData = [
                'version_id' => $versionData['id'],
                'version_no' => $versionData['version_no'],
                'domain' => $domain,
                'type' => 2,//付费版
                'product_code' => self::PRODUCT_CODE,
                'update_type' => $updateType,
                'status' => $status ? 1 : 0,
                'error' => empty(self::$error) ? '' : self::$error,
            ];
            $requestUrl = self::BASE_URL . '/indexapi/version/log';
            $result = Requests::post($requestUrl, [], $paramsData);
            return $result;

        } catch (\Exception $e) {
            Log::write('更新日志:' . '更新失败' . $e->getMessage());
            return false;
        }
    }


    /**
     * @notes 通过版本记录id获取版本信息
     * @param $id
     * @return array
     * @author 段誉
     * @date 2021/10/9 11:40
     */
    public static function getVersionDataById($id)
    {
        $cacheVersion = self::getRemoteVersion()['lists'] ?? [];
        if (!empty($cacheVersion)) {
            $versionColumn = array_column($cacheVersion, null, 'id');
            if (!empty($versionColumn[$id])) {
                return $versionColumn[$id];
            }
        }
        return [];
    }



    /**
     * @notes 下载远程文件
     * @param $url
     * @param string $savePath
     * @return array|false
     * @author 段誉
     * @date 2021/8/14 17:20
     */
    public static function downFile($url, $savePath = './upgrade/')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $header = '';
        $body = '';
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $headerSize);
            $body = substr($response, $headerSize);
        }
        curl_close($ch);
        //文件名
        $fullName = basename($url);
        //文件保存完整路径
        $savePath = $savePath . $fullName;
        //创建目录并设置权限
        $basePath = dirname($savePath);
        if (!file_exists($basePath)) {
            @mkdir($basePath, 0777, true);
            @chmod($basePath, 0777);
        }
        if (file_put_contents($savePath, $body)) {
            return [
                'save_path' => $savePath,
                'file_name' => $fullName,
            ];
        }
        return false;
    }



    /**
     * @notes 获取项目路径
     * @return string
     * @author 段誉
     * @date 2021/8/14 17:20
     */
    public static function getProjectPath()
    {
        $path = dirname(ROOT_PATH);
        if(substr($path, -1) != '/') {
            $path = $path . '/';
        }
        return $path;
    }


    /**
     * @notes 更新sql
     * @param $dir
     * @return bool
     * @author 段誉
     * @date 2021/8/14 17:20
     */
    public static function upgradeSql($dir)
    {
        //没有sql文件时无需更新
        if (!file_exists($dir)) {
            return true;
        }
        //遍历指定目录下的指定后缀文件
        $sqlFiles = get_scandir($dir, '', 'sql');
        if (false === $sqlFiles) {
            return false;
        }

        //当前数据库前缀
        $sqlPrefix = config('database.connections.mysql.prefix');

        foreach ($sqlFiles as $k => $item) {
            if (get_extension($item) != 'sql') {
                continue;
            }
            $sqlContent = file_get_contents($dir . $item);
            if (empty($sqlContent)) {
                continue;
            }
            $sqls = explode(';', $sqlContent);
            //执行sql
            foreach ($sqls as $sql) {
                $sql = trim($sql);
                if (!empty($sql)) {
                    //替换表前缀，只替换一次
                    $sql = preg_replace('/`ai_/', '`' .$sqlPrefix, $sql,1). ';';
//                    $sql = str_replace('`ai_', '`' . $sqlPrefix, $sql) . ';';
                    Db::execute($sql);
                }
            }
        }
        return true;
    }


    /**
     * @notes 更新文件
     * @param $tempFile
     * @param $oldFile
     * @return bool
     * @author 段誉
     * @date 2021/8/14 17:21
     */
    public static function upgradeFile($tempFile, $oldFile)
    {
        if (empty(trim($tempFile)) || empty(trim($oldFile))) {
            return false;
        }

        // 目录不存在就新建
        if (!is_dir($oldFile)) {
            mkdir($oldFile, 0777, true);
        }

        foreach (glob($tempFile . '*') as $fileName) {
            // 要处理的是目录时,递归处理文件目录。
            if (is_dir($fileName)) {
                self::upgradeFile($fileName . '/', $oldFile . basename($fileName) . '/');
            }
            // 要处理的是文件时,判断是否存在 或者 与原来文件不一致 则覆盖
            if (is_file($fileName)) {
                if (!file_exists($oldFile . basename($fileName))
                    || md5(file_get_contents($fileName)) != md5(file_get_contents($oldFile . basename($fileName)))
                ) {
                    copy($fileName, $oldFile . basename($fileName));
                }
            }
        }
        return true;
    }

}
