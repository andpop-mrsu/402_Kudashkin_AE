<?php

namespace App;

class Student
{
    private static int $lastId = 1;

    private int $id;
    private string $lastName;
    private string $name;
    private string $faculty;
    private int $course;
    private int $group;

    public function __construct()
    {
        $this -> id = self::$lastId++;
    }

    public function setName(string $name)
    {
        $this -> name = $name;
        return $this;
    }

    public function setLastName(string $lastName)
    {
        $this -> lastName = $lastName;
        return $this;
    }

    public function setFaculty(string $faculty)
    {
        $this -> faculty = $faculty;
        return $this;
    }

    public function setCourse(int $course)
    {
        $this -> course = $course;
        return $this;
    }

    public function setGroup(int $group)
    {
        $this -> group = $group;
        return $this;
    }

    public function getId(): int
    {
        return $this -> id;
    }

    public function getName(): string
    {
        return $this -> name;
    }

    public function getLastName(): string
    {
        return $this -> lastName;
    }

    public function getFaculty(): string
    {
        return $this -> faculty;
    }

    public function getCourse(): int
    {
        return $this -> course;
    }

    public function getGroup(): int
    {
        return $this -> group;
    }

    public function __toString(): string
    {
        return ("Id: {$this -> id}" . "\n" .
        "Фамилия: {$this -> lastName}" . "\n" .
        "Имя: {$this -> name}" . "\n" .
        "Факультет: {$this -> faculty}" . "\n" .
        "Курс: {$this -> course}" . "\n" .
        "Группа: {$this -> group}");
    }
}
