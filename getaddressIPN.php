<?php

    require_once __DIR__ . "/vendor/autoload.php";

    $secret_keys_and_config = [
        "merchant_id" => "22189",
        "merchant_password" => "SBSXE1FjsC7WA2PZw1r4GZa68q6t7e9Y",
        "api_id" => "23931",
        "api_password" => "STa9Owrh1lqIuVAwDhNZVBzu0Eu3s9I5",
        "config" => [
            "test_mode" => false,
        ],
    ];

    $params = [
        "amount" => 300,
        "system" => "TRON",
        "currency" => "TRX",
        "order_id" => "1 " . microtime(true),
        "comment" => "My comment",
    ];

    $paykassa = new \Paykassa\PaykassaSCI(
        $secret_keys_and_config["merchant_id"],
        $secret_keys_and_config["merchant_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );

    $res = $paykassa->createAddress(
        $params["system"],
        $params["currency"],
        $params["order_id"],
        $params["comment"]
    );

    if ($res['error']) {
        echo $res['message'];
        // actions in case of an error
    } else {
        if (false === $secret_keys_and_config["config"]["test_mode"]) {
            $invoice_id = $res['data']['invoice'];

            $address = $res["data"]["wallet"];
            $tag = $res["data"]["tag"];
            $tag_name = $res["data"]["tag_name"];
            $is_tag = $res["data"]["is_tag"];

            $system = $res["data"]["system"];
            $currency = $res["data"]["currency"];

            $display = sprintf("address %s", $address);
            if ($is_tag) {
                $display = sprintf("address %s %s: %s", $address, mb_convert_case($tag_name, MB_CASE_TITLE), $tag);
            }

            if (null === $params["amount"]) {
                echo sprintf(
                    "Send a money to the %s %s.",
                    $system,
                    htmlspecialchars($display,ENT_QUOTES, "UTF-8")
                );
            } else {
                echo sprintf(
                    "Send %s %s to the %s %s.",
                    $params["amount"],
                    $currency,
                    $system,
                    htmlspecialchars($display, ENT_QUOTES, "UTF-8")
                );
            }


            //Creating QR
            $qr_request = $paykassa->getQrLink($res['data'], $params["amount"]);
            if (!$qr_request["error"]) {
                echo sprintf(
                    '<br><br>QR Code:<br><img alt="" src="http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=%s">',
                    $qr_request["data"]["link"]
                );
            }
        } else {
            echo sprintf('Test link: <a target="_blank" href="%s">Open link</a>', $res["data"]["url"]);
        }
    }