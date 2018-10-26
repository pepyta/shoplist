<p align="center">
  <a href="https://shoplist.ml">
    <img src="https://i.imgur.com/2tQpAJG.png" width="150">
  </a>
</p>

<h3 align="center">Shopping List</h3>

<p align="center">
   Material themed shopping list.<br>
   Made with HTML, CSS, JS, PHP and with <3
</p>

## Introduction
This project's purpose is to make an online shopping list with open-source code.<br>
This is my first project so I appreciate any help or commit.

## Live preview
[Try the project](https://shoplist.ml)

## Getting Started
To make your own Shopping List you need a Web Server (you can use XAMPP for local).

### Installing
0. Download the project
1. Copy it to your webserver.
2. Change the $salt to something unique (for example encrypt a word and set it as salt)
3. Profit!

### Set up your settings.php file!
```
<?php
//TURN ERROR_REPORTING OFF
error_reporting(0);

//MYSQLI connection
$conn = new mysqli("localhost", "USERNAME", "PASSWORD", "DATABASE");

//Make sure website works properly
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//Language set
$lang = 'en';

//Recaptcha code (sitekey)
$recaptcha = 'Your unique ReCaptcha code';

//SALT for more security
$salt = 'Whatever you want to use for salting your passwords';
?>
```
## Test if it works
To make sure your website works correctly go to YOURWEBSITE.com.<br>
If you don't get a plain text with MySQL warnings, then you set your Shopping List webserver up correctly.

## Built With

* [MaterializeCSS (and JS)](https://materializecss.com/) - A modern responsive front-end framework based on Material Design
* [jQuery](https://jquery.com/) - Make JS developement more efficient

## Contributing
If you want to contribute just make a pull request and I will test it and if it works i will accept it.

## Authors

* **Gál Péter** -  [pepyta](https://github.com/pepyta)

See also the list of [contributors](https://github.com/pepyta/shoplist.ml/graphs/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
