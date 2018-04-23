<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Topic;
use Tests\Traits\ActingJWTUser;

class TopicApiTest extends TestCase
{

	use ActingJWTUser;

	protected $user;

    /**
     * A basic test example.
     *
     * @return void
     */
    // public function testExample()
    // {
    //     $this->assertTrue(true);
    // }

    public function setUp() {
    	parent::setUp();
    	$this->user = factory(User::class)->create();
    }

    public function testStoreTopic() {
    	$data = ['category_id' => 1, 'body' => 'test body', 'title' => 'test title'];

    
    	$response = $this->JWTActingAs($this->user)->json('POST', '/api/topics', $data);

    	$assertData = [
    		'category_id' => 1,
    		'user_id' => $this->user->id,
    		'body' => clean('test body', 'user_topic_body'),
    		'title' => 'test title',
    	];

    	$response->assertStatus(201)->assertJsonFragment($assertData);
    }

    public function testUpdateTopic() {
    	$topic = $this->makeTopic();

    	$data = ['category_id' => 2, 'body' => 'update body', 'title' => 'update title'];

    	$response = $this->JWTActingAs($this->user)->json('PATCH', '/api/topics/'. $topic->id, $data);

    	$assertData = [
    		'category_id' => 2,
    		'user_id' => $this->user->id,
    		'title' => 'update title',
    		'body' => clean('update body', 'user_topic_body'),
    	];

    	$response->assertStatus(200)->assertJsonFragment($assertData);

    }

    public function makeTopic() {
    	return factory(Topic::class)->create(['user_id' => $this->user->id, 'category_id' => 1]);
    }

    public function testShowTopic() {
    	$topic = $this->makeTopic();

    	$response = $this->json('GET', '/api/topics/'.$topic->id);

    	$assertData = [
    		'category_id' => $topic->category_id,
    		'user_id' => $this->user->id,
    		'title' => $topic->title,
    		'body' => $topic->body,
    	];

    	$response->assertStatus(200)->assertJsonFragment($assertData);
    }

    public function testIndexTopic() {
    	$response = $this->json('GET', '/api/topics');
    	$response->assertStatus(200)->assertJsonStructure(['data', 'meta']);
    }

    public function testDeleteTopic() {
    	$topic = $this->makeTopic();
    	$response = $this->JWTActingAs($this->user)->json('DELETE', 'api/topics/'.$topic->id);

    	$response->assertStatus(204);

    	$response = $this->json('GET', '/api/topics/'.$topic->id);
    	$response->assertStatus(404);
    }
}
