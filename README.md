# Lamenta

![Logo](assets/bitmap.png)

Lamenta is an open-source article website project that was formerly known as BlueWinds. It was discontinued and is now available for public use.

## Purpose

Lamenta is designed with the mission to make knowledge accessible to everyone. It serves as a platform for sharing articles and information with the community.

## Getting Started

Before you start using Lamenta, please consider the following steps:

1. **Customize Website Information:**
   - Change the name, logo, description, etc. of the website in the configuration file file located at `/config.php`.

2. **Database Configuration:**
   - In `/classes/DatabaseConnection.php`, update the database connection details (lines 4 to 7) with your own database information.

3. **Pexels API Integration:**
   - In `/writing/writing.js`, insert your Pexels API token on line 172. You can obtain a token by registering on [Pexels API](https://www.pexels.com/api/register/).

4. **MySQL Database:**
   - A MySQL database is provided in the `/database` directory. It contains 19 articles and 3 authors. The password for each author is `trust!1`.

5. **Discord Integration:**
   - In `/helpers/sendToDiscord.php`, add your Discord webhook URL on line 4 to receive notifications in your Discord server.

6. **Install GD Library:**
   - Ensure that the GD library is installed in your PHP environment. This is required for image processing. It is inlcuded by default in many systems and hosting services.

7. **ImgBB API Integration:**
   - In `/classes/Image.php`, add your ImgBB API URL on line 4. Obtain the API URL from [ImgBB API](https://api.imgbb.com/).

## Usage

Follow the steps mentioned above for setup, and you should be ready to use Lamenta. Feel free to explore and modify the code according to your needs.

## Contributing

If you have any improvements or bug fixes, feel free to contribute!

## License

This project is licensed under the [MIT License](LICENSE).

## Acknowledgments

Special thanks to all contributors who have contributed to the development of Lamenta (formerly known as BlueWinds).