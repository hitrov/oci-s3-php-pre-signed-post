<?php

require 'vendor/autoload.php';

use Aws\S3\PostObjectV4;
use Aws\S3\S3Client;

$provider = 'aws';
//$provider = 'oci';
//$provider = 'ibm';

$config = [
    'version' => 'latest',
    'signature_version' => 'v4',
];

$key = ''; // TODO
$secret = ''; // TODO

$region = '';
$bucket = '';
$endpoint = null;
switch ($provider) {
    case 'aws':
        $region = 'eu-central-1';
        $bucket = 'test20feb2021';
        $config['region'] = $region;

        $key = '';
        $secret = '';

        break;
    case 'oci':
        $region = 'eu-frankfurt-1';
        $bucket = 'test20210214';
        $namespace = 'frdtj2fg48gb';
        $endpoint = "https://$namespace.compat.objectstorage.$region.oraclecloud.com";
        $config['region'] = $region;
        $config['endpoint'] = $endpoint;
//        $config['signature_version'] = 'v4';
        $config['use_path_style_endpoint'] = true;

        $key = '';
        $secret = '';

        break;
    case 'ibm':
        $region = 'eu-geo';
        $bucket = 'test202102144';
        $endpoint = 'https://s3.eu.cloud-object-storage.appdomain.cloud';
        $config['region'] = $region;
        $config['endpoint'] = $endpoint;
//        $config['signature_version'] = 'v4';

        $key = '';
        $secret = '';

        break;
    default:
        throw new Exception("Invalid provider $provider");
}

$config['credentials'] = [
    'key'    => $key,
    'secret' => $secret,
];

$s3 = new S3Client($config);

// Set some defaults for form input fields
$formInputs = ['acl' => $_REQUEST['acl'] ?? 'private'];

// Construct an array of conditions for policy
$options = [
    ['acl' => 'private'],
    ['bucket' => $bucket],
    ['starts-with', '$key', '']
];

// Optional: configure expiration time string
$expires = '+2 hours';

$postObject = new PostObjectV4(
    $s3,
    $bucket,
    $formInputs,
    $options,
    $expires
);

// Get attributes to set on an HTML form, e.g., action, method, enctype
$formAttributes = $postObject->getFormAttributes();

// Get form input fields. This will include anything set as a form input in
// the constructor, the provided JSON policy, your AWS access key ID, and an
// auth signature.
$formInputs = $postObject->getFormInputs();

?>

<h1><?= $provider ?></h1>
<form method="<?= $formAttributes['method'] ?>" action="<?= $formAttributes['action'] ?>" enctype="<?= $formAttributes['enctype'] ?>">
    <fieldset>
        <?php foreach ($formInputs as $name => $value): ?>
            <input name="<?= $name ?>" value="<?= $value ?>" />
        <?php endforeach; ?>
    </fieldset>

    <fieldset>
        <input type="file" name="file" id="file" /> <br />
        <input type="submit" value="Upload to <?= strtoupper($provider) ?>" />
    </fieldset>
</form>
