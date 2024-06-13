<template>
  <div class="d-flex flex-column align-content-between text-gray-500">
    <div class="modal-header p-3 d-flex align-items-center bg-light">
      <div class="d-flex align-items-center space-x-2">
        <span class="text-xl font-semibold">{{ $t('Slideshow Settings') }}</span>
      </div>

      <div class="d-flex align-items-center">
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
          <span @click="onClose">
            <IClose class="w-4 h-4 hover:opacity-75 hover:cursor-pointer" />
          </span>
        </div>
      </div>
    </div>
    <div class="modal-body">
      <div class="pb-2 font-semibold">
        {{ $t('Slideshow Mode') }}
      </div>

      <div class="pb-4 text-sm">
        {{ $t('Slideshow Mode is a user friendly demo to see the flow of the process.') }}
      </div>

      <div class="pb-3 text-sm space-x-3 flex">
        <a
          target="_blank"
          :href="href"
          class="text-[#1572C2] hover:opacity-70 hover:cursor-pointer">
          {{ $t('Open Slideshow Mode') }}
        </a>

        <span
          @click="copyLink"
          class="text-[#1572C2] hover:opacity-70 hover:cursor-pointer flex">
          <ICopy />
          <span> {{ $t('Copy Link') }} </span>
        </span>
      </div>

      <div class="pb-4">
        <span class="text-sm">{{ $t('Alternatives to see in slideshow mode') }}</span>

        <select class="form-control rounded-sm" id="selectAlternatives">
          <option>{{ $t('Alternative A') }}</option>
          <option>{{ $t('Alternative B') }}</option>
          <option>{{ $t('Alternative A + Alternative B') }}</option>
        </select>
      </div>

      <div class="relative">
        <div class="absolute inset-0 flex items-center" aria-hidden="true">
          <div class="w-full border-t border-gray-300" />
        </div>
      </div>

      <div class="flex py-3 items-center">
        <div>
          <span class="font-semibold pb-2 block">
            {{ $t('Enable Slideshow sharing') }}
          </span>

          <span class="text-sm block">
            {{ $t('Enable this options to let anyone with the link view the slideshow, even without a ProcessMaker account.') }}
          </span>
        </div>

        <div class="px-4 custom-control custom-switch">
          <input
            type="checkbox"
            class="custom-control-input"
            id="customSwitch1"
            @change="toogleEmails">
          <label class="custom-control-label" for="customSwitch1" />
        </div>
      </div>

      <div v-if="showEmails" class="pb-3">
        <label for="email" class="block text-sm">{{ $t("Send the link via Email") }}</label>
        <TextChips
          :disable-button="emails.length == 0"
          class="!text-sm"
          @onClick="sendLink"
          :label-button="$t('SEND LINK')"
          :data="emails" />
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
// eslint-disable-next-line import/no-unresolved
import { t } from 'i18n';
import { ICopy, IClose } from '../icons';
import TextChips from '../sharedComponents/TextChips.vue';

export default defineComponent({
  components: {
    ICopy,
    IClose,
    TextChips,
  },
  emits: ['next', 'close'],
  setup(props, context) {
    const { emit } = context;
    const emails = ref([]);
    const showEmails = ref(false);
    const href = ref(`/slideshow/main/${window.ProcessMaker.modeler.process.id}`);

    const onClick = () => {
      emit('next');
    };

    const toogleEmails = () => {
      showEmails.value = !showEmails.value;
    };

    const onClose = () => emit('close');

    const copyLink = () => {
      window.ProcessMaker.alert(t('Link copied to clipboard'), 'success');
    };

    const sendLink = () => {
      if (emails.value.length) {
        window.ProcessMaker.alert(t(`Invitation sent successfully to ${emails.value.length} persons`), 'success');
      }
    };

    return {
      emails,
      showEmails,
      href,
      onClick,
      copyLink,
      sendLink,
      onClose,
      toogleEmails,
    };
  },
});
</script>

<style scoped>
div.bg-light {
  background-color: #f2f8fe !important;
}
</style>
