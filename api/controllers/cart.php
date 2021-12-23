<?php
    require_once(__DIR__ . "/../models/track.php");
    require_once(__DIR__ . "/../models/invoice.php");
    require_once(__DIR__ . "/../models/invoiceline.php");
    require_once(__DIR__ . "/../models/customer.php");
    require_once(__DIR__ . "/../utils.php");

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    class CartController {
        static function getCart() {
            if (!isset($_SESSION['cart'])) {
                return [];
            }
            $tracks = TrackDAO::getInstance();
            $result = [];
            foreach ($_SESSION['cart'] as $trackId) {
                try {
                    $result[] = $tracks->findByPk($trackId);
                } catch (Exception){
                    continue;
                }
            }
            return $result;
        }

        static function addToCart($trackId) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [$trackId];
            } else {
                if (!in_array($trackId, $_SESSION['cart'])) {
                    $_SESSION['cart'][] = $trackId;
                } else {
                    Responde::badRequest("Item already in cart");
                }
            }
            return count($_SESSION['cart']);
        }

        static function removeFromCart($trackId) {
            if (!isset($_SESSION['cart'])) {
                return 0;
            }
            if (($key = array_search($trackId, $_SESSION['cart'])) !== false) {
                unset($_SESSION['cart'][$key]);
            }
            return count($_SESSION['cart']);
        }

        static function clearCart() {
            unset($_SESSION['cart']);
            return true;
        }

        static function checkout($billing) {
            if (!isset($_SESSION['cart'])) {
                Responde::badRequest("Cart is empty");
            }

            $trackDao = TrackDAO::getInstance();
            $invoiceDao = InvoiceDAO::getInstance();
            $invoiceLineDao = InvoiceLineDAO::getInstance();
            $customerDao = CustomerDAO::getInstance();

            $invoiceData = [
                'CustomerId' => $_SESSION['userData']['CustomerId'],
                'InvoiceDate' => date("Y-m-d H:i:s"),
                'BillingAddress' => isset($billing['BillingAddress'])
                    ? $billing['BillingAddress'] : $_SESSION['userData']['Address'],
                'BillingCity' => isset($billing['BillingCity'])
                    ? $billing['BillingCity'] : $_SESSION['userData']['City'],
                'BillingState' => isset($billing['BillingState'])
                    ? $billing['BillingState'] : $_SESSION['userData']['State'],
                'BillingCountry' => isset($billing['BillingCountry'])
                    ? $billing['BillingCountry'] : $_SESSION['userData']['Country'],
                'BillingPostalCode' => isset($billing['BillingPostalCode'])
                    ? $billing['BillingPostalCode'] : $_SESSION['userData']['PostalCode'],
                'Total' => 0
            ];
            $pdo = $customerDao->getPdo();
            $pdo->beginTransaction();
            try {
                $invoice = $invoiceDao->create($invoiceData);

                $songs = [];
                foreach ($_SESSION['cart'] as $trackId) {
                    try {
                        $songs[] = $trackDao->findByPk($trackId);
                    } catch (Exception){
                        continue;
                    }
                }
                $total = 0;
                foreach ($songs as $track) {
                    $item = [
                        'InvoiceId' => $invoice['InvoiceId'],
                        'TrackId' => $track['TrackId'],
                        'UnitPrice' => $track['UnitPrice'],
                        'Quantity' => 1,
                    ];
                    $total += $track['UnitPrice'];
                    $invoiceLineDao->create($item);
                }
                $invoiceDao->update($invoice['InvoiceId'], [ 'Total' => $total ]);
            } catch (Exception) {
                $pdo->rollback();
                Responde::badRequest("Could not checkout products.");
            }
        }
    }
?>