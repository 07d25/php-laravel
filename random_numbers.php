<?php

// ===============================
// NÚMEROS ALEATORIOS
// ===============================

// rango básico
echo rand(1, 10) . "\n";

// mejor versión moderna
echo random_int(1, 100) . "\n";

// float random (0-1)
echo mt_rand() / mt_getrandmax() . "\n";
