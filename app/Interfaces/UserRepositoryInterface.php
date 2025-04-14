<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function index($perPage = 15);

    public function show($id);

    public function store($data);

    public function update($id, $data);

    public function destroy($id);
}
