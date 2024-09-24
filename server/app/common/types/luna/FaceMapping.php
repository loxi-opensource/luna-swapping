<?php

namespace app\common\types\luna;

class FaceMapping
{
    private int $up_file_id; // 目标图文件ID
    private array $mapping; // 人脸映射关系。目标图人脸ID => 用户图人脸ID

    public function __construct(int $up_file_id, array $mapping)
    {
        $this->up_file_id = $up_file_id;
        $this->mapping = $mapping;
    }

    public function toArray(): array
    {
        return [
            'up_file_id' => $this->up_file_id,
            'targetFileId' => $this->up_file_id, // 兼容算法端
            'mapping' => $this->mapping,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['up_file_id'],
            $data['mapping']
        );
    }
}
