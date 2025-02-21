# FreeNetly - Open Source File Sharing Platform

FreeNetly is a modern, secure, and user-friendly file sharing platform built with PHP. It allows users to upload and share files easily with support for files up to 100MB.

## 🌟 Features

- 📤 File uploads up to 100MB
- 🔒 Secure file storage
- 🔗 Easy-to-share download links
- 📱 Responsive design
- 🚀 Fast download speeds
- 🎨 Modern UI with Tailwind CSS
- 🛡️ Built-in AdBlock detection
- 📊 File preview support
- 🌐 Social sharing options

## 🚀 Quick Start

### Prerequisites

- PHP 7.4 or higher
- Apache/Nginx web server
- XAMPP/WAMP/LAMP stack
- Composer (optional)
- OneNetly API key

### Installation

1. Clone the repository:
```bash
git clone https://github.com/TheAceMotiur/FreeNetly.git
cd FreeNetly
```

2. Configure your web server:
   - For Apache, ensure mod_rewrite is enabled
   - Point your web root to the project directory

3. Install via the web interface:
   - Navigate to `http://your-domain.com/install.php`
   - Follow the installation wizard
   - Enter your OneNetly API key and site settings

4. Manual installation:
   - Copy `config.example.php` to `config.php`
   - Edit `config.php` with your settings:
```php
define('API_KEY', 'your-api-key-here');
define('SITE_NAME', 'Your Site Name');
define('SITE_EMAIL', 'your@email.com');
define('DMCA_EMAIL', 'dmca@email.com');
define('PRIVACY_EMAIL', 'privacy@email.com');
```

5. Set file permissions:
```bash
chmod 755 .
chmod 644 *.php
chmod 644 .htaccess
chmod 755 assets/
chmod 755 includes/
```

## 🛠️ Configuration Options

### Maximum File Size

Edit `.htaccess`:
```apache
php_value upload_max_filesize 100M
php_value post_max_size 100M
```

### Custom Domain

Update `config.php`:
```php
define('BASE_URL', 'https://your-domain.com');
```

### Social Sharing

Social sharing buttons are pre-configured for:
- WhatsApp
- Telegram
- Facebook
- Twitter

## 💻 Development


### Contributing

1. Fork the repository
2. Create your feature branch:
```bash
git checkout -b feature/AmazingFeature
```
3. Commit your changes:
```bash
git commit -m 'Add some AmazingFeature'
```
4. Push to the branch:
```bash
git push origin feature/AmazingFeature
```
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Support

For support, email support@freenetly.com or create an issue in the repository.

## 🙏 Acknowledgments

- [Tailwind CSS](https://tailwindcss.com)
- [OneNetly API](https://onenetly.com)
- All contributors who have helped with code, bug reports, and suggestions

## 🔐 Security

### Reporting Security Issues

If you discover a security vulnerability, please send an email to security@freenetly.com. All security vulnerabilities will be promptly addressed.

## 📈 Future Plans

- [ ] Multiple file upload support
- [ ] Folder upload support
- [ ] User accounts system
- [ ] API for developers
- [ ] File encryption at rest
- [ ] More file preview types
- [ ] Download resumption support
- [ ] Advanced file management

## ⭐ Show Your Support

Give a ⭐️ if this project helped you!

---

Made with ❤️
