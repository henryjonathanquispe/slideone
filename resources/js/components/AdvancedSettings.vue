<script>
import { defineComponent } from "vue"

export default defineComponent({
    emits: ["back", "close"],
    setup(props, { emit }) {
        const onClick = () => {
            emit("back")
        }

        const onClose = () => {
            emit("close")
        }

        return {
            onClick,
            onClose,
        }
    },
})
</script>

<template>
    <div class="d-flex flex-column">
        <div class="modal-header d-flex align-items-center">
            <div class="d-flex align-items-center space-x-2">
                <button
                    @click="onClick"
                    type="button"
                    class="btn">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        class="bi bi-chevron-compact-left"
                        viewBox="0 0 16 16">
                        <path
                            fill-rule="evenodd"
                            d="M9.224 1.553a.5.5 0 0 1 .223.67L6.56 8l2.888 5.776a.5.5 0 1 1-.894.448l-3-6a.5.5 0 0 1 0-.448l3-6a.5.5 0 0 1 .67-.223" />
                    </svg>
                </button>

                <div class="lh-1">
                    <span>Advanced configuration</span>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <button
                        @click="onClose"
                        type="button"
                        class="btn">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            fill="currentColor"
                            class="bi bi-x"
                            viewBox="0 0 16 16">
                            <path
                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="modal-body h-100"></div>

        <div
            class="modal-footer d-flex justify-content-between align-items-center">
            <button
                @click="onClick"
                type="button"
                class="btn bg-primary text-white px-5">
                Save & Back to share settings
            </button>
        </div>
    </div>
</template>

<style></style>
