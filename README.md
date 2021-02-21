Example PHP script `index.php` shows how to create native HTML form for direct uploading files into S3-compatible Object Storage of major cloud providers:
- Amazon Web Services Simple Storage Service (S3)
- IBM Object Storage
- Oracle Cloud Infrastructure Object Storage

In order to run this example:
1. `composer install`
2. Adjust `$provider` and set `$key`/`$secret`.
3. `php -S localhost:8000`
4. Open the browser and visit the URL above.
5. Browse for a file and click Upload to {provider-name}.
