<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DummyCategoryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->migrate();
    }

    public function test_dummy_category_create()
    {
        $category = factory(App\Models\DummyCategory::class)->create([
            'title' => 'Dummy Category',
        ]);

        $this->seeInDatabase('categories', [
            'title' => $category->title,
            'slug' => 'dummy-category',
            'description' => $category->description,
            'type' => 'dummy',
        ]);
    }

    public function test_dummy_category_nested()
    {
        $category = factory(App\Models\Category::class)->create();

        $childCategory = factory(App\Models\Category::class)->create();

        $category->appendNode($childCategory);

        $dummy = factory(App\Models\DummyCategory::class)->create();

        $childDummy = factory(App\Models\DummyCategory::class)->create();

        $dummy->appendNode($childDummy);

        $html = [];

        $traverse = function ($categories, $prefix = '-') use (&$traverse, &$html) {
            foreach ($categories as $category) {
                $html[] = $prefix.' '.$category->title;

                $traverse($category->children, $prefix.'-');
            }
        };

        $traverse(App\Models\Category::get()->toTree());

        $this->assertSame($html[0], '- '.$category->title);
        $this->assertSame($html[1], '-- '.$childCategory->title);

        $html = [];

        $traverse(App\Models\DummyCategory::get()->toTree());

        $this->assertSame($html[0], '- '.$dummy->title);
        $this->assertSame($html[1], '-- '.$childDummy->title);
    }
}
