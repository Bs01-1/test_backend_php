<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test backend</title>
</head>
<body style="display: grid; grid-template-columns: repeat(2, 300px)">
    <!-- Меню-->
    <div>
        <a href="/">Все товары</a>
        <?=\classes\Core::getBlock('menu', ['groups' => $groups, 'key' => 0])?>
    </div>

    <!-- Продукты -->
    <div class="products-wrapper">
        <?=\classes\Core::getBlock('products', ['products' => $products])?>
    </div>
</body>
</html>
<style>
    .products-wrapper > p {
        padding: 0;
        margin: 0;
    }
    .active {
        color: green;
    }
</style>