<script>
import {
    ref,
    onMounted,
    onUnmounted,
    computed,
    defineComponent,
    shallowRef,
} from "vue"
import * as bootstrap from "bootstrap"
import Settings from "./Settings.vue"
import AdvancedSettings from "./AdvancedSettings.vue"

export default defineComponent({
    setup() {
        const modal = ref()
        const content = ref()
        const containercarousel = ref()
        const slides = ref([])

        const show = () => modal.value.show()

        const hide = () => modal.value.hide()

        const views = ref([
            {
                component: shallowRef(Settings),
                id: "Settings",
                active: true,
            },
            {
                component: shallowRef(AdvancedSettings),
                id: "AdvancedSettings",
                active: false,
            },
        ])

        const currentIndex = ref(0)
        const slideWidth = ref(0)
        const translateX = ref(0)
        const resizeObserver = ref()

        const sizes = {
            sm: 1,
            md: 2,
            lg: 3,
            xl: 4,
            "2xl": 5,
        }

        const prevSlide = () => {
            slideWidth.value = content.value.offsetWidth
            if (currentIndex.value > 0) {
                currentIndex.value--
                translateX.value += slideWidth.value
            }
        }

        const nextSlide = () => {
            slideWidth.value = content.value.offsetWidth

            if (currentIndex.value < views.value.length - 1) {
                translateX.value = translateX.value - slideWidth.value

                currentIndex.value++
            }
        }

        onMounted(() => {
            slideWidth.value = slides.value[0].$el.offsetWidth
            modal.value = new bootstrap.Modal(
                document.getElementById("modal"),
                {
                    backdrop: true,
                }
            )
        })

        onUnmounted(() => {})

        return {
            nextSlide,
            prevSlide,
            sizes,
            translateX,
            slideWidth,
            currentIndex,
            slides,
            containercarousel,
            show,
            hide,
            views,
            content,
        }
    },
})
</script>

<template>
    <div
        class="modal fade"
        id="modal"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div
            class="modal-dialog"
            role="document">
            <div
                class="modal-content"
                ref="content">
                <div
                    ref="containercarousel"
                    class="carousel"
                    :style="{
                        '--var-sizes-sm': `${100 / sizes.sm}%`,
                        '--var-sizes-md': `${100 / sizes.md}%`,
                        '--var-sizes-lg': `${100 / sizes.lg}%`,
                        '--var-sizes-xl': `${100 / sizes.xl}%`,
                        '--var-sizes-2xl': `${100 / sizes['2xl']}%`,
                    }">
                    <div
                        class="slides-show"
                        :style="{
                            transform: 'translateX(' + translateX + 'px)',
                        }">
                        <template v-for="(item, index) in views">
                            <component
                                @next="nextSlide"
                                @back="prevSlide"
                                @close="hide"
                                class="slide"
                                ref="slides"
                                :is="item.component"
                                :key="index" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.carousel {
    position: relative;
    overflow: hidden;
    container-type: inline-size;
}

.slides-show {
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
    background-color: seagreen;
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
