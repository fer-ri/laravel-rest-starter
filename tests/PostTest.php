<?php

class PostTest extends TestCase
{
    public function test_post_index()
    {
        $this->migrate();

        $user = $this->asUser();

        $user->setPermission('post:index');

        $user->setPermission('post:update');

        $this->get('/posts');

        $this->seeStatusCode(401);

        factory(App\Models\Post::class, 2)->create([
            'user_id' => 1,
        ]);

        $this->get('/posts', $this->headers($user));

        $this->seeStatusCode(200);

        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'uuid', 'title', 'slug', 'content', 'status',
                    '_authorization' => [
                        'update', 'destroy',
                    ],
                ],
            ],
        ]);

        $json = collect(json_decode($this->response->getContent())->data);

        $this->assertTrue($json->first()->_authorization->update);

        $this->assertFalse($json->first()->_authorization->destroy);
    }

    public function test_post_create()
    {
        $this->migrate();

        $user = $this->asUser();

        $user->setPermission('post:store');

        $post = factory(App\Models\Post::class)->make()->toArray();

        $this->post('/posts', $post);

        $this->seeStatusCode(401);

        $this->post('/posts', $post, $this->headers($user));

        $this->seeStatusCode(201);

        $this->seeInDatabase('posts', [
            'user_id' => $user->id,
            'title' => $post['title'],
            'content' => $post['content'],
            'status' => 'publish',
        ]);
    }

    public function test_post_show()
    {
        $this->migrate();

        $user = $this->asUser();

        $user->setPermission('post:show');

        $post = factory(App\Models\Post::class)->create([
            'user_id' => 1,
        ]);

        $this->get('/posts/'.$post->uuid);

        $this->seeStatusCode(401);

        $this->get('/posts/'.$post->uuid, $this->headers($user));

        $this->seeStatusCode(200);

        $this->seeJson(['uuid' => $post->uuid]);

        $this->seeJsonStructure([
            'data' => [
                'uuid', 'title', 'slug', 'content', 'status',
            ],
        ]);
    }

    public function test_post_update()
    {
        $this->migrate();

        $user = $this->asUser();

        $user->setPermission('post:update');

        $post = factory(App\Models\Post::class)->create([
            'user_id' => 1,
        ]);

        $title = 'All New Title';

        $content = 'All New Content';

        $status = 'draft';

        $this->put('/posts/'.$post->uuid);

        $this->seeStatusCode(401);

        $this->put('/posts/'.$post->uuid,
            compact('title', 'content', 'status'), $this->headers($user));

        $this->seeStatusCode(204);

        $this->seeInDatabase('posts', [
            'uuid' => $post->uuid,
            'title' => $title,
            'content' => $content,
            'status' => $status,
        ]);
    }

    public function test_post_delete()
    {
        $this->migrate();

        $user = $this->asUser();

        $user->setPermission('post:destroy');

        $post = factory(App\Models\Post::class)->create([
            'user_id' => 1,
        ]);

        $this->delete('/posts/'.$post->uuid);

        $this->seeStatusCode(401);

        $this->delete('/posts/'.$post->uuid, [], $this->headers($user));

        $this->seeStatusCode(204);

        $this->seeInDatabase('posts', [
            'uuid' => $post->uuid,
        ]);

        $this->notSeeInDatabase('posts', [
            'uuid' => $post->uuid,
            'deleted_at' => null,
        ]);
    }
}
