<?php

// =========================
// String (cadena de texto)
// =========================
$name = "Alice";

echo $name . "\n";

// =========================
// Integer (número entero)
// =========================
$age = 30;

echo $age . "\n";

// =========================
// Float (número decimal)
// =========================
$price = 19.99;

echo $price . "\n";

// =========================
// Boolean (verdadero/falso)
// =========================
$isStudent = true;

var_dump($isStudent);

// =========================
// Array (colección de valores)
// =========================
$fruits = ["Apple", "Banana", "Orange"];
$data = [
  "texto",
  123,
  3.14,
  true,
  null,
  ["otro", "array"],
  new stdClass()
];

print_r($fruits);
print_r($data);
var_dump($data[0] . $data[4] . $data[5][1]);

// =========================
// Null (sin valor)
// =========================
$middleName = null;

var_dump($middleName);

// =========================
// Object (objeto)
// =========================
class User
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}

$user = new User("Alice");

var_dump($user);