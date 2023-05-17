<template>
  <div class="shadow">
    <div class="blockcomponent--wrapper">
      <div class="closeform--wrapper">
        <button
          type="button"
          v-on:click="$emit('closeme')"
          class="btn pull-right closebutton"
        >
          X
        </button>
      </div>
      <div class="blockcomponent--body">
        <p class="blockcomponent--title">Создать заявку/ки (не более 10)</p>
        <div>
          <input
            type="radio"
            name="typedeal"
            id="type-buy"
            value="buy"
            v-model="typeDeal"
          />
          <label for="type-buy">Тип сделки - покупка</label>
          <br />
          <input
            type="radio"
            name="typedeal"
            id="type-sell"
            value="sell"
            v-model="typeDeal"
          />
          <label for="type-sell">Тип сделки - продажа</label>
        </div>
        <label for="fake">Фейковый заказ</label>
        <input type="checkbox" v-model.number="isFake" />
        <br />
        <label for="org_type">Количество:</label>
        <input type="text" v-model.number="deal_count" required />
        <button
          type="button"
          class="btn btn-default center-block savebutton"
          v-on:click="setDealCount()"
        >
          Ок
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import ViewMixins from "../../mixins/view";
export default {
  mixins: [ViewMixins],
  props: {
    userId: {
      type: [String, Number],
      required: true
    }
  },
  data() {
    return {
      isFake: 0,
      deal_count: 1,
      typeDeal: ""
    };
  },
  methods: {
    isInt(n) {
      return (n ^ 0) === n;
    },
    setDealCount() {
      if (this.isInt(this.deal_count)) {
        if (parseInt(this.deal_count) < 1) {
          this.showError("Количество заявок должно быть больше 0");
          return false;
        } else if (parseInt(this.deal_count) > 10) {
          this.showError("Количество заявок должно быть меньше 11");
          return false;
        } else {
          this.createDeals();
        }
      } else {
        this.showError("Введите целое число");
      }
    },
    createDeals() {
      var params = {
        // type_deal: "buy",
        type_deal: this.typeDeal,
        count_deal: this.deal_count,
        user_id: this.userId,
        is_fake: this.isFake
      };
      axios
        .post("/admin/api/deals/manager/store", params)
        .then((resp) => {
          if (resp.data.result == true) {
            this.$emit("closeme");
            this.showSuccess("Заявка/ки успешно создана/ны");
          } else {
            this.showError("Неизвестная ошибка. Попробуйте позднее");
          }
        })
        .catch((e) => {
          console.log(e);
          this.showError("Произошла ошибка. Попробуйте позднее");
        });
    }
  }
};
</script>
<style scoped>
.shadow {
  position: fixed;
  width: 100%;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  z-index: 10;
  background: #0006;
}
.blockcomponent--wrapper {
  width: 400px;
  position: fixed;
  left: calc(50% - 200px);
  top: 30%;
  background: white;
  padding: 15px;
}
.blockreason--textarea {
  resize: none;
  width: 100%;
  height: 100px;
}
.blockcomponent--title {
  font-weight: 600;
}
.closebutton {
  padding: 0;
  background: transparent;
  font-weight: 600;
  line-height: 0;
  cursor: pointer;
  height: 11px;
}
.savebutton {
  margin-top: 10px;
  margin-bottom: -2px;
}
</style>
