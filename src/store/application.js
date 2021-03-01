import axios from "axios";
import Table from "@/components/Table";
import Card from "@/components/Card";

export default {
    state: {
        menu: [],
        authenticated: false,
        user: null
    },
    mutations: {
        setMenu(state, menu) {
            state.menu = menu;
        },
        setAuthenticated(state, authenticated) {
            state.authenticated = authenticated;
        },
        setUser(state, user) {
            state.user = user;
        }
    },
    actions: {
        async signIn({ dispatch }, credentials) {
            await dispatch('getCsrfToken');
            await axios.post('https://erp.katzen48.de/login', credentials, {
                withCredentials: true
            });

            return dispatch('me');
        },

        async getCsrfToken() {
            await axios.get('https://erp.katzen48.de/sanctum/csrf-cookie', {
                withCredentials: true
            });
        },

        async signOut ({ dispatch }) {
            await axios.post('https://erp.katzen48.de/logout', {
                withCredentials: true
            })

            return dispatch('me')
        },

        me ({ commit }) {
            return axios.get('https://erp.katzen48.de/api/user', {
                withCredentials: true
            }).then((response) => {
                commit('setAuthenticated', true)
                commit('setUser', response.data)
            }).catch(() => {
                commit('setAuthenticated', false)
                commit('setUser', null)
            })
        },

        async loadRoutes({ commit }, $router) {
            let res = await axios.get('https://erp.katzen48.de/api/application/structure', {
                withCredentials: true
            });
            res = res.data;
            commit("setMenu", res)

            for (const [key, value] of Object.entries(res)) {
                if (!value.parent) {
                    $router.addRoute({
                        path: '/' + key,
                        name: key,
                        component: value.type == 'List' ? Table : Card,
                    });
                }
            }
        }
    },
    modules: {
    },
}