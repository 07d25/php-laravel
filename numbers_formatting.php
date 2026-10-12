<?php

// ===============================
// FORMATO DE NÚMEROS
// ===============================

$price = 1999.5678;

// 2 decimales
echo number_format($price, 2) . "\n";

// con separador de miles
echo number_format($price, 2, ".", ",") . "\n";
