<template>
  <div class="d-flex flex-column align-content-between">
    <div class="modal-header py-2 px-3 d-flex align-items-center bg-light">
      <div class="d-flex align-items-center space-x-2">
        <div class="lh-1">
          <span>{{ $t('Share this file') }}</span>
        </div>
      </div>

      <div class="d-flex align-items-center">
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
          <button @click="onClose" type="button" class="btn">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              fill="currentColor"
              class="bi bi-x"
              viewBox="0 0 16 16"
            >
              <path
                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>
    <div class="modal-body h-auto">
      <div class="d-flex justify-content-between pb-2">
        <h5 class="mb-0 font-weight-bold">
          {{ $t('Share SlideShow "Contact Us"') }}
        </h5>
        <button
          @click="onClick"
          type="button"
          class="text-capitalize btn btn-sm d-flex align-items-center space-x-3 text-muted btn-outline-gray rounded-lg shadow-sm"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="16"
            height="16"
            fill="currentColor"
            class="bi bi-copy"
            viewBox="0 0 16 16"
          >
            <path
              fill-rule="evenodd"
              d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z"
            />
          </svg>
          <span> {{ $t('Advanced Settings') }} </span>
        </button>
      </div>
      <div class="form-group pb-2">
        <label class="d-flex align-items-center justify-content-between">
          {{ $t('Choose how would you like to share this file') }}
        </label>
      </div>

      <div class="form-group pb-2 text-left">
        <label for="userEmail">{{ $t('User Email') }}</label>
        <input
          type="email"
          class="form-control"
          id="userEmail"
          aria-describedby="emailHelp"
          @keypress.enter="addEmail"
          :placeholder="$t('Add people')"
        />
      </div>

      <h5 class="mb-0 text-left font-weight-bold">
        {{ $t('People with access') }}
      </h5>

      <b-list-group
        ref="contactsContainer"
        class="text-left"
        :style="{
          'overflow-y': contacts.length > maxContacts ? 'auto' : 'hidden',
          height: contactsHeigth,
        }"
      >
        <b-list-group-item v-for="(item, index) in contacts" :key="index">
          <div class="d-flex justify-content-between">
            <span>{{ item }}</span>

            <span role="button" @click="removeContact(index)">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                fill="currentColor"
                class="bi bi-x"
                viewBox="0 0 16 16"
              >
                <path
                  d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"
                /></svg
            ></span>
          </div>
        </b-list-group-item>
      </b-list-group>
    </div>

    <div class="modal-footer d-flex justify-content-between align-items-center">
      <button
        type="button"
        class="text-capitalize btn d-flex align-items-center space-x-3 text-muted btn-outline-gray rounded-lg shadow-sm"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          fill="currentColor"
          class="bi bi-copy"
          viewBox="0 0 16 16"
        >
          <path
            fill-rule="evenodd"
            d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z"
          />
        </svg>
        <span>{{ $t('Copy link') }} </span>
      </button>

      <button type="button" class="btn bg-primary text-white px-5">
        {{ $t('Done') }}
      </button>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue';

export default defineComponent({
  emits: ['next', 'close'],
  setup(props, { emit }) {
    const contacts = ref([]);
    const maxContacts = ref(3);
    const contactsHeigth = ref('auto');
    const contactsContainer = ref();

    const onClick = () => {
      emit('next');
    };

    const onClose = () => {
      emit('close');
    };

    const addEmail = (ev) => {
      contacts.value.push(ev.target.value);

      ev.target.value = '';

      updateContactsHeight();
    };

    const removeContact = (index) => {
      contacts.value.splice(index, 1);

      updateContactsHeight();
    };

    const updateContactsHeight = () => {
      contactsHeigth.value =
        contacts.value.length > maxContacts.value
          ? `${contactsContainer.value.offsetHeight}px`
          : 'auto';
    };

    return {
      onClick,
      onClose,
      addEmail,
      contacts,
      maxContacts,
      removeContact,
      contactsContainer,
      contactsHeigth,
    };
  },
});
</script>

<style scoped>
div.bg-light {
  background-color: #f2f8fe !important;
}
</style>
