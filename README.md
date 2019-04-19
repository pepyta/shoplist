<p align="center">
  <a href="https://shop.pepyta.ml">
    <img src="https://raw.githubusercontent.com/pepyta/shoplist.ml/master/img/web_hi_res_512.png" width="150">
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
[Try the project](https://shop.pepyta.ml)

## Getting Started
To make your own Shopping List you need a Web Server (you can use XAMPP for local).

## Settings.php example
```
<?php
//TURN ERROR_REPORTING OFF
error_reporting(0);

//MYSQLI connection
$conn = new mysqli("HOST", "USER", "PASSWORD", "DATABASE");
//Make sure website works properly
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//Language set
$lang = 'en';

//SALT for more security
$salt = 'YOUR VERY UNIQUE CODE';
?>
```

### Installing
0. Download the project
1. Copy it to your webserver.
2. Change $conn = new mysqli("SERVER", "USERNAME", "PW", "DB"); in settings.php
   - SERVER to your MySQL server's address (usually localhost)
   - USERNAME to your MySQL username
   - PW to your MySQL password
   - DB to the name of the MySQL database
3. Change the $salt to something unique (for example encrypt a word and set it as salt)
4. Import the [IMPORT.sql](https://raw.githubusercontent.com/pepyta/shoplist.ml/master/IMPORT.sql) file to your MySQL server
5. Profit!

## Test if it works
To make sure your website works correctly go to YOURWEBSITE.com/list.<br>
If you don't get a plain text with MySQL warnings, then you set your Shopping List webserver up correctly.

## Built With

* [MaterializeCSS (and JS)](https://materializecss.com/) - A modern responsive front-end framework based on Material Design
* [jQuery](https://jquery.com/) - Make JS developement more efficient

## Contributing
If you want to contribute just make a pull request and I will test it and if it works i will accept it.

## Authors

* **Gál Péter** -  [pepyta](https://github.com/pepyta)

See also the list of [contributors](https://github.com/pepyta/shoplist.ml/graphs/contributors) who participated in this project.

## In motion
<p>
<img src="https://shoplist.ml/github/1.gif" width="31%" style="display:inline-block;">
<img src="https://shoplist.ml/github/2.gif" width="31%" style="display:inline-block;">
<img src="https://shoplist.ml/github/3.gif" width="31%" style="display:inline-block;">
</p>

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
