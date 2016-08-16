<?php

namespace App\Transformers;

use App\Models\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    public function transform(Post $post)
    {
        return [
            'uuid' => $post->uuid,
            'title' => $post->title,
            'slug' => $post->slug,
            'content' => $post->content,
            'status' => $post->status,
            'createdAt' => $post->created_at->__toString(),
            'updatedAt' => $post->updated_at->__toString(),
        ];
    }
}
