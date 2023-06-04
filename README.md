# Laravel OTP Package Documentation

## Introduction
The Laravel OTP package provides a simple and secure way to generate and validate one-time passwords (OTPs) using the TOTP algorithm. It offers features such as flexible configuration options, customizable OTP length, expiration time, and more. This documentation will guide you through the installation, configuration, and usage of the package.

## Architecture
The Laravel OTP package follows a modular architecture that integrates seamlessly with the Laravel framework. It leverages the TOTP (Time-Based One-Time Password) algorithm to generate and validate one-time passwords.

At its core, the package consists of the following components:

1. OTP Generator: This component is responsible for generating OTP codes based on the TOTP algorithm. It takes an identifier (such as a user email or reset token) and generates a unique OTP code.

2. OTP Validator: This component validates the OTP entered by the user. It compares the entered OTP with the OTP generated for the corresponding identifier and checks for its validity based on factors like expiration time (TTL) and configuration settings.

3. Configuration: The package provides a configuration file (config/otp.php) that allows you to customize various aspects of OTP generation and validation. You can adjust options such as OTP length, TTL (expiration time), and behavior after successful validation.

4. Database: The package utilizes a database to store the generated OTP codes and related data. This storage mechanism ensures that the OTP codes can be retrieved and validated later when needed.

## Installation
To install the Laravel OTP package, follow these steps:

1. Install the package via Composer:
```bash
composer require kodnificent/laravel-otp
```

2. Publish the package configuration file:
```bash
php artisan vendor:publish --tag=otp-config
```

3. Update the configuration file located at **config/otp.php** to customize the package settings according to your requirements.

4. Run database migrations to create the required tables:

```bash
php artisan migrate
```

## Configuration
The package provides several configuration options to customize its behavior. You can find the configuration file at **config/otp.php**. Here are the available options:

- **length**: The length of the generated OTP code.
- **ttl**: The time-to-live (expiration time) for the OTP, in seconds.
- **always_invalidate**: Whether to invalidate the OTP after successful validation.
- **hashing_salt**: The salt value used for generating the secret key hash.
- **always_pass**: Whether to bypass OTP validation (useful for testing or debugging).

Make sure to review and update these options based on your application's needs.

## Usage
### Generating an OTP
To generate an OTP for a specific identifier, use the `generate` method of the `Otp` class. Here's an example:

```php
use Kodnificent\LaravelOtp\Facades\Otp;

$identifier = 'user@example.com';
$otp = Otp::generate($identifier);
```

The generate method returns the generated OTP code as a string.

### Validating an OTP
To validate an OTP, use the `isValid` method of the Otp class. Here's an example:

```php
use Kodnificent\LaravelOtp\Facades\Otp;

$identifier = 'user@example.com';
$code = '123456';
$isValid = Otp::isValid($identifier, $code);

if (! $isValid) {
    // OTP is invalid, handle accordingly
}

// OTP is valid, perform further actions
// ......
```

The `isValid` method returns a boolean value indicating whether the OTP is valid or not.

### Invalidating an OTP
To invalidate an OTP for a specific identifier, use the `invalidate` method of the Otp class. Here's an example:

```php
use Kodnificent\LaravelOtp\Facades\Otp;

$identifier = 'user@example.com';
Otp::invalidate($identifier);
```

This will mark the OTP as invalidated, and subsequent validations for the same identifier will fail.

## Examples and Use Cases
### Example 1: Two-Factor Authentication (2FA)
```php
// Generate OTP for user
$otp = Otp::generate($user->email);

// Send OTP to user (e.g., via email or SMS)

// Validate OTP entered by the user
$isValid = Otp::isValid($user->email, $enteredOtp);

if (!$isValid) {
// 2FA failed, show error message
}

// 2FA is successful, proceed with authentication
```

### Example 2: Password Reset
```php
// Generate OTP for password reset request
$otp = Otp::generate($resetToken);

// Validate OTP entered by the user
$isValid = Otp::isValid($resetToken, $enteredOtp);

if (!$isValid) {
// OTP is invalid, show error message
}

// OTP is valid, allow password reset
```
## API Reference
### generate(string $identifier, ?int $length = null): string
Generates an OTP for the specified identifier.

- `$identifier` (string): The identifier associated with the OTP.
- `$length` (int, optional): The length of the generated OTP code. If not provided, the default length configured in the package will be used.

Returns the generated OTP as a string.

### isValid(string $identifier, string $code, bool $invalidate = null, ?int $ttl = null): bool
Validates the provided OTP code for the specified identifier.

- `$identifier` (string): The identifier associated with the OTP.
- `$code` (string): The OTP code to validate.
- `$invalidate` (bool, optional): Whether to invalidate the OTP after successful validation. If not provided, the default behavior configured in the package will be used.
- `$ttl` (int, optional): The time-to-live (expiration time) for the OTP, in seconds. If not provided, the default TTL configured in the package will be used.

Returns a boolean value indicating whether the OTP is valid or not.

### invalidate(string $identifier): void
Invalidates the OTP for the specified identifier.

- `$identifier` (string): The identifier associated with the OTP.

## Best Practices and Tips
- Although the Laravel OTP package securely stores the OTP secret and related data, it is important to ensure that your application's overall security measures are in place. - Follow industry best practices for securing your application, including proper access controls, encryption, and other relevant security measures.
- Consider adjusting the OTP length and TTL based on your application's specific security requirements. A longer OTP length and shorter TTL can enhance security but may also impact user experience. Find the right balance based on your needs.
- Regularly update both the Laravel OTP package and the Laravel framework to benefit from the latest security patches and improvements. Keeping your dependencies up to date helps ensure that you are using the most secure and stable versions.

## Troubleshooting and FAQs
### Q: Why am I getting an "Invalid length configured" exception?
Make sure to configure a valid OTP length in the package configuration (config/otp.php). The length must be a positive integer greater than or equal to 4.

### Q: OTP Validation Fails
If OTP validation fails, ensure that the entered OTP matches the one generated for the corresponding identifier. Also, check the TTL and configuration settings to ensure they are correct.

## Contributing
To contribute to the Laravel OTP package, please follow the guidelines outlined in the `CONTRIBUTING.md` file. Contributions in the form of bug reports, feature requests, or pull requests are welcome.

## License and Acknowledgements
The Laravel OTP package is open-source software licensed under the MIT license. It makes use of the OTPHP library developed by Laurent Samson.

## Versioning and Changelog
The package follows Semantic Versioning guidelines. For a detailed list of changes, see the changelog.
