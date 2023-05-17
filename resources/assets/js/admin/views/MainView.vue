<template>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-lg-4 col-md-6 col-sm-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>
              1
              <sup style="font-size: 20px">%</sup>
            </h3>

            <p>Успешных сделок за сутки</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer">
            Подробнее
            <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-4 col-md-6 col-sm-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>1</h3>

            <p>Новых сделок за сутки</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <a href="#" class="small-box-footer">
            Подробнее
            <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-4 col-md-6 col-sm-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>1</h3>

            <p>Новых пользователей за сутки</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="#" class="small-box-footer">
            Подробнее
            <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <!-- ./col -->
    </div>
  </section>
  <!-- /.content -->
</template>

<script>
import ViewMixins from "../mixins/view";

export default {
  mixins: [ViewMixins],
  data() {
    return {};
  }
};
</script>

<style scoped></style>
