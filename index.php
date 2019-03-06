<?php
    require_once 'db.php';

    $DB = DB::getInstance();

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = addslashes(strip_tags($_GET['search'])) . '%';
        $statement = $DB->prepare("SELECT * FROM reviews WHERE email LIKE :email");
        $statement->bindParam(':email', $search, \PDO::PARAM_STR);
    } else {
        $statement = $DB->prepare('SELECT * FROM reviews');
    }
    $statement->execute();

    $reviews = $statement->fetchAll(\PDO::FETCH_OBJ);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Отзывы на сайте</title>
    <link href="css/style.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <header>
            <div class="wrapper">
                <div class="block header-block in-row">
                    <div class="col block-title">Отзывы</div>
                    <div class="col text-right">
                        <form action="" class="search-form">
                            <div class="block in-row">
                                <div class="col">
                                    <input type="text" name="search" class="form-input" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" placeholder="Поиск по эл. почте" />
                                </div>
                                <div>
                                    <button class="button">Поиск</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <div class="wrapper">
            <form class="form review-form">
                <div class="block">
                    <div class="block-row form-label">
                        <label for="name">Имя</label>
                    </div>
                    <div class="block-row form-element">
                        <input type="text" id="name" name="name" class="form-input" required="required" />
                    </div>
                </div>
                <div class="block">
                    <div class="block-row form-label">
                        <label for="email">Эл. почта</label>
                    </div>
                    <div class="block-row form-element">
                        <input type="email" id="email" name="email" class="form-input" required="required" />
                    </div>
                </div>
                <div class="block">
                    <div class="block-row form-label">
                        <label for="review">Текст</label>
                    </div>
                    <div class="block-row form-element">
                        <textarea id="review" name="review" class="form-input" rows="7" required="required"></textarea>
                    </div>
                </div>
                <div class="block">
                    <div class="block-row">
                        <button class="button button-primary">Оставить отзыв</button>
                    </div>
                </div>
                <div class="block block-alert"></div>
            </form>
        </div>
        <div class="wrapper">
            <ul class="list review-list">
                <?php foreach ($reviews as $review): ?>
                <?php include ('html/review.html.php') ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>