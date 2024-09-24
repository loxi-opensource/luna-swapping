<?php

namespace app\common\service\storage\engine;

use Ramsey\Uuid\Uuid;
use think\Request;
use think\Exception;

/**
 * 存储引擎抽象类
 * Class server
 * @package app\common\library\storage\drivers
 */
abstract class Server
{
    protected $file;
    protected $error;
    protected $fileName;
    protected $fileInfo;

    // 是否为内部上传
    protected $isInternal = false;

    /**
     * 构造函数
     * Server constructor.
     */
    protected function __construct()
    {
    }

    /**
     * 设置上传的文件信息
     * @param string $name
     * @throws Exception
     */
    public function setUploadFile($name)
    {
        // 接收上传的文件
        $this->file = request()->file($name);
        if (empty($this->file)) {
            throw new Exception('未找到上传文件的信息');
        }

        // 校验上传文件后缀
        $limit = array_merge(config('project.file_image'), config('project.file_video'));
        if (!in_array(strtolower($this->file->extension()), $limit)) {
            throw new Exception('不允许上传' . $this->file->extension() . '后缀文件');
        }

//        if ($this->file->getSize() > 1024 * 1024 * 15) {
//            throw new Exception('上传文件大小不能超过15M');
//        }

        // 文件信息
        $this->fileInfo = [
            'ext' => $this->file->extension(),
            'size' => $this->file->getSize(),
            'mime' => $this->file->getMime(),
            'name' => $this->file->getOriginalName(),
            'realPath' => $this->file->getRealPath(),
        ];
        // 生成保存文件名
        $this->fileName = $this->buildSaveName();
    }

    /**
     * 设置上传的文件信息
     * @param string $filePath
     */
    public function setUploadFileByReal($filePath)
    {
        // 设置为系统内部上传
        $this->isInternal = true;
        // 文件信息
        $this->fileInfo = [
            'name' => basename($filePath),
            'size' => filesize($filePath),
            'ext' => pathinfo($filePath, PATHINFO_EXTENSION),
            'tmp_name' => $filePath,
            'realPath' => $filePath,
            'error' => 0,
        ];
        // 生成保存文件名
        $this->fileName = $this->buildSaveName();
    }

    /**
     * Notes: 抓取网络资源
     * @param $url
     * @param $key
     * @return mixed
     * @author 张无忌(2021/3/2 14:15)
     */
    abstract protected function fetch($url, $key);

    /**
     * 文件上传
     * @param $save_dir (保存路径)
     * @return mixed
     */
    abstract protected function upload($save_dir);

    /**
     * 文件删除
     * @param $fileName
     * @return mixed
     */
    abstract protected function delete($fileName);

    /**
     * 返回上传后文件路径
     * @return mixed
     */
    abstract public function getFileName();

    /**
     * 返回文件信息
     * @return mixed
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    protected function getRealPath()
    {
        return $this->fileInfo['realPath'];
    }

    /**
     * 返回错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 生成保存文件名
     */
    private function buildSaveName()
    {
        // 要上传图片的本地路径
        $realPath = $this->getRealPath();
        // 扩展名
        $ext = pathinfo($this->getFileInfo()['name'], PATHINFO_EXTENSION);
        // 自动生成文件名
        $uuid = Uuid::uuid4()->toString();
        return $uuid . ".{$ext}";
    }

}
