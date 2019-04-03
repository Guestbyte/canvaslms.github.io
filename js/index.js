// Vuex Store
const store = new Vuex.Store({
    state: {
        cartItems: [],
        products: [],
        loading: true,
        errored: false
    },
    mutations: {
        ADD_NEW_ITEM: (state, item) => {
            item.isDeleting = false;
            item = {...item,
                quantity: 1
            }
            state.cartItems = [...state.cartItems, item];
        },
        UPDATE_CART: (state, item) => {
            let findIndex = state.cartItems.findIndex(x => x.name == item.name);
            state.cartItems[findIndex].quantity++;
        },
        CHANGE_QUANTITY: (state, {
            index,
            increase
        }) => {
            if (increase) {
                state.cartItems[index].quantity++;
            } else {
                if (state.cartItems[index].quantity == 1) {
                    state.cartItems.splice(index, 1);
                } else {
                    state.cartItems[index].quantity--;
                }
            }
        },
        REMOVE_ALL: (state, index) => {
            state.cartItems.splice(index, 1);
        },
        LOAD_PRODUCTS: (state, products) => {
            state.products = products
            state.loading = false
        },
        ERRORED: (state, error) => {
            state.errored = true;
            console.error(error);
        }
    },
    actions: {
        removeAll: ({
            commit
        }, index) => {
            return new Promise((resolve) => {
                setTimeout(() => {
                    commit('REMOVE_ALL', index)
                    resolve()
                }, 700)
            })
        },
        retrieveProducts: ({
            commit
        }) => {
            // eslint-disable-next-line no-undef
            axios
                .get('https://api.jsonbin.io/b/5c6eadd27bded36fef1b653e/1')
                .then(response => {
                    commit('LOAD_PRODUCTS', response.data.products)
                })
                .catch(error => {
                    commit('ERRORED', error)
                })
        },
        addToCart: ({
            commit,
            state
        }, product) => {
            let found = state.cartItems.some((el) => {
                return el.name === product.name
            });
            if (!found) {
                commit('ADD_NEW_ITEM', product)
            } else {
                commit('UPDATE_CART', product)
            }
        }
    },
    getters: {
        cartCount: state => {
            if (state.cartItems.length === 0) {
                return 'empty'
            } else {
                return state.cartItems.reduce((a, b) => a + b.quantity, 0);
            }
        },
        cartTotal: state => {
            return (state.cartItems.reduce((a, b) => a + (b.price * b.quantity), 0)).toFixed(2);
        },
        itemCount: (state) => (index) => {
            if (index >= 0) {
                return state.cartItems[index].quantity
            }
        },
        itemTotal: (state) => (index) => {
            if (state.cartItems[index]) {
                return (state.cartItems[index].price * state.cartItems[index].quantity);
            }
        }
    }
})

// Vue filter
//Vue.filter('capitalise', function(val) {
//    return val.toUpperCase();
//});

// Vue component: homepage
const Homepage = {
    render: function(createElement) {
        return createElement(
            'div', {
                class: 'content'
            }, [
                createElement('div', {
                    attrs: {
                        style: 'float:left'
                    }
                }, [
                    createElement('h2', 'Welcome to the store'),
                    createElement('p', 'Select a department to get started')
                ]), createElement('img', {
                    class: 'vue-logo',
                    attrs: {
                        src: 'https://vuejs.org/images/logo.png'
                    }
                })
            ])
    }
}

// Vue component: product
const Product = {
    template: '#product',
    data() {
        return {
            product: {
                'name': this.name,
                'price': this.price
            }
        }
    },
    props: {
        name: String,
        img: String,
        price: Number
    },
    methods: {
        ...Vuex.mapMutations([
            'ADD_NEW_ITEM',
            'UPDATE_CART'
        ]),
        ...Vuex.mapActions([
            'addToCart'
        ])
    },
    computed: {
        ...Vuex.mapState([
            'cartItems'
        ]),
        ...Vuex.mapGetters([
            'itemCount'
        ]),
        formatPrice() {
            return this.price.toFixed(2);
        },
        itemIndex() {
            return this.cartItems.findIndex(x => x.name == this.name)
        },
        slashedName() {
            return this.name.replace(/\s+/g, '-').toLowerCase();
        }
    }
}

// Vue component: department
const Department = {
    template: '#department',
    components: {
        'product': Product
    },
    computed: {
        ...Vuex.mapState([
            'products',
            'loading',
            'errored'
        ]),
        filteredProducts() {
            return this.products.filter(x => x.department.toLowerCase() == this.$route.params.department)
        }
    }
}

// Vue component: UserReport
const UserReport = {
    template: '#user-report',
    data() {
        return {
            report: {
                'name': "User Report"
            }
        }
    },
    props: {
        name: String
    }
}

// Vue component: CourseReport
const CourseReport = {
    template: '#course-report',
    data() {
        return {
            report: {
                'name': "Course Report"
            }
        }
    },
    props: {
        name: String
    }
}

// Vue component: Setup
const Setup = {
    template: '#setup',
    data() {
        return {
            report: {
                'name': "General Setup"
            }
        }
    },
    props: {
        name: String
    }
}

// Vue component: product-detail
const ProductDetail = {
    template: '#product-detail',
    data() {
        return {
            lorem: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec posuere tortor ac sapien iaculis, vitae iaculis nunc iaculis. Mauris justo nisi, tempor venenatis felis vel, elementum venenatis mi. Morbi in dolor vehicula, sollicitudin ante non, eleifend tellus. Nunc mollis tortor quis sapien aliquet porttitor. Duis eu turpis vel sapien tristique sodales. Ut quis risus sed dui sagittis ultricies vel eu felis. Donec in cursus tortor, vitae vehicula nisl.'
        }
    },
    methods: {
        ...Vuex.mapActions([
            'addToCart'
        ])
    },
    computed: {
        ...Vuex.mapState([
            'products',
            'cartItems'
        ]),
        ...Vuex.mapGetters([
            'itemCount'
        ]),
        product() {
            let findProduct = this.products.filter(x => x.name == this.formattedProduct)
            return findProduct[0];
        },
        formattedProduct() {
            let removeSlash = this.$route.params.product.replace(/-/g, ' ')
            return removeSlash.replace(/\w\S*/g, function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        },
        productPayload() {
            return {
                'name': this.product.name,
                'price': this.product.price
            }
        },
        itemIndex() {
            return this.cartItems.findIndex(x => x.name == this.product.name)
        },
    }
}

// Vue Router
Vue.use(VueRouter)
const router = new VueRouter({
    routes: [{
            path: '/',
            name: 'homepage',
            component: Homepage
        },
        {
            path: '/department/:department',
            name: 'department',
            component: Department,
            props: true
        },
        {
            path: '/product/:product',
            name: 'product-detail',
            component: ProductDetail,
            props: true
        },
        {
            path: '/user-report',
            name: 'user-report',
            component: UserReport,
            props: true
        },
        {
            path: '/course-report',
            name: 'course-report',
            component: CourseReport,
            props: true
        },
        {
            path: '/setup',
            name: 'setup',
            component: Setup,
            props: true
        }
    ]
})

// Vue instance
new Vue({
    el: '#app',
    router,
    store,
    name: 'app',
    data() {
        return {
            appTitle: 'Admin Dashboard for Canvas LMS v.0.2',
            basketIsShown: false,
            showModal: false,
            drawer: false,
            snackbar: true,
            item: '',
            courses: [],
            UserReportName: "User Details",
            SetupPageName: "General Setup",
            CourseReportName: "Course Details"
        }
    },
    filters: {
        currency: (price) => {
            return parseFloat(price).toFixed(2);
        }
    },
    methods: {
        ...Vuex.mapMutations([
            'CHANGE_QUANTITY'
        ]),
        ...Vuex.mapActions([
            'removeAll',
            'retrieveProducts'
        ]),
        showBasket: function() {
            this.basketIsShown = !this.basketIsShown;
        },
        changeQuantity: function(index, increase) {
            this.CHANGE_QUANTITY({
                index,
                increase
            })
        },
        removeAllItems: function(item, index) {
            this.$store.state.cartItems[index].isDeleting = true;
            this.removeAll(index);
        },
        afterLeave() {
            window.scroll(0, 0)
        },
        retriveCourses() {
            // axios.get('/accounts/106/courses').then((courses) => {
            //     console.log(courses);
            //     this.courses = courses;
            // });
            axios.get('/accounts/106/courses').then((courses) => {
                console.log(courses.data);
                this.courses = courses;
            });

        }
    },
    computed: {
        ...Vuex.mapState([
            'cartItems',
            'products',
            'loading',
            'errored'
        ]),
        ...Vuex.mapGetters([
            'cartCount',
            'cartTotal',
            'itemTotal'
        ]),
        departments() {
            let allDepartments = this.products.map(x => {
                return x.department.toLowerCase();
            })
            return [...new Set(allDepartments)]
        }
    },
    mounted() {
        axios.defaults.baseURL = 'http://mathema.com.br/online/lms/API/api.php';
        this.retrieveProducts()
        this.retriveCourses()
    }
})