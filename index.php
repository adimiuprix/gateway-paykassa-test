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
        "amount" => "2500",
        "system" => "TRON",
        "currency" => "TRX",
        "order_id" => "21654 "  . microtime(true),
        "comment" => "",
    ];

    $paykassa = new \Paykassa\PaykassaSCI(
        $secret_keys_and_config["merchant_id"],
        $secret_keys_and_config["merchant_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );

    $res = $paykassa->createOrder(
        $params["amount"],
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
            ?>
            Click the button to make the payment
            <form action="<?php echo $res["data"]["url"]; ?>" method="POST">
                <button>To pay</button>
            </form>
<?php
        } else {
            echo sprintf('Test link: <a target="_blank" href="%s">Open link</a>', $res["data"]["url"]);
        }
    }