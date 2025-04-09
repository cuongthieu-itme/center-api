<?php

namespace App\Interfaces;

interface ClassModelRepositoryInterface
{
    public function index();

    public function show($id);

    public function store($data);

    public function update($id, $data);

    public function destroy($id);
}
