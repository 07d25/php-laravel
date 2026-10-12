<?php

require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Employee.php';
require_once __DIR__ . '/BankAccount.php';
require_once __DIR__ . '/PremiumBankAccount.php';
require_once __DIR__ . '/MathUtils.php';
require_once __DIR__ . '/PaymentStatus.php';
require_once __DIR__ . '/PaymentMethod.php';
require_once __DIR__ . '/AbstractPaymentProcessor.php';
require_once __DIR__ . '/StripePaymentProcessor.php';
require_once __DIR__ . '/PaypalPaymentProcessor.php';
require_once __DIR__ . '/Timestampable.php';
require_once __DIR__ . '/Order.php';
require_once __DIR__ . '/Invoice.php';
require_once __DIR__ . '/InvoicePrinter.php';

use App\BankAccount;
use App\Employee;
use App\Invoice;
use App\InvoicePrinter;
use App\MathUtils;
use App\Order;
use App\PaymentStatus;
use App\PaypalPaymentProcessor;
use App\PremiumBankAccount;
use App\StripePaymentProcessor;
use App\User;

echo "== Classes ==\n";
$user1 = new User('Philipos', 25);
echo $user1->introduce() . "\n";

echo "\n== Class Inheritance ==\n";
$employee = new Employee('Brandon', 30, 'Developer');
echo $employee->introduce() . "\n";

echo "\n== Class Members Visibility ==\n";
$account = new BankAccount('Maria');
$account->deposit(1000);
echo "Owner: " . $account->getOwner() . "\n";
echo "Balance: " . $account->getBalance() . "\n";

$premiumAccount = new PremiumBankAccount('Lucas');
$premiumAccount->deposit(500);
$premiumAccount->applyMonthlyBonus(50);
echo $premiumAccount->getPremiumLabel() . "\n";
echo "Premium balance: " . $premiumAccount->getBalance() . "\n";

echo "\n== Static Class Members ==\n";
echo "Square of 5: " . MathUtils::square(5) . "\n";
echo "Area of a circle with radius 3: " . MathUtils::circleArea(3) . "\n";

echo "\n== Interfaces + Abstract Classes ==\n";
$stripe = new StripePaymentProcessor();
$paypal = new PaypalPaymentProcessor();
echo $stripe->getProviderName() . ': ' . $stripe->pay(1200)->value . "\n";
echo $paypal->getProviderName() . ': ' . $paypal->pay(3000)->value . "\n";

echo "\n== Traits ==\n";
$order = new Order('ORD-1001', 199.99);
echo "Order {$order->number} created at: " . $order->getCreatedAt()->format('Y-m-d H:i:s') . "\n";
$order->markAsApproved();
echo "Order status after approval: " . $order->status->value . "\n";

echo "\n== Final & Readonly ==\n";
$invoice = new Invoice('INV-9001', 499.95, PaymentStatus::Approved);
$printer = new InvoicePrinter();
echo $printer->format($invoice) . "\n";
