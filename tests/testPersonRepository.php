<?php
/*
 * Exceptions are not tested
 */
include_once __DIR__ . '/../src/Services/RawApiApp.class.php';

use Services\RawApiApp;
use Model\Repository\PersonRepository;
use Services\DBServices\DBConnection;

    RawApiApp::CreateEnvironment();

    $personRepository = new PersonRepository(DBConnection::getConnection());

    assert_options(ASSERT_ACTIVE,   true);
    assert_options(ASSERT_BAIL,     true);

    list($usec, $sec) = explode(' ', microtime());
    $seed = (float) $sec + ((float) $usec * 100000);
    mt_srand($seed);
    $randval = mt_rand();
    $firstName = 'julieta'. $randval;
    $secondName = 'chris'. $randval;
    $failName = 'tony'. $randval;

    try {
        echo "testing insert 2 rows ";
        $affected = $personRepository->insert(
            'person', //table
            ['name', 'email'], //fields
            [
               [$firstName, $firstName.'@email.com'],
               [$secondName, $secondName.'@email.com']
            ] //values array (one per row)
        );
        assert($affected == 2);  echo ' ... ok' . PHP_EOL;

        echo "testing select row 1";
        $personArray = $personRepository->select(
            'person', //tablename
            ['name', 'email'], //fields
            ['email = ?'], //where
            [ $firstName.'@email.com'] //wherevalues
        );
        assert($personArray === [['name' => $firstName, 'email' => $firstName.'@email.com']]);  echo ' ... ok' . PHP_EOL;

        echo "testing select row 2";
        $personArray = $personRepository->select(
            'person', //tablename
            ['name', 'email'], //fields
            ['email = ?'], //where
            [ $secondName.'@email.com'] //wherevalues
        );
        assert($personArray === [['name' => $secondName, 'email' => $secondName.'@email.com']]); echo ' ... ok' . PHP_EOL;

        echo 'testing select inexisting field';
        $personArray = $personRepository->select(
            'person', //tablename
            ['name', 'email'], //fields
            ['email = ?'], //where
            [$failName.'@email.com'] //bindparameters
        );
        assert($personArray === []); echo ' ... ok' . PHP_EOL;

        echo 'testing delete existing row';
        $affected = $personRepository->delete('person',
            ['email = ?'], //where
            [$secondName.'@email.com'] //bindparameters
        );
        assert($affected == 1); echo ' ... ok' . PHP_EOL;

        echo 'testing delete non-existing row';
        $affected = $personRepository->delete('person',
            ['email = ?'], //where
            [$failName.'@email.com'] //bindparameters
        );
        assert($affected == 0); echo ' ... ok' . PHP_EOL;

    } catch (\Exception $e) {
        echo 'TEST FAILED ' .PHP_EOL;
        echo 'ERROR CODE: ' . $e->getCode() . PHP_EOL;
        echo $e->getMessage() .PHP_EOL;
        return;
    }
    echo 'TEST PASSED ' .PHP_EOL;
