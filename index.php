<?php
include 'list/settings.php';
include 'list/functions.php';

$sql = "SELECT * FROM lists";
$result = $conn->query($sql);
$lists = $result->num_rows;

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
$users = $result->num_rows;

$sql = "SELECT * FROM items";
$result = $conn->query($sql);
$items = $result->num_rows;

$template->parse("list/html/head.html");
?>
    <div class="parallax-container land">
        <div class="parallax animation">
            <div class="top white-text center-align">
                <h1>ShopList.ml</h1>
                <h6>is an innovative solution to the shopping list</h6><br>
                <a href="list/index.php" class="btn waves-effect white waves-indigo indigo-text btn-large darken-2 ">Let's start shopping!</a>

            </div>
        </div>
    </div>
    <svg id="topsvg" style="transform: scaleY(-1);" xmlns="http://www.w3.org/2000/svg" version="1.0" width="100%" fill="#fff" height="90" viewBox="0 0 1920 90" preserveAspectRatio="none"><path d="M0,0H1920L0,90V0Z"></path></svg>
    <div class="section white" id="devices">
        <div class="container">
            <div class="row">
                <div class="col m6 s12">
                    <h2>All device supported!</h2>
                    The Shoplist.ml is supported in all devices, that supports HTML5 - that's basically means all the webbrowsers.</div>
                <div class="col m6 s12"><img src="img/devices.PNG" class="col s12"></div>
            </div>
        </div>
    </div> <svg style="transform: scaleX(-1);" xmlns="http://www.w3.org/2000/svg" version="1.0" width="100%" height="90" fill="#fff" viewBox="0 0 1920 90" preserveAspectRatio="none"><path d="M0,0H1920L0,90V0Z"></path></svg>
    <div class="section indigo" id="info">
        <div class="container">
            <div class="row">
                <div class="col s12 m4">
                    <div class="card hoverable">
                        <div class="card-content indigo-text">
                            <center>
                                <i class="material-icons center-align indigo-text medium">content_paste</i>
                                <h2>
                                    <?php echo $lists;?>
                                </h2>
                                <h6>lists were created</h6>
                            </center>
                        </div>
                    </div>
                </div>

                <div class="col s12 m4">
                    <div class="card hoverable">
                        <div class="card-content indigo-text">
                            <center>
                                <i class="material-icons center-align indigo-text medium">person</i>
                                <h2>
                                    <?php echo $users;?>
                                </h2>
                                <h6>users using shoplist.ml</h6>
                            </center>
                        </div>
                    </div>
                </div>

                <div class="col s12 m4">
                    <div class="card hoverable">
                        <div class="card-content indigo-text">
                            <center>
                                <i class="material-icons center-align indigo-text medium">add_box</i>
                                <h2>
                                    <?php echo $items;?>
                                </h2>
                                <h6>new items were added</h6>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section white" id="devices">
        <div class="container">
            <div class="row">
                <div class="col m4 s12"><img src="img/web_hi_res_512.png" class="col s12"></div>
                <div class="col m8 s12">
                    <h2>Android app is coming soon!</h2>
                    We know that the most user likes to use their applcations instead of websites. So we will build the Shoplist.ml to an Android Application. The accounts and all of its data are shared, so if you create a new list in your app you will also see it in the desktop version.
                </div>

            </div>
        </div>
    </div>
    <div class="section indigo" id="info">
        <div class="container">
            <div class="row">
                <div class="col s12 m6">
                    <a href="https://www.facebook.com/shoppinglistml/">
                        <div class="card hoverable">
                            <div class="card-content indigo-text">
                                <center>
                                    <svg style="width:61px;height:61px" viewBox="0 0 24 24">
                                        <path fill="#3f51b5" d="M17,2V2H17V6H15C14.31,6 14,6.81 14,7.5V10H14L17,10V14H14V22H10V14H7V10H10V6A4,4 0 0,1 14,2H17Z" />
                                    </svg>
                                    <h6>Open our page on</h6>
                                    <h2>Facebook</h2>

                                </center>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col s12 m6">
                    <a href="https://github.com/pepyta/shoplist.ml/">
                        <div class="card hoverable">
                            <div class="card-content indigo-text">
                                <center>
                                    <svg style="width:61px61px;height:61px" viewBox="0 0 24 24">
                                        <path fill="#3f51b5" d="M5,3H19A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H14.56C14.24,20.93 14.23,20.32 14.23,20.11L14.24,17.64C14.24,16.8 13.95,16.25 13.63,15.97C15.64,15.75 17.74,15 17.74,11.53C17.74,10.55 17.39,9.74 16.82,9.11C16.91,8.89 17.22,7.97 16.73,6.73C16.73,6.73 15.97,6.5 14.25,7.66C13.53,7.46 12.77,7.36 12,7.35C11.24,7.36 10.46,7.46 9.75,7.66C8.03,6.5 7.27,6.73 7.27,6.73C6.78,7.97 7.09,8.89 7.18,9.11C6.61,9.74 6.26,10.55 6.26,11.53C6.26,15 8.36,15.75 10.36,16C10.1,16.2 9.87,16.6 9.79,17.18C9.27,17.41 7.97,17.81 7.17,16.43C7.17,16.43 6.69,15.57 5.79,15.5C5.79,15.5 4.91,15.5 5.73,16.05C5.73,16.05 6.32,16.33 6.73,17.37C6.73,17.37 7.25,19.12 9.76,18.58L9.77,20.11C9.77,20.32 9.75,20.93 9.43,21H5A2,2 0 0,1 3,19V5A2,2 0 0,1 5,3Z" />
                                    </svg>
                                    <h6>View our project on</h6>
                                    <h2>
                                        Github
                                    </h2>

                                </center>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="page-footer indigo">
        <div class="footer-copyright">
            <div class="container">
                Copyright &copy; 2018 Gál Péter
            </div>
        </div>
    </footer>
    <span itemscope itemtype="http://schema.org/Organization" style="display:none;">
      <link itemprop="url" href="https://shoplist.ml">
      <a itemprop="sameAs" href="http://www.facebook.com/shoppinglistml">FB</a>
      
    </span>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js" async></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.1/js/materialize.min.js'></script>
    <script type="text/javascript">
        M.AutoInit();

    </script>
    <script async src='https://www.googletagmanager.com/gtag/js?id=UA-120924669-1'></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-120924669-1');

    </script>
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "SoftwareApplication",
            "name": "ShopList",
            "image": "https://shoplist.ml/img/favicon-196x196.png",
            "url": "https://shoplist.ml/",
            "applicationCategory": "Web application",
            "operatingSystem": "Windows, Android, iOs, Linux",
        }

    </script>
    </BODY>

    </HTML>
