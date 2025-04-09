<?php

namespace App\Interfaces;

interface AttendanceRepositoryInterface
{
    public function index();

    public function show($id);

    public function store($data);

    public function update($id, $data);

    public function destroy($id);

    public function bulkStore(array $data);
}
