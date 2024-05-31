<script>
import { defineComponent } from "vue"

export default defineComponent({
    emits: ["next", "close"],
    setup(props, { emit }) {
        const onClick = () => {
            emit("next")
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
                <div class="lh-1">
                    <span>Share this file</span>
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
        <div class="modal-body h-100">
            <button
                @click="onClick"
                type="button"
                class="btn d-flex align-items-center space-x-3 text-muted btn-outline-gray rounded-lg shadow-sm">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-copy"
                    viewBox="0 0 16 16">
                    <path
                        fill-rule="evenodd"
                        d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z" />
                </svg>
                <span> Advanced Settings </span>
            </button>
        </div>

        <div
            class="modal-footer d-flex justify-content-between align-items-center">
            <button
                type="button"
                class="btn d-flex align-items-center space-x-3 text-muted btn-outline-gray rounded-lg shadow-sm">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-copy"
                    viewBox="0 0 16 16">
                    <path
                        fill-rule="evenodd"
                        d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z" />
                </svg>
                <span> Copy link </span>
            </button>

            <button
                type="button"
                class="btn bg-primary text-white px-5">
                Done
            </button>
        </div>
    </div>
</template>

<style></style>
