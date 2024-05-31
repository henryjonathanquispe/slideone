<template>
    <div
        ref="containercarousel"
        @resize="updateSlideWidth"
        class="carousel"
        :style="{
            '--var-sizes-sm': `${100 / sizes.sm}%`,
            '--var-sizes-md': `${100 / sizes.md}%`,
            '--var-sizes-lg': `${100 / sizes.lg}%`,
            '--var-sizes-xl': `${100 / sizes.xl}%`,
            '--var-sizes-2xl': `${100 / sizes['2xl']}%`,            
        }">
        <div
            class="slides"
            :style="{ transform: 'translateX(' + translateX + 'px)' }">
            <div
                class="slide"
                v-for="(item, index) in items"
                :key="index">
                <div
                    ref="slides"
                    class="content">
                    {{ item.text }}
                </div>
            </div>
        </div>
        <button
            class="prev"
            @click="prevSlide">
            Prev
        </button>
        <button
            class="next"
            @click="nextSlide">
            Next
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            resizeObserver:null,
            items: [
                { id: 1, text: "Slide 1" },
                { id: 2, text: "Slide 2" },
                { id: 3, text: "Slide 3" },
                { id: 1, text: "Slide 4" },
                { id: 2, text: "Slide 5" },
                { id: 3, text: "Slide 6" },
                { id: 1, text: "Slide 7" },
                { id: 2, text: "Slide 8" },
                { id: 3, text: "Slide 9" },
                { id: 1, text: "Slide 10" },
                { id: 2, text: "Slide 11" },
                { id: 3, text: "Slide 12" },
                // Add more items as needed
            ],
            currentIndex: 0,
            slideWidth: 0,
            translateX: 0,
            sizes: {
                sm: 1,
                md: 2,
                lg: 3,
                xl: 4,
                "2xl": 5,
            },
        }
    },
    computed: {        
        slideCount() {
            return this.items.length
        },
    },
    mounted() {
        this.slideWidth = this.$refs.slides[0].offsetWidth
        this.resizeObserver = new ResizeObserver(this.updateSlideWidth)
        this.resizeObserver.observe(this.$refs.containercarousel)

    },
    destroyed() {
        this.resizeObserver.unobserve(this.$refs.containercarousel)
    },
    methods: {
        updateSlideWidth() {
            this.slideWidth = this.$refs.slides[0].offsetWidth;
            this.translateX = this.slideWidth*(-this.currentIndex)
        },
        prevSlide() {           
            if (this.currentIndex > 0) {
                this.currentIndex--
                this.translateX += this.slideWidth
            }            
        },
        nextSlide() {
            if (this.currentIndex < this.slideCount - 1) {
                this.currentIndex++
                this.translateX -= this.slideWidth
            }
        },
    },
}
</script>

<style>
.carousel {
    position: relative;
    overflow: hidden;
    container-type: inline-size;    
}

.slides {
    display: flex;
    transition: transform 0.3s ease;
}

.slide {
    flex: 0 0 auto;
    width: 100%;
}

.content {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 200px; /* Adjust height as needed */
    background-color: #ccc;
}

.prev,
.next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: #333;
    color: #fff;
    border: none;
    padding: 10px;
    cursor: pointer;
}

.prev {
    left: 0;
}

.next {
    right: 0;
}

@container (min-width: 640px) {
    .slide {
        width: var(--var-sizes-sm);
    }
}

@container (min-width: 768px) {
    .slide {
        width: var(--var-sizes-md);
    }
}

@container (min-width: 1024px) {
    .slide {
        width: var(--var-sizes-lg);
    }
}

@container (min-width: 1280px) {
    .slide {
        width: var(--var-sizes-xl);
    }
}

@container (min-width: 1536px) {
    .slide {
        width: var(--var-sizes-2xl);
    }
}
</style>
