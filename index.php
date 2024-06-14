<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css"
          integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd"
        crossorigin="anonymous"></script>
<div class="custom-top-bar">
    <nav>
        <ul class="w-100 mt-0 nav nav-tabs row justify-content-around mt-3 navbar-nav">
            <a class="custom-title navbar-brand" href="">Dungeons and Databases</a>
            <li class="col nav-item active"><a data-toggle="tab" href="#CHARACTER">Characters</a></li>
            <li class="col nav-item"><a data-toggle="tab" href="#CLASS">Classes</a></li>
            <li class="col nav-item"><a data-toggle="tab" href="#RACE">Races</a></li>
            <li class="col nav-item"><a data-toggle="tab" href="#BACKGROUND">Backgrounds</a></li>
            <li class="col nav-item"><a data-toggle="tab" href="#FEAT">Feats</a></li>
            <li class="col nav-item"><a data-toggle="tab" href="#FEATURE">Features</a></li>
            <li class="col nav-item"><a data-toggle="tab" href="#ITEM">Items</a></li>
        </ul>
    </nav>
</div>
<div class="custom-content">
    <div class="tab-content">

        <div class="tab-pane active" id="CHARACTER">
            <?php
            error_reporting(-1);
            ini_set('display_errors', 1);
            include("tables/Character.php");
            ?>
        </div>

        <div class="tab-pane" id="CLASS">
            <?php
            error_reporting(-1);
            ini_set('display_errors', 1);
            include("tables/Class.php");
            ?>
        </div>

        <div class="tab-pane" id="RACE">
            <?php
            error_reporting(-1);
            ini_set('display_errors', 1);
            include("tables/Race.php");
            ?>
        </div>

        <div class="tab-pane" id="BACKGROUND">
            <?php
            error_reporting(-1);
            ini_set('display_errors', 1);
            include("tables/Background.php");
            ?>
        </div>

        <div class="tab-pane" id="FEAT">
            <?php
            error_reporting(-1);
            ini_set('display_errors', 1);
            include("tables/Feat.php");
            ?>
        </div>

        <div class="tab-pane" id="FEATURE">
            <?php
            error_reporting(-1);
            ini_set('display_errors', 1);
            include("tables/Feature.php");
            ?>
        </div>

        <div class="tab-pane" id="ITEM">
            <?php
            error_reporting(-1);
            ini_set('display_errors', 1);
            include("tables/Item.php");
            ?>
        </div>
    </div>


</div>
</body>
</html>