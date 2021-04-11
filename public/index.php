<?php

require_once __DIR__.'/../vendor/autoload.php';

$shortLink = getRequestPath();
getUrl($shortLink);
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>
<body>
<div class="container">
    <h1>Сокращение ссылок!</h1>
    <form id="form">
        <input type="text" name="url" id="url" class="form-control">
        <button type="submit" class="btn btn-info mt-3">submit</button>
</div>
</form>
<div class="container m-3">
    <ul id="short-link"></ul>
    <div class=" container out"></div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
<script>
    $("#form").on("submit", function () {
        event.preventDefault();
        $.ajax({
            url: '/get_link.php',
            method: 'post',
            dataType: 'html',
            data: $(this).serialize(),
            success: function (data) {
                console.log(data)
                if (data) {
                    let url = data;
                    let copyButton = '<button class="btn btn-info btn-copy ml-2">Copy text</button>'
                    let gotoButton = '<button class="btn btn-primary btn-goto ml-2">Goto Link</button>'
                    let res = '<li class="li-short-link"><a href="' + url + '" id="shortLinkHref">' + url + '</a>' + copyButton + gotoButton + '</li>';
                    $("#short-link").html(res);
                } else {
                    let res = '<li class="li-short-link" style="color: red">Введите оригинальный URL-адрес в поле ввода</li>';
                    $("#short-link").html(res);
                }

            }
        });
    });

    document.body.onclick = (event) => {
        const elem = event.target;
        const elemLink = document.querySelector('#shortLinkHref');

        if (elem.classList.contains('btn-copy')) {
            navigator.clipboard.writeText(elemLink.href)
                .then(() => {
                    document.querySelector('.out').innerHTML = '<p>' + elemLink.href + '<span style="color: forestgreen; margin-left: 15%">Success! Cкопировано.</span></p>';
                })
                .catch(err => {
                    console.log(err)
                });
        }
        if (elem.classList.contains('btn-goto')) {
            window.location.href = elemLink.href;
        }

    }

</script>
</body>
</html>