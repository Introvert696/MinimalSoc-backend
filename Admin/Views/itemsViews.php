<?php
require_once 'layout/header.php';
?>

<div class="album py-5 bg-light">
    <div class="container">

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <!--  -->
           
<?php foreach($items_arr as $key =>$value){ ?>
                <div class="col">
                    <div class="card shadow-sm">
                        <img src="<?php echo $value['image'] ?>" alt="">

                        <div class="card-body">
                            <p>
                            <h3><?php echo $value['title'] ?></h3>
                            </p>
                            <p class="card-text"><?php echo $value['desk'] ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="/items/get/<?php echo $value['id'] ?>" class="m-1" target="about/blank">
                                        <button type="button" class="btn btn-sm btn-outline-secondary">Посмотреть</button>
                                    </a>
                                    <a href="/panel/edit/<?php echo $value['id'] ?>" class="m-1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary ">Изменить</button>
                                    </a>
                                    <a href="/panel/delete/<?php echo $value['id'] ?>" class="m-1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary ">Удалить</button>
                                    </a>


                                </div>
                                <small class="text-muted"></small>
                            </div>
                        </div>
                    </div>
                </div>
          
<?php } ?>

            <!--  -->
        </div>
    </div>
</div>


<?php
require_once 'layout/footer.php';
?>