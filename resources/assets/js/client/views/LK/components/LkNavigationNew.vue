<template lang="pug">
.sidebar
  ProfileInfo(:profile="profile")
  .sidebar_menu(v-if="$root.profile")
    ul
      li
        router-link(:to="{ name: 'lk.deals' }")
          .sidebar_text
            svg(width="14", height="17", viewBox="0 0 14 17", fill="none", xmlns="http://www.w3.org/2000/svg")
              path(d="M7.75 1.21582H2.5C2.10218 1.21582 1.72064 1.37361 1.43934 1.65447C1.15804 1.93534 1 2.31627 1 2.71348V14.6947C1 15.0919 1.15804 15.4729 1.43934 15.7537C1.72064 16.0346 2.10218 16.1924 2.5 16.1924H11.5C11.8978 16.1924 12.2794 16.0346 12.5607 15.7537C12.842 15.4729 13 15.0919 13 14.6947V6.45762L7.75 1.21582Z", stroke="#000", stroke-linecap="round", stroke-linejoin="round")
              path(d="M7.75085 1.21582V6.45758H13.0009", stroke="#000", stroke-linecap="round", stroke-linejoin="round")
            span Мои объявления
          .sidebar_circle(v-if="profile.notifications && profile.notifications.unreaded_owner_deal>0") {{profile.notifications.unreaded_owner_deal}}
      li
        router-link(:to="{ name: 'lk.responses' }")
          .sidebar_text
            svg(width="17", height="15", viewBox="0 0 17 15", fill="none", xmlns="http://www.w3.org/2000/svg")
              path(
                d="M2.5 1.14551H14.5C14.8978 1.14551 15.2794 1.29745 15.5607 1.56791C15.842 1.83838 16 2.2052 16 2.58769V6.91425C16 8.82671 15.2098 10.6608 13.8033 12.0132C12.3968 13.3655 10.4891 14.1252 8.5 14.1252C7.51509 14.1252 6.53982 13.9387 5.62987 13.5763C4.71993 13.2139 3.89314 12.6827 3.1967 12.0132C1.79018 10.6608 1 8.82671 1 6.91425V2.58769C1 2.2052 1.15804 1.83838 1.43934 1.56791C1.72064 1.29745 2.10218 1.14551 2.5 1.14551V1.14551Z",
                stroke="black",
                stroke-linecap="round",
                stroke-linejoin="round"
              )
              path(d="M5.50073 6.19336L8.50073 9.0777L11.5007 6.19336", stroke="black", stroke-linecap="round", stroke-linejoin="round")
            span Отклики
          .sidebar_circle(v-if="profile.notifications && profile.notifications.unreaded_owner_response>0") {{profile.notifications.unreaded_owner_response}}
      li
        router-link(:to="{ name: 'lk.favorites' }")
          .sidebar_text
            svg(width="12", height="17", viewBox="0 0 12 17", fill="none", xmlns="http://www.w3.org/2000/svg")
              path(
                fill-rule="evenodd",
                clip-rule="evenodd",
                d="M4.99963 13.0538L9.99927 16.0488V4.06891C9.99927 3.53937 9.78857 3.03151 9.41352 2.65707C9.03848 2.28263 8.52981 2.07227 7.99941 2.07227H1.99985C1.46946 2.07227 0.960789 2.28263 0.585743 2.65707C0.210698 3.03151 0 3.53937 0 4.06891V16.0488L4.99963 13.0538ZM0.999927 14.2857L4.99963 11.8898L8.99934 14.2857V4.06891C8.99934 3.80414 8.89399 3.55021 8.70647 3.36299C8.51895 3.17577 8.26461 3.07059 7.99941 3.07059H1.99985C1.73466 3.07059 1.48032 3.17577 1.2928 3.36299C1.10528 3.55021 0.999927 3.80414 0.999927 4.06891V14.2857Z",
                fill="black"
              )
              path(d="M12.0006 14.0517L11.0006 13.4527V2.07184C11.0006 1.80707 10.8953 1.55314 10.7078 1.36592C10.5203 1.1787 10.2659 1.07352 10.0007 1.07352H2.26929C2.44481 0.769996 2.69726 0.517949 3.00126 0.342707C3.30527 0.167466 3.65012 0.0752042 4.00116 0.0751953L10.0007 0.0751953C10.5311 0.0751953 11.0398 0.285555 11.4148 0.659999C11.7899 1.03444 12.0006 1.5423 12.0006 2.07184V14.0517Z", fill="black")
            span Закладки
      li
        router-link(:to="{ name: 'lk.dialogs' }")
          .sidebar_text
            svg(width="17", height="15", viewBox="0 0 17 15", fill="none", xmlns="http://www.w3.org/2000/svg")
              path(d="M16 9.65023C16 10.0327 15.8244 10.3995 15.5118 10.67C15.1993 10.9405 14.7754 11.0924 14.3333 11.0924H4.33333L1 13.9768V2.43926C1 2.05677 1.17559 1.68994 1.48816 1.41948C1.80072 1.14902 2.22464 0.99707 2.66667 0.99707H14.3333C14.7754 0.99707 15.1993 1.14902 15.5118 1.41948C15.8244 1.68994 16 2.05677 16 2.43926V9.65023Z", stroke="black", stroke-linecap="round", stroke-linejoin="round")
            span Сообщения
          .sidebar_circle(v-if="profile.unreadMsg") {{ profile.unreadMsg }}
      li
        router-link(:to="{ name: 'lk.wallet' }")
          .sidebar_text
            svg(width="18", height="13", viewBox="0 0 18 13", fill="none", xmlns="http://www.w3.org/2000/svg")
              path(d="M15.5455 0.923828H2.45455C1.65122 0.923828 1 1.53847 1 2.29668V10.5338C1 11.292 1.65122 11.9066 2.45455 11.9066H15.5455C16.3488 11.9066 17 11.292 17 10.5338V2.29668C17 1.53847 16.3488 0.923828 15.5455 0.923828Z", stroke="black", stroke-linecap="round", stroke-linejoin="round")
              path(d="M1 5.04199H17", stroke="black", stroke-linecap="round", stroke-linejoin="round")
            span Кошелёк
          .sidebar_money {{ priceFormat(profile.profile.ballance) }} ₽
      li
        router-link(:to="{ name: 'lk.paidservices' }")
          .sidebar_text
            svg(width="14", height="17", viewBox="0 0 14 17", fill="none", xmlns="http://www.w3.org/2000/svg")
              path(
                fill-rule="evenodd",
                clip-rule="evenodd",
                d="M13 5.49451H1V14.7703C1 15.0437 1.10536 15.3058 1.29289 15.4991C1.48043 15.6924 1.73478 15.801 2 15.801H12C12.2652 15.801 12.5196 15.6924 12.7071 15.4991C12.8946 15.3058 13 15.0437 13 14.7703V5.49451ZM0 4.46387V14.7703C0 15.317 0.210714 15.8413 0.585786 16.2279C0.960859 16.6144 1.46957 16.8316 2 16.8316H12C12.5304 16.8316 13.0391 16.6144 13.4142 16.2279C13.7893 15.8413 14 15.317 14 14.7703V4.46387H0Z",
                fill="black"
              )
              path(
                d="M7.00085 1.88712C6.33781 1.88712 5.70193 2.15859 5.23309 2.64181C4.76425 3.12504 4.50085 3.78043 4.50085 4.46381H3.50085C3.50085 3.50707 3.8696 2.58953 4.52598 1.91302C5.18236 1.23651 6.0726 0.856445 7.00085 0.856445C7.92911 0.856445 8.81935 1.23651 9.47573 1.91302C10.1321 2.58953 10.5009 3.50707 10.5009 4.46381H9.50085C9.50085 3.78043 9.23746 3.12504 8.76862 2.64181C8.29978 2.15859 7.6639 1.88712 7.00085 1.88712Z",
                fill="black"
              )
              path(
                fill-rule="evenodd",
                clip-rule="evenodd",
                d="M9.85463 8.22171C9.9012 8.26958 9.93814 8.32644 9.96334 8.38905C9.98855 8.45166 10.0015 8.51878 10.0015 8.58656C10.0015 8.65434 9.98855 8.72146 9.96334 8.78407C9.93814 8.84667 9.9012 8.90354 9.85463 8.95141L6.85463 12.0434C6.80819 12.0914 6.75301 12.1294 6.69227 12.1554C6.63152 12.1814 6.5664 12.1948 6.50063 12.1948C6.43486 12.1948 6.36974 12.1814 6.309 12.1554C6.24825 12.1294 6.19308 12.0914 6.14663 12.0434L4.64663 10.4974C4.55275 10.4006 4.5 10.2694 4.5 10.1325C4.5 9.99569 4.55275 9.86445 4.64663 9.76769C4.74052 9.67092 4.86786 9.61656 5.00063 9.61656C5.13341 9.61656 5.26075 9.67092 5.35463 9.76769L6.50063 10.9498L9.14663 8.22171C9.19308 8.17372 9.24825 8.13564 9.309 8.10966C9.36974 8.08368 9.43486 8.07031 9.50063 8.07031C9.5664 8.07031 9.63152 8.08368 9.69227 8.10966C9.75301 8.13564 9.80819 8.17372 9.85463 8.22171Z",
                fill="black"
              )
            span Платные услуги
      li
        router-link(:to="{ name: 'lk.settings' }")
          .sidebar_text
            svg(width="14", height="15", viewBox="0 0 14 15", fill="none", xmlns="http://www.w3.org/2000/svg")
              path(d="M13 13.7592V12.317C13 11.552 12.6839 10.8183 12.1213 10.2774C11.5587 9.7365 10.7956 9.43262 10 9.43262H4C3.20435 9.43262 2.44129 9.7365 1.87868 10.2774C1.31607 10.8183 1 11.552 1 12.317V13.7592", stroke="black", stroke-width="1.2", stroke-linecap="round", stroke-linejoin="round")
              path(d="M6.99939 6.54803C8.65624 6.54803 9.99939 5.25666 9.99939 3.66367C9.99939 2.07067 8.65624 0.779297 6.99939 0.779297C5.34254 0.779297 3.99939 2.07067 3.99939 3.66367C3.99939 5.25666 5.34254 6.54803 6.99939 6.54803Z", stroke="black", stroke-width="1.2", stroke-linecap="round", stroke-linejoin="round")
            span Настройки
</template>

<script>
import { mapGetters, mapState } from "vuex"
import ProfileInfo from "./ProfileInfo"
export default {
  components: {
    ProfileInfo
  },
  data() {
    return {}
  },
  computed: {
    ...mapGetters(["getDefaultValue", "getUnreadedMsg", "getProfileBalance", "getProfileOrganizationIsCreated"]),
    ...mapState(["profile"])
  },
  methods: {},
  mounted() {}
}
</script>

<style>
a {
  color: #000;
}

a:hover {
  color: #000;
}
</style>
