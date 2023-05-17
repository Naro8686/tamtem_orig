<template lang="pug">
fieldset.typeahead-wrap
  VueBootstrapTypeahead(
    title="Начните вводить город",
    :data="addressesList",
    v-model="addressValue",
    :serializer="(s) => s.title",
    placeholder="Город",
    ref="typeahead",
    @input="getAddresses(generateList)",
    @hit="addressSelected = $event"
  )
</template>
<script>
import VueBootstrapTypeahead from "./VueBootstrapTypeahead";

export default {
  components: {
    VueBootstrapTypeahead
  },
  props: {
    cityName: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      kladr: null,
      addressesList: [],
      addressValue: "",
      addressSelected: {}
    };
  },
  computed: {},
  methods: {
    getAddresses(callback) {
      if (this.addressValue.length) {
        axios
          .get("/api/v1/kladr/place?query=" + this.addressValue)
          .then((resp) => {
            this.kladr = resp.data.data;
            if (callback) {
              callback();
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    generateList() {
      if (this.kladr) {
        this.addressesList = [];
        const cities = this.kladr.cities;

        for (let i in cities) {
          const city = cities[i];
          city.title = this.getStringAddr(city);
          this.addressesList.push(city);
        }
      }
    },
    getStringAddr(city) {
      return city.name + ", " + city.region_name;
    }
  },
  mounted() {
    this.addressValue = this.cityName;
  },
  watch: {
    cityName(newVal, oldVal) {
      this.addressValue = newVal;
      this.getAddresses(() => {
        this.addressValue = this.kladr.cities[0].name;
        this.$refs.typeahead.inputValue = this.addressValue;
        this.addressSelected = this.kladr.cities[0];
      });
    },
    addressSelected(newVal, oldVal) {
      this.$emit("setCityEmit", newVal.id);
    }
  }
};
</script>
