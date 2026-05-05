<?php

use App\Models\User;
use App\Models\Category;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->adminRole = Role::firstOrCreate(['name' => 'admin']);
    $this->memberRole = Role::firstOrCreate(['name' => 'member']);
    
    $this->admin = User::factory()->create();
    $this->admin->assignRole($this->adminRole);
    
    $this->member = User::factory()->create();
    $this->member->assignRole($this->memberRole);
});

test('admin can see categories index', function () {
    $response = $this->actingAs($this->admin)->get('/categories');
    $response->assertStatus(200);
});

test('member cannot see categories index', function () {
    $response = $this->actingAs($this->member)->get('/categories');
    $response->assertStatus(403);
});

test('admin can create category', function () {
    $response = $this->actingAs($this->admin)->post('/categories', [
        'name' => 'New Category'
    ]);
    
    $response->assertRedirect('/categories');
    $this->assertDatabaseHas('categories', ['name' => 'New Category']);
});

test('admin can update category', function () {
    $category = Category::create(['name' => 'Old Category']);
    
    $response = $this->actingAs($this->admin)->put("/categories/{$category->id}", [
        'name' => 'Updated Category'
    ]);
    
    $response->assertRedirect('/categories');
    $this->assertDatabaseHas('categories', ['name' => 'Updated Category']);
});

test('admin can delete category', function () {
    $category = Category::create(['name' => 'To Delete']);
    
    $response = $this->actingAs($this->admin)->delete("/categories/{$category->id}");
    
    $response->assertRedirect('/categories');
    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});
