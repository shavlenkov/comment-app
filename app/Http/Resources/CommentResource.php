<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{

    public function buildAnswers($answers, $parentId = null)
    {
        $result = [];

        foreach ($answers as $answer) {
            if ($answer['parent_id'] === $parentId) {
                $childAnswer = $this->buildAnswers($answers, $answer['id']);

                $result[] = [
                    'id' => $answer['id'],
                    'user' => $answer->user,
                    'comment_id' => $answer->comment_id,
                    'homepage' => $answer->homepage,
                    'text' => $answer['text'],
                    'file' => $answer->file,
                    'answers' => !empty($childAnswer) ? $childAnswer : null,
                    'created_at' => $answer->created_at
                ];
            }
        }

        return $result;
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'comment_id' => $this->comment_id,
            'homepage' => $this->homepage,
            'text' => $this->text,
            'file' => $this->file,
            'answers' => $this->buildAnswers($this->answers),
            'created_at' => $this->created_at
        ];
    }
}
