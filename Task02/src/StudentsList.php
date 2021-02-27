<?php

namespace App;

use App\Student;

class StudentsList
{
    private $students = array();

    public function add(Student $student)
    {
        array_push($this -> students, $student);
    }

    public function count(): int
    {
        return count($this -> students);
    }

    public function get(int $n): Student
    {
        return $this -> students[$n - 1];
    }

    public function store(string $fileName)
    {
        file_put_contents($fileName, serialize($this -> students));
    }

    public function load(string $fileName)
    {
        if (!file_exists($fileName)) {
            return "Файл " . $fileName . " не существует!";
        }

        $this -> students = unserialize(file_get_contents($fileName));
    }
}
