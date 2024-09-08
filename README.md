# URL-Short

A sleek, efficient, and user-friendly URL shortener with a minimalist design and enhanced security features.
#### Live Demo : [sh.mrbean.dev](https://sh.mrbean.dev)
## Features

- **Clean, Minimalist UI**: A modern, responsive design that works well on all devices.
- **Efficient Shortening**: Quickly generate short URLs for long web addresses.
- **Copy to Clipboard**: Easily copy shortened URLs with a single click.
- **Duplicate Detection**: Avoids creating multiple short URLs for the same long URL.
- **reCAPTCHA Integration**: Prevents spam and abuse through Google's reCAPTCHA service.
- **API Support**: Shortening URLs programmatically through a simple API.
- **Animations**: Smooth, subtle animations enhance the user experience.

## Tech Stack

- PHP 7.4+
- HTML5
- CSS3
- JavaScript (ES6+)
- Google reCAPTCHA v2
- Font Awesome 6.1.1

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/url-shortener.git
   cd url-shortener
   ```

2. Configure your web server (e.g., Apache, Nginx) to serve the project directory.

3. Create a `urls.json` file in the project root with write permissions:
   ```
   touch urls.json
   chmod 666 urls.json
   ```

4. Sign up for Google reCAPTCHA and get your site key and secret key.

5. Update the configuration in `index.php`:
   - Set the `$base_url` to your domain.
   - Replace `YOUR_RECAPTCHA_SITE_KEY` and `YOUR_RECAPTCHA_SECRET_KEY` with your actual reCAPTCHA keys.

6. Ensure PHP has write permissions for the project directory.

## Usage

### Web Interface

1. Visit the URL shortener website.
2. Enter a long URL in the input field.
3. Complete the reCAPTCHA challenge.
4. Click the shorten button.
5. Copy the generated short URL.

### API Usage

To shorten a URL programmatically, send a GET request to:

```
https://yourdomain.com/index.php?url=https://long-url-to-shorten.com
```

The API will return a JSON response:

```json
{
  "short_url": "https://yourdomain.com/abcdef"
}
```

## Customization

- Modify `styles.css` to change the appearance of the URL shortener.
- Update `script.js` to add or modify client-side functionality.

## Security Considerations

- Regularly update PHP and all dependencies.
- Use HTTPS to encrypt traffic between users and your server.
- Implement rate limiting to prevent API abuse.
- Regularly backup the `urls.json` file.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open source and available under the [MIT License](LICENSE).

## Contact

If you have any questions, feel free to reach out to me at [@mrbeandev](https://t.me/mrbeandev) or open an issue in this repository.
