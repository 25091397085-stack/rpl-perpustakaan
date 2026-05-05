<?php

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(302); // Berubah menjadi 302 karena di web.php route('/') di-redirect
});
