import Vue from 'vue'
import App from './layouts/App'
import router from './router'
import store from './store'

Vue.config.productionTip = false;
function isLoggedIn() {
    return localStorage.getItem('isLoggedIn')
}

const app = new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app');
