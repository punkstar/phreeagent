Example:

    <?php
    require_once "vendor/autoload.php";

    $config = new Freeagent\Config(
        '123',
        '456',
        '789'
    );

    $contact = new Freeagent\Contact($config);

    $contact->first_name = 'Nick';
    $contact->last_name = 'Jones';
    $contact->email = 'nick@nicksays.co.uk';

    $contact->create();

    var_dump($contact);
