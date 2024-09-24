<?php

namespace app\common\types\luna;

class FaceMappingList
{
    /** @var FaceMapping[] */
    private array $faceMappingList = [];

    public function addFaceMapping(FaceMapping $faceMapping): void
    {
        $this->faceMappingList[] = $faceMapping;
    }

    public function getFaceMappingList(): array
    {
        return $this->faceMappingList;
    }

    public function isEmpty()
    {
        return empty($this->faceMappingList);
    }

    public function toArray(): array
    {
        return array_map(fn($faceMapping) => $faceMapping->toArray(), $this->faceMappingList);
    }

    public static function fromArray(array $dataList): self
    {
        $faceMappingList = new self();
        foreach ($dataList as $data) {
            $faceMappingList->addFaceMapping(FaceMapping::fromArray($data));
        }
        return $faceMappingList;
    }
}
