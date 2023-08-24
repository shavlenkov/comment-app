<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'comment_id' => $this->comment_id,
            'homepage' => $this->homepage,
            'text' => $this->text,
            'file' => $this->file,
            'answers' => new AnswerResource($this->answer),
            'created_at' => $this->created_at
        ];
    }
}
