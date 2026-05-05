<?php

use App\Models\User;
use App\Models\Member;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->adminRole = Role::firstOrCreate(['name' => 'admin']);
    $this->memberRole = Role::firstOrCreate(['name' => 'member']);
    
    $this->admin = User::factory()->create();
    $this->admin->assignRole($this->adminRole);
    
    $this->user = User::factory()->create();
    $this->user->assignRole($this->memberRole);
});

test('admin can see members list', function () {
    $response = $this->actingAs($this->admin)->get('/members');
    $response->assertStatus(200);
});

test('member cannot see members list', function () {
    $response = $this->actingAs($this->user)->get('/members');
    $response->assertStatus(403);
});

test('admin can delete member', function () {
    $member = Member::create([
        'user_id' => $this->user->id,
        'member_code' => 55555,
        'name' => 'Delete Me',
        'email' => 'delete@example.com',
        'address' => 'Addr',
        'phone' => '089999'
    ]);
    
    $response = $this->actingAs($this->admin)->delete("/members/{$member->id}");
    
    $response->assertRedirect('/members');
    $this->assertDatabaseMissing('members', ['id' => $member->id]);
});
