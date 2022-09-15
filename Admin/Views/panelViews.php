<?php
require_once 'layout/header.php';
?>

<div class="container ">
  <div class="row ">
    <div class="card m-1" style="width: 18rem;">
      <p><h2 class="m-5">Количество статей: <?php echo $amout_items ?></h2></p>
    </div>
    <div class="card m-1"  style="width: 18rem;">
      <p><h5 class="m-5">Последняя статья: <br> <?php echo $last_item['title']  ?></h5></p>
    </div>
  </div>

</div>
<?php
require_once 'layout/footer.php';
?>