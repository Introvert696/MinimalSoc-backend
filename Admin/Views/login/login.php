<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <title>Login Pages</title>
</head>

<body>
    <div class="container center ">
        <form class="w-25 position-absolute top-50 start-50 translate-middle" action="/panel/" method="post">

            <p>
            <h1>Login:</h1>
            </p>

            <div class="form-outline mb-4">
                <input type="text" id="login" name="login" class="form-control" />
                <label class="form-label" for="form2Example1">Логин</label>
            </div>


            <div class="form-outline mb-4">
                <input type="password" id="password" name="password" class="form-control" />
                <label class="form-label" for="form2Example2">Пароль:</label>
            </div>


            <button type="submit" class="btn btn-primary btn-block mb-4">Войти</button>


            </form>

    </div>

</body>

</html>