<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agrocompanion : Home</title>
    <link rel="stylesheet" href="css/landingpage.css">

</head>

<body>

    <div class="scroll-up-btn">
        <i class="fas fa-angle-up"></i>
    </div>

    <nav class="navbar">
        <div class="max-width">
            <div class="logo"><a href="#" style="font:40px 'Akaya Telivigala', cursive;"><span style="color:orange">AgroCompanion</span></a></div>
            <ul class="menu">
                <li><a href="#home" class="menu-btn">Home</a></li>
                <li><a href="#about" class="menu-btn">About</a></li>
                <li><a href="#services" class="menu-btn">Services</a></li>
                <li><a href="#market" class="menu-btn">Market</a></li>
                <li><a href="#contact" class="menu-btn">Contact</a></li>
                <li><a href="registration.php" class="menu-btn">Register</a></li>
                <li><a href="login.php" class="menu-btn">Login</a></li>
            </ul>
            <div class="menu-btn">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- home section start -->
    <section class="home" id="home">
        <div class="max-width">
            <div class="row">
                <div class="home-content">
                    <div class="text-2" style="font-size:60px;">Your product Our market!!</div>
                    <div class="text-3">Change how you trade. </div>
                    <a href="#">Get started</a>
                </div>
            </div>
        </div>
    </section>

    <!--about section start -->
    <section class="about" id="about">
        <div class="max-width">
            <h2 class="title">About us</h2>
            <div class="about-content">
                <div class="column left">
                    <img src="images/img1.jpg" alt="">
                </div>
                <div class="column right">

                    <p>Agrocompanion is an online web-based Agriculture Management System which seeks to help farmers by
                        providing various kinds of agri-related
                        information and services. This website helps farmers by providing them a large online market to
                        sell their produce. Customer can send purchase
                        request and they can purchase product.</p><br>

                    <p style="padding-left:15px;"><strong
                            style="font-family: 'Akaya Telivigala', cursive; color:#143601"> OUR MISSION - </strong>To
                        provide technology and services to the farmers and sellers thus helping them to expand their
                        businesses and provide them with
                        a wider market. Hence improve the present farming processes and to provide knowledge about
                        recent agricultural issues.</p><br>

                    <p style="padding-left:15px;"><strong
                            style="font-family: 'Akaya Telivigala', cursive;color:#143601"> OUR VISION - </strong>To
                        provide a helping hand to the farmers in improving their lives through the medium of technology,
                        thereby, improving the
                        agricultural sector in the Kenyan economy</p><br>

                    <p style="padding-left:15px;"><strong
                            style="font-family: 'Akaya Telivigala', cursive; color:#143601"> CORE VALUES -
                        </strong>Integrity, Efficiency, Innovativeness and Competence</p>

                </div>
            </div>

        </div>
    </section>

    <!-- services section start -->
    <section class="services" id="services">
        <div class="max-width">
            <h2 class="title">Our Services</h2>
            <div class="serv-content">
                <div class="card">
                    <div class="box">
                        <section class="4u$ 12u$(small)" id="circle"><i class="fas fa-clock"></i><br><br>
                            <p style="font-family: 'Balsamiq Sans', cursive;">Digital Market</p>
                        </section>

                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <section class="4u$ 12u$(small)" id="circle"><i class="fas fa-clock"></i><br><br>
                            <p style="font-family: 'Balsamiq Sans', cursive;">Agri-Articles</p>
                        </section>
                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <section class="4u$ 12u$(small)" id="circle"><i class="fas fa-clock"></i><br><br>
                            <p style="font-family: 'Balsamiq Sans', cursive;">Register with us</p>
                        </section>

                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>


    <!-- skills section start -->
    <section class="skills" id="skills">
        <div class="max-width">
            <h2 class="title">Our Products</h2>
            <div class="skills-content">
                <div class="column left">
                    <div class="text">Products.</div>
                    <p> We offer different categories of products. On a weekly basis, the illustration displayed on the
                        side shows the average statistics of the products
                        sold.</p>
                    <a href="productMenu.php">Read more</a>
                </div>
                <div class="column right">
                    <div class="bars">
                        <div class="info">
                            <span>Vegetables</span>
                            <span>90%</span>
                        </div>
                        <div class="line html"></div>
                    </div>
                    <div class="bars">
                        <div class="info">
                            <span>Grains</span>
                            <span>65%</span>
                        </div>
                        <div class="line css"></div>
                    </div>
                    <div class="bars">
                        <div class="info">
                            <span>Fruits</span>
                            <span>80%</span>
                        </div>
                        <div class="line js"></div>
                    </div>
                    <div class="bars">
                        <div class="info">
                            <span>Tools</span>
                            <span>50%</span>
                        </div>
                        <div class="line php"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- teams section start 
    <section class="teams" id="teams">
        <div class="max-width">
            <h2 class="title">Gallery</h2>
            <div class="carousel owl-carousel">
                <div class="card">
                    <div class="box">
                        <img src="images/tea.jpg" alt="">
                        <div class="text">Tea Farmers</div>
                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <img src="images/capsicum.jpg" alt="">
                        <div class="text">Capsicum</div>
   
                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <img src="images/onionsTypes.jpg" alt="">
                        <div class="text">Onions</div>
                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <img src="images/crop-beans.jpg" alt="">
                        <div class="text">Beans</div>
                    </div>
                </div>
                <div class="card">
                    <div class="box">
                        <img src="images/digitalFarming.jpg" alt="">
                        <div class="text">Digital Farming</div>
                    </div>
                </div>
				<div class="card">
                    <div class="box">
                        <img src="images/partnership.jpg" alt="">
                        <div class="text">Partnership</div>
                    </div>
                </div>
				<div class="card">
                    <div class="box">
                        <img src="images/food.jpg" alt="">
                        <div class="text">Vegetables</div>
                    </div>
                </div>
				<div class="card">
                    <div class="box">
                        <img src="images/maize.jpg" alt="">
                        <div class="text">Fresh Corn</div>
                    </div>
                </div>
				<div class="card">
                    <div class="box">
                        <img src="images/foodSecurity.jpg" alt="">
                        <div class="text">Food Security</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

     contact section start -->
    <section class="contact" id="contact">
        <div class="max-width">
            <h2 class="title">Contact us</h2>
            <div class="contact-content">
                <div class="column left">
                    <div class="text">Reach Us</div>
                    <div class="icons">
                        <div class="row">
                            <i class="fas fa-phone"></i>
                            <div class="info">
                                <div class="head">Telephone</div>
                                <div class="sub-title">+254 707 602 068</div>
                            </div>
                        </div>


                        <div class="row">
                            <i class="fas fa-envelope"></i>
                            <div class="info">
                                <div class="head">Email</div>
                                <div class="sub-title">info@agrocompanion.co.ke</div>
                                <div class="sub-title">admin@agrocompanion.co.ke</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column right">
                    <div class="text">Send us a message</div>
                    <form class="contact-form" action="#" method="POST">
                        <div class="fields">
                            <div class="field name">
                                <input type="text" class="fullname" placeholder="Name">
                            </div>
                            <div class="field email">
                                <input type="text" class="email-input" placeholder="Email">
                            </div>
                        </div>
                        <div class="field">
                            <input type="text" class="subject" placeholder="Subject">
                        </div>
                        <div class="field textarea">
                            <textarea class="message" cols="30" rows="10" placeholder="Message.."></textarea>
                        </div>
                        <div class="button-area">
                            <button class="send-msg" type="submit" name="send">Send message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- footer section start -->



    <?php
include('footer/footer.php')
?>

</body>

</html>