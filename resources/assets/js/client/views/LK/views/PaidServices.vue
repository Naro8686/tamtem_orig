<template lang="pug">
.tabs
  .maintabs
    button.maintab.active Платные услуги
  .content
  span.top_line
  p.services_top_text Получайте заказы на сервисе tamtem.ru, оплачивая за контакт от 200 ₽
  .services_table
    .services_table_row
      .services_table_row_cell.cell_first
      .services_table_row_cell.cell_second
        div
          p.table_title_main Покупателям
          p.table_title_info от 0 рублей
      .services_table_row_cell.cell_third
        div
          p.table_title_main Продавцам *
          p.table_title_info от 200 рублей
    .services_table_row
      .services_table_row_cell.cell_first
        | Размещение объявлений
      .services_table_row_cell.cell_second
        p.services_table_green бесплатно
      .services_table_row_cell.cell_third
        p.services_table_green бесплатно
    .services_table_row.row_last
      .services_table_row_cell.cell_first
        | Заключение сделки
      .services_table_row_cell.cell_second
        p.services_table_green бесплатно
      .services_table_row_cell.cell_third.cell_doubled
        div
          p сумма сделки, руб
          p #{'<'} 500 000
          p #{'<'} 1 000 000
          p #{'<'} 3 000 000
        div
          p стоимость, руб
          p 200
          p 500
          p 800
  .services_mobile
    p.services_text_center_bold Размещение объявлений
    .services_mobile_box
      p.mobile_default_text Покупателям
      p.mobile_green бесплатно
    .services_mobile_box
      p.mobile_default_text Продавцам
      p.mobile_green бесплатно
    p.services_text_center_bold Заключение сделки
    .services_mobile_box
      p.mobile_default_text Покупателям
      p.mobile_green бесплатно
    .services_mobile_box
      p.mobile_default_text Продавцам
      p.mobile_default_text от 200 рублей
    .mobile_table
      .mobile_table_row
        .mobile_table_row_item.first_mobile_item
          p
            | сумма сделки,
            br
            |
            | руб
        .mobile_table_row_item
          p
            | стоимость,
            br
            |
            | руб
      .mobile_table_row
        .mobile_table_row_item.first_mobile_item
          p #{'<'} 500 000
            br 
            | #{'<'} 3 000 000
            br 
            | #{'<'} 1 000 000
        .mobile_table_row_item
          p
            | 200
            br
            | 500
            br
            | 800
  .undertable_text
    p * Вы можете отправлять ответы на неограниченное колличество заказов.
    p
      | * Продавец оплачивает предоставление контактных данных
      b только тогда, когда Покупатель выберет его
      | . Сумма оплаты за контакт видна при отправке предложения Покупателю.
</template>

<script>
export default {
  data() {
    return {};
  },
  mounted() {},
};
</script>
<style>
@media(max-width: 767px) {
  .top_line {
    display: none;
  }
  .services_top_text {
    display: none;
  }
}
</style>