const createBidModule = {
    namespaced: true,
    state: {
        step_subzero: {
            token: null,
            needAuth: 0
        },
        step_0: {
            uploadedFile: null,
            name: null,
            category: null,
            description: null,
            city: null,
            dqh_doc_confirm_quality: false
        },
        step_1: {
            period: {
                id: 2,
                value: "В месяц"
            },
            otherPeriod: null,
            startDate: "",
            endDate: "",
            unit_for_all: null,
            date_of_event: null,
            questions: {
                dqh_type_deal: "once",
                dqh_min_party: null,
                dqh_volume: null
            }
        },
        step_2: {
            delivery: null,
            pickup: null,
            whoUnpack: null,
            whenDelivery: null,
            deliveryTime: {
                startTime: null,
                endTime: null
            },
            dqh_logistics: []
        },
        step_3: {
            dqh_payment_method: [],
            dqh_payment_term: [],
            prePayType: null,
            prePayProcent: null,
            prePayDelayDays: null,
            budget_from: null,
            delayDays: null,
            budget_to: null,
            unit_for_unit: null,
            nds: false
        },
        create_sell: {
            name: null,
            category: null,
            description: null,
            city: null,
            uploadedFiles: [],
            keywords: null
        }
    },
    mutations: {
        saveStep(state, payload) {
            state[payload.step] = Object.assign({}, payload.data);
        },
        // обнуляем объекты хранилища
        clearSteps(state) {
            state.step_subzero = Object.assign({}, {
                token: null,
                needAuth: 0
            });
            state.step_0 = Object.assign({}, {
                uploadedFile: null,
                name: null,
                category: null,
                description: null,
                city: null,
                dqh_doc_confirm_quality: false
            });
            state.step_1 = Object.assign({}, {
                period: {
                    id: 2,
                    value: "В месяц"
                },
                otherPeriod: null,
                startDate: "",
                endDate: "",
                unit_for_all: null,
                date_of_event: null,
                questions: {
                    dqh_type_deal: "once",
                    dqh_min_party: null,
                    dqh_volume: null
                }
            });
            state.step_2 = Object.assign({}, {
                delivery: null,
                pickup: null,
                whoUnpack: null,
                whenDelivery: null,
                deliveryTime: {
                    startTime: null,
                    endTime: null
                },
                dqh_logistics: []
            });
            state.step_3 = Object.assign({}, {
                dqh_payment_method: [],
                dqh_payment_term: [],
                prePayType: null,
                prePayProcent: null,
                prePayDelayDays: null,
                budget_from: null,
                delayDays: null,
                budget_to: null,
                unit_for_unit: null,
                nds: false
            });
            state.create_sell = Object.assign({}, {
                name: null,
                category: null,
                description: null,
                city: null,
                uploadedFiles: [],
                keywords: null
            });
        }
    },
    getters: {
        getStep: state => payload => {
            return state[payload];
        }
    },
    actions: {
        saveStep(context, payload) {
            context.commit("saveStep", payload);
        },
        clearSteps(context) {
            context.commit("clearSteps");
        }
    }
};
export default createBidModule;