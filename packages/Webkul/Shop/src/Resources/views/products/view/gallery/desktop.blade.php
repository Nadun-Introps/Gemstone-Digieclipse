<div class="details-product flex flex-wrap gap-8"> <!-- Left: Image Gallery -->
    <div class="flex-1 max-w-[560px]"> <v-product-gallery ref="gallery"> <x-shop::shimmer.products.gallery />
        </v-product-gallery> </div> <!-- Right: Product Info -->
    <div class="flex-1 min-w-[300px]">
        <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>
        <div class="flex items-center gap-2 my-2">
            <div class="star-rating"> <span class="star-5"></span> </div>
            <div class="text-sm text-gray-500">({{ $product->reviews_count }})</div>
        </div>
        <div class="my-2"> <span class="font-medium">Availability:</span> <span
                class="text-green-600">{{ $product->haveSufficientQuantity(1) ? 'In Stock' : 'Out of Stock' }}</span>
        </div>
        <div class="text-xl font-bold my-2">${{ $product->price }}</div>
        <ul class="list-disc pl-5 text-gray-600 my-2">
            @foreach ($product->description as $desc)
                <li>{{ $desc }}</li>
                @endforeach
        </ul> <!-- Variations -->
        <div class="my-4">
            <div class="mb-2"> <span class="font-medium">Color:</span>
                <div class="flex gap-2 mt-1">
                    @foreach ($product->colors as $color)
                        <button class="w-6 h-6 rounded-full border"
                            :class="{ 'ring ring-navyBlue': selectedColor == '{{ $color->id }}' }"
                            @click="selectedColor='{{ $color->id }}'"
                            style="background-color: {{ $color->hex }}"></button>
                        @endforeach
                </div>
            </div>
            <div> <span class="font-medium">Size:</span>
                <div class="flex gap-2 mt-1">
                    @foreach ($product->sizes as $size)
                        <button class="px-2 py-1 border rounded"
                            :class="{ 'bg-navyBlue text-white': selectedSize == '{{ $size->id }}' }"
                            @click="selectedSize='{{ $size->id }}'">{{ strtoupper($size->name) }}</button>
                    @endforeach
                </div>
            </div>
        </div> <!-- Wishlist + Add to Cart -->
        <div class="flex items-center gap-4 mt-4"> <x-shop::products.add-to-wishlist-button :product="$product" />
            <div class="flex items-center gap-2"> <input type="number" min="1" value="1"
                    class="w-16 border rounded px-2 py-1"> <x-shop::products.add-to-cart-button :product="$product" />
            </div>
        </div>
    </div>
</div> @pushOnce('scripts')
    <script>
        // Vue Gallery logic (based on your old code) app.component('v-product-gallery', { template: '#v-product-gallery-template', data() { return { isImageZooming: false, isMediaLoading: true, media: { images: @json(product_image()->getGalleryImages($product)), videos: @json(product_video()->getVideos($product)), }, baseFile: { type: '', path: '' }, activeIndex: 0, containerOffset: 110, }; }, mounted() { if (this.media.images.length) { this.baseFile.type = 'image'; this.baseFile.path = this.media.images[0].large_image_url; } else if (this.media.videos.length) { this.baseFile.type = 'video'; this.baseFile.path = this.media.videos[0].video_url; } }, computed: { attachments() { return [...this.media.images, ...this.media.videos]; }, lengthOfMedia() { return [...this.media.images, ...this.media.videos].length > 5; } }, methods: { change(media, index) { this.isMediaLoading = true; this.baseFile.type = media.type === 'videos' ? 'video' : 'image'; this.baseFile.path = media.type === 'videos' ? media.video_url : media.large_image_url; this.activeIndex = index; this.isMediaLoading = false; }, swipeTop() { this.$refs.swiperContainer.scrollTop -= this.containerOffset; }, swipeDown() { this.$refs.swiperContainer.scrollTop += this.containerOffset; }, isActiveMedia(index) { return index === this.activeIndex; } } }); 
    </script>
@endpushOnce
