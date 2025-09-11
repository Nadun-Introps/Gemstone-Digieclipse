<div id="shop-by-categories">
    <shop-by-categories :src="'{{ $src }}'"></shop-by-categories>
</div>

@push('styles')
<style>
.shop-by-categories-container {
    padding: 50px 0; 
    text-align: center;
}
.shop-by-categories-container h2 {
    font-size: 26px; 
    font-weight: 200; 
    margin-bottom: 25px; 
    text-align: left; 
    margin-left: 100px;
}
.shop-by-categories-grid {
    display: grid; 
    grid-template-columns: repeat(4, 1fr); 
    gap: 20px; 
    max-width: 1100px; 
    margin: auto;
}
.shop-category-card {
    background: #f9f9f9; 
    border-radius: 12px; 
    padding: 15px 10px; 
    text-align: center;
    transition: all 0.3s ease; 
    flex: 0 0 auto; 
    scroll-snap-align: center;
}
.shop-category-card:hover {
    transform: translateY(-5px); 
    box-shadow: 0 6px 15px rgba(0,0,0,0.15); 
    background: #FFF9D6;
}
.shop-category-card img {
    width: 100%; 
    height: auto; 
    border-radius: 50%; 
    object-fit: cover; 
    max-width: 180px; 
    margin: auto;
}
.shop-category-card h3 { 
    margin: 15px 0 5px; 
    font-size: 16px; 
    font-weight: 600; 
}
.shop-category-card p { 
    font-size: 13px;
    color: #555; 
    }
.show-more-btn { 
    margin: 20px auto 0; 
    display: block; 
    padding: 10px 20px; 
    border: none; 
    background: #000; 
    color: #fff; 
    cursor: pointer; 
    border-radius: 6px; 
    transition: all 0.3s ease; }
.show-more-btn:hover { 
    background: #555; 
}

/* Tablet */
@media (max-width: 992px) {
    .shop-by-categories-grid { 
        grid-template-columns: repeat(2, 1fr); 
    }
}

/* Mobile */
@media (max-width: 576px) {
    .shop-by-categories-grid {
        grid-template-columns: 1fr; 
        display: grid; 
        gap: 10px; 
        padding: 0 10px;
    }
    .shop-category-card {
        min-width: 100%; 
        max-width: 100%; 
        padding: 10px;
    }
    .shop-category-card img { 
        max-width: 120px; 
    }
    .shop-by-categories-container h2 { 
        margin-left: 10px; 
        font-size: 20px; 
    }
}
</style>
@endpush

@push('scripts')
<script type="module">
if (typeof app !== 'undefined') {
    app.component('shop-by-categories', {
        props: ['src'],
        data() {
            return {
                categories: [],
                isLoading: true,
                showAll: false,
                maxVisibleDesktop: 8,
                maxVisibleMobile: 2,
                isMobile: window.innerWidth <= 576
            };
        },
        mounted() {
            this.getCategories();
            window.addEventListener('resize', this.checkMobile);
        },
        beforeUnmount() {
            window.removeEventListener('resize', this.checkMobile);
        },
        methods: {
            getCategories() {
                this.$axios.get(this.src)
                    .then(response => {
                        this.categories = response.data.data;
                        this.isLoading = false;
                    })
                    .catch(error => console.error(error));
            },
            toggleShowMore() {
                this.showAll = !this.showAll;
            },
            checkMobile() {
                this.isMobile = window.innerWidth <= 576;
            }
        },
        computed: {
            visibleCategories() {
                if(this.isMobile) {
                    return this.showAll ? this.categories : this.categories.slice(0, this.maxVisibleMobile);
                } else {
                    return this.showAll ? this.categories : this.categories.slice(0, this.maxVisibleDesktop);
                }
            }
        },
        template: `
            <div class="shop-by-categories-container">
                <h2>Shop By <strong>Categories</strong></h2>

                <div class="shop-by-categories-grid" v-if="!isLoading && categories.length">
                    <div class="shop-category-card" v-for="category in visibleCategories" :key="category.id">
                        <a :href="category.slug">
                            <img :src="category.logo?.large_image_url || '{{ bagisto_asset('images/small-product-placeholder.webp') }}'" :alt="category.name">
                        </a>
                        <h3>@{{ category.name }}</h3>
                        <p>See the Collection</p>
                    </div>
                </div>

                <!-- Show More Button -->
                <button v-if="categories.length > (isMobile ? maxVisibleMobile : maxVisibleDesktop)" class="show-more-btn" @click="toggleShowMore">
                    @{{ showAll ? 'Show Less' : 'Show More' }}
                </button>

                <template v-if="isLoading">
                    <x-shop::shimmer.categories.carousel :count="8" />
                </template>
            </div>
        `
    });
} else {
    console.error('Bagisto Vue instance not found!');
}
</script>
@endpush

