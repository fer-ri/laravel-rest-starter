<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->migrate();
    }

    public function test_category_create()
    {
        $category = factory(App\Models\Category::class)->create([
            'title' => 'Uncategorized'
        ]);

        $this->seeInDatabase('categories', [
            'title' => $category->title,
            'slug' => 'uncategorized',
            'description' => $category->description,
            'type' => 'post',
        ]);
    }

    public function test_category_nested()
    {
        $root = factory(App\Models\Category::class)->create();

        $child = factory(App\Models\Category::class)->create();

        $child2 = factory(App\Models\Category::class)->create();

        $root->appendNode($child);

        // use different way to append node
        $child2->appendToNode($child)->save();

        $findChild = App\Models\Category::descendantsOf($root->id)->first();

        $this->assertSame($findChild->id, $child->id);

        $findChild2 = App\Models\Category::descendantsOf($child->id)->first();

        $this->assertSame($findChild2->id, $child2->id);

        $nodes = App\Models\Category::get()->toTree();

        $html = [];

        $traverse = function ($categories, $prefix = '-') use (&$traverse, &$html) {
            foreach ($categories as $category) {
                $html[] = $prefix.' '.$category->title;

                $traverse($category->children, $prefix.'-');
            }
        };

        $traverse($nodes);

        $this->assertSame($html[0], '- '.$root->title);
        $this->assertSame($html[1], '-- '.$child->title);
        $this->assertSame($html[2], '--- '.$child2->title);
    }
}
