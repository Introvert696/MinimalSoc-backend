<?php
require_once 'layout/header.php';
?>

<div class="container">
    <form action="/panel/create" method="POST">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Название</label>
            <input type="text" class="form-control" id="title" name="title" ">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Ссылка на превью</label>
            <input type="text" class="form-control" id="image" name="image" ">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Описание</label>
            <input type="text" class="form-control" id="desk" name="desk" ">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Текст</label>
            <textarea type="text" class="form-control" id="text" name="text" style="height: 40vh;"></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
</div>
<?php
require_once 'layout/footer.php';
?>