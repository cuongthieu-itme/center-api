<?php

namespace App\Interfaces;

interface StudentRepositoryInterface
{
    public function index($perPage = 20, $page = 1);

    public function show($id);

    public function store($data);

    public function update($id, $data);

    public function destroy($id);

    public function getAttendanceHistory($studentId);
}
