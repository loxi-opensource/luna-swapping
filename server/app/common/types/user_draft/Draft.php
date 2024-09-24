<?php

namespace app\common\types\user_draft;

use app\common\types\luna\FaceMappingList;

class Draft
{
    /**
     * @var Template[]
     */
    public array $templates;

    public int $strategy_id;

    public int $is_collection;

    public int $random_candidate_cnt;

    public ?UserImage $user_image;

    /**
     * @var FaceMappingList
     */
    public $template_face_mapping;

    public function __construct(array $data)
    {
        $this->templates = array_map(fn($item) => new Template($item), $data['templates']);
        $this->strategy_id = $data['strategy_id'] ?? 0;
        $this->is_collection = $data['is_collection'] ?? 0;
        $this->random_candidate_cnt = $data['random_candidate_cnt'] ?? 0;
        if ($data['user_image'] ?? []) {
            $this->user_image = new UserImage($data['user_image']);
        }
        if ($data['template_face_mapping'] ?? []) {
            $this->template_face_mapping = FaceMappingList::fromArray($data['template_face_mapping']);
        }
    }

    public function toArray(): array
    {
        return [
            'templates' => array_map(fn($template) => $template->toArray(), $this->templates),
            'strategy_id' => $this->strategy_id,
            'is_collection' => $this->is_collection,
            'random_candidate_cnt' => $this->random_candidate_cnt,
            'user_image' => !empty($this->user_image) ? $this->user_image->toArray() : null,
            'template_face_mapping' => !empty($this->template_face_mapping) ? $this->template_face_mapping->toArray() : null,
        ];
    }

}
