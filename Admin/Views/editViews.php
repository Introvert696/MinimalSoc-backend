<?php
require_once 'layout/header.php';
?>

<div class="container">
    <form action="/panel/edit/<?php echo $item['id'] ?>" method="POST">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Название</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo $item['title'] ?>">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Ссылка на превью</label>
            <input type="text" class="form-control" id="image" name="image" value="<?php echo $item['image'] ?>">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Описание</label>
            <input type="text" class="form-control" id="desk" name="desk" value="<?php echo $item['desk'] ?>">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Текст</label>
            <textarea type="text" class="form-control" id="text" name="text" style="height: 40vh;"><?php echo $item['text'] ?></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Изменить</button>
    </form>
</div>
<?php
require_once 'layout/footer.php';
?>