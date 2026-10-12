<?php

// ===============================
// COMBINACIÓN REAL
// ===============================

$planA = ["basic", "pro"];
$planB = ["pro", "enterprise"];

// todos los planes
$allPlans = array_merge($planA, $planB);

// planes únicos
$uniquePlans = array_unique($allPlans);

// planes comunes
$commonPlans = array_intersect($planA, $planB);

print_r($uniquePlans);
print_r($commonPlans);
