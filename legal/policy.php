<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/config.php");
require_once("$server_root/libs/Parsedown.php");
$policy = "
# $site_name Privacy Policy

## 1. Introduction

Welcome to $site_name, and thank you for using our service. At $site_name, we are committed to safeguarding your privacy. This Privacy Policy explains how we collect, use, disclose, and protect your personal information when you interact with our service located at [$site_address]($site_address) (the \"Service\"). By using our Service, you acknowledge that you have read, understood, and agreed to this Privacy Policy.

## 2. Information We Collect

### 2.1 Personal Information

We respect your privacy and strive to collect as little personal information as possible. The personal information we may collect includes:

- Your chosen author name (real or pseudonymous)

### 2.2 Non-Personal Information

In addition to personal information, we may collect non-personal information about your interactions with our Service, such as your browser type, operating system, device type, and screen resolution.

## 3. Collection of IP Addresses

We collect IP addresses in the following situations:

- **Author Registration:** When you apply to become an author, we collect your IP address as part of the application process for security and identification purposes.

- **Writing Articles:** As an author, your IP address may be collected when you write and submit articles to our Service. This is done to identify the author and prevent unauthorized access.

- **Commenting:** When you post comments as a user, we collect your IP address to prevent abuse and to recognize you when you comment again.

## 4. Purpose of Collection

We collect personal and non-personal information, including IP addresses, for specific purposes, including:

- Allowing authors to register and maintain an account on our Service
- Enabling authors to write and publish articles
- Facilitating communication between authors and us
- Improving the experience of authors on our Service
- Detecting and preventing fraudulent activity
- Complying with legal obligations

## 5. Use of Cookies

We respect your privacy, and therefore, we do not use cookies or tracking technologies to collect information from our users.

## 6. Disclosure of Information

We value your trust and only disclose your information under specific circumstances, such as:

- In response to a subpoena, court order, or other legal process
- To enforce our Terms of Service or this Privacy Policy
- To protect the rights, property, or safety of $site_name, its agents, customers, or others
- In connection with a merger, acquisition, or sale of all or a portion of our assets
- With your consent

## 7. Copyright

Our logo and assets are copyrighted and protected by law. Unauthorized use of our copyrighted materials is prohibited.

## 8. Responsibility for Images

$site_name is not responsible for any images included by authors in their articles. If you have copyright concerns about images on our site, please contact us at [$site_email](mailto:$site_email), and we will address the issue promptly.

## 9. Security Measures

We take security seriously and have implemented measures to protect your information, including:

- Storing personal information securely in encrypted form
- Limiting access to personal information to authorized personnel
- Implementing security protocols to prevent unauthorized access
- Regularly reviewing and updating our security policies and procedures

## 10. Data Retention

We retain personal and non-personal information, including IP addresses, for as long as necessary to fulfill the purpose for which it was collected, unless required by law. Additionally, we may retain personal information even after an account has been closed or suspended, for security and identification purposes.

## 11. Account Suspension

Please note that author accounts can be suspended at our discretion, but your data will not be deleted. We retain the information for security and identification purposes.

## 12. Your Rights

You have the right to request access to your personal information. To prevent abuse and fraud, we may refuse to delete such information from our servers upon request. You can also object to the processing of your personal information. Please contact us at [$site_email](mailto:$site_email) for inquiries and requests.

## 13. Changes to this Privacy Policy

We reserve the right to modify this Privacy Policy at any time. Changes will be effective immediately upon posting. We encourage you to periodically review this page for updates. Your continued use of our Service constitutes your agreement to any changes to this Privacy Policy.

## 14. Contact Us

If you have any questions or concerns regarding this Privacy Policy or our privacy practices, please feel free to contact us at [$site_email](mailto:$site_email). We are here to assist you.
";
$parser = new Parsedown();
$formattedPolicy = $parser->parse($policy);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    Privacy Policy | <?php echo $site_name; ?>
  </title>
  <?php require_once("$server_root/components/metadata.php"); ?>
  <link rel="stylesheet" href="/legal/policy.css" />
</head>

<body>
  <div class="container">
    <?php require_once("$server_root/components/navbar.php"); ?>
    <div class="box policy">
      <?php echo $formattedPolicy; ?>
    </div>
</body>

</html>