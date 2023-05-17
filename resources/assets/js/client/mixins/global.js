import moment from "moment";
require("moment/locale/ru");

var mixin = {
  computed: {
    isMobile() {
      return window.innerWidth < 768;
    },
    isTablet() {
      return window.innerWidth < 992;
    }
  },
  methods: {
    /**
     * Copy a string to clipboard
     * @param  {String} string         The string to be copied to clipboard
     * @return {Boolean}               returns a boolean correspondent to the success of the copy operation.
     */
    copyToClipboard(string) {
      let textarea;
      let result;

      try {
        textarea = document.createElement("textarea");
        textarea.setAttribute("readonly", true);
        textarea.setAttribute("contenteditable", true);
        textarea.style.position = "fixed"; // prevent scroll from jumping to the bottom when focus is set.
        textarea.value = string;

        document.body.appendChild(textarea);

        textarea.focus();
        textarea.select();

        const range = document.createRange();
        range.selectNodeContents(textarea);

        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);

        textarea.setSelectionRange(0, textarea.value.length);
        result = document.execCommand("copy");
      } catch (err) {
        console.error(err);
        result = null;
      } finally {
        document.body.removeChild(textarea);
      }

      // manual copy fallback using prompt
      if (!result) {
        const isMac = navigator.platform.toUpperCase().indexOf("MAC") >= 0;
        const copyHotkey = isMac ? "⌘C" : "CTRL+C";
        result = prompt(`Press ${copyHotkey}`, string); // eslint-disable-line no-alert
        if (!result) {
          return false;
        }
      }
      return true;
    },
    yandexReachGoal(targetName) {
      const counterNumber = "76387882";
      if (window.isProdMode && window.ym) {
        console.log('here');
        window.ym(counterNumber, "reachGoal", targetName);
      }
    },
    googleReachGoal(targetName) {
      if (window.isProdMode && window.gtag) {
        gtag("event", targetName);
      }
    },
    goToUrlWithReachGoal(targetName, url) {
      this.yandexReachGoal(targetName);
      this.googleReachGoal(targetName);
      window.location = url;
    },
    goToRouteWithReachGoal(targetName, url) {
      this.yandexReachGoal(targetName);
      this.googleReachGoal(targetName);
      this.$router.push({path: url}).then(r => {}).catch(e => {console.log(e)});
    },
    getProfile() {
      return this.$store.getters.getProfileState;
    },

    getCity() {
      return this.$root.currentCity;
    },

    dateFormat(d, s = "D MMMM YYYY") {
      const date = moment(d);
      if (date.isValid()) return date.locale("ru").format(s);
      return false;
    },
    dateTimeformat(d) {
      const date = moment(d);
      if (date.isValid())
        return date.locale("ru").calendar(null, {
          sameElse: "D MMMM YYYY"
        });
      return false;
    },
    priceFormat(price) {
      const p = parseFloat(price, 10);
      if (p === 0) {
        return p;
      } else {
        if (p !== null && p !== false) {
          return p.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1 ");
        }
      }
    },
    phoneFormat(p) {
      if (p)
        return p
          .toString()
          .replace(/(\d{3})(\d{3})(\d{2})(\d{2})/, "+7 ($1) $2-$3-$4");
    },

    onlyNumber(e, isFloat) {
      const evt = e ? e : window.event;
      const charCode = evt.which ? evt.which : evt.keyCode;
      let dot = true;

      if (isFloat) dot = charCode !== 46;
      if (
        charCode > 31 &&
        (charCode < 48 || charCode > 57) &&
        (charCode < 96 || charCode > 105) &&
        dot
      ) {
        evt.target.addEventListener(
          "change",
          evtChange => {
            setTimeout(() => {
              const newVal = evtChange.target.value.replace(/[^\d]/, "");
              evtChange.target.value = newVal;
            }, 15);
          },
          { once: true }
        );
        evt.returnValue = false;
        evt.preventDefault();
        evt.stopPropagation();
        return false;
      } else {
        return true;
      }
    },

    formatPriceFormField(fieldName) {
      const price = parseInt(this.form[fieldName]);
      if (price >= 0) {
        if (price == 0) {
          this.form[fieldName] = "";
        } else {
          this.form[fieldName] = this.priceFormat(
            this.form[fieldName].replace(/\s/g, "")
          );
        }
      }
    },

    getCookie(name) {
      var matches = document.cookie.match(
        new RegExp(
          "(?:^|; )" +
            name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") +
            "=([^;]*)"
        )
      );
      return matches ? decodeURIComponent(matches[1]) : undefined;
    },
    setCookie(name, value, options) {
      options = options || {};

      let expires = options.expires;

      if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
      }
      if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
      }

      if (!options.path) options.path = "/";

      value = encodeURIComponent(value);

      let updatedCookie = name + "=" + value;

      for (let propName in options) {
        updatedCookie += "; " + propName;
        const propValue = options[propName];
        if (propValue !== true) {
          updatedCookie += "=" + propValue;
        }
      }
      document.cookie = updatedCookie;
    },
    deleteCookie(name) {
      setCookie(name, "", {
        expires: -1
      });
    },

    // TODO: may need to replace on https://docs.sentry.io/platforms/javascript/vue/
    printErrorOnConsole(err, type = "danger", msg) {
      let color;
      let title = "Error";
      if (msg) msg = msg + " ";

      switch (type) {
        case "danger":
          color = "color:red;";
          break;
        case "warning":
          color = "color:orange;";
          title = "Warning";
          break;
        case "info":
          color = "color:gray;";
          title = "Info";
          break;
      }

      if (window.isDevMode) {
        console.group(`%c${title}:`, color);
        if (msg) console.log(`%c${msg}`, color);
        if (typeof err === "string") {
          console.log(`%c%s`, color, err);
        } else {
          console.log(`%c%o`, color, err);
          // if (err.response) {}
          // else if (err.message) {}
        }
        console.groupEnd();
      } else {
        switch (type) {
          case "danger":
            if (typeof err === "string") {
              console.error(err);
            } else {
              console.error("Произошла ошибка");
            }
            break;
          case "warning":
            if (typeof err === "string") {
              console.warn(err);
            } else {
              console.warn("Возможна не корректная работа");
            }
            break;
          case "info":
            if (typeof err === "string") {
              console.info(err);
            }
            break;
        }
      }

      this.writeLogOnServer({
        url: window.location.href,
        msg: `${msg}${title}`,
        err
      });
    },

    writeLogOnServer(data) {
      $.post("/api/v1/log/js", data).fail((trace, type, msg) => {
        console.error(`Error write log\n${type}: ${msg}`);
      });
    },

    setTitle(title) {
      document.title = `${APPNAME} - ${title}`;
      this.$route.meta.title = `${APPNAME} - ${title}`;
    },

    paralaxMouse(evt, target, layer = 1, strength = 20, wrapper = window) {
      if (target) {
        const layerCoeff = strength / layer;
        const x = (evt.pageX - wrapper.offsetWidth / 2) / layerCoeff;
        const y = (evt.pageY - wrapper.offsetHeight / 2) / layerCoeff;
        target.style.transform = `translate(${x}px, ${y}px)`;
      }
    }
  }
};

export default mixin;
