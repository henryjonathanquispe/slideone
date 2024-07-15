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

      <div v-if="getInfoAB()" class="pb-4">
        <span class="text-sm">{{ $t('Alternatives to see in slideshow mode') }}</span>

        <select
          class="form-control rounded-sm"
          id="selectAlternatives"
          v-model="alternative">
          <template v-for="item in alternatives">
            <option :key="item.value" :value="item.value">
              {{ $t(item.label) }}
            </option>
          </template>
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
            v-model="showEmails"
            type="checkbox"
            class="custom-control-input"
            id="customSwitch1">
          <label class="custom-control-label" for="customSwitch1" />
        </div>
      </div>

      <div v-if="showEmails" class="pb-3">
        <label for="email" class="block text-sm">{{ $t("Send the link via Email") }}</label>
        <TextChips
          :disable-button="emails.length == 0"
          :regexp="regexp"
          class="!text-sm"
          :placeholder="$t('Type an email here')"
          :click-button="sendLink"
          :label-button="$t('SEND LINK')"
          :data="emails" />
      </div>
      <div>
        <button
          type="button"
          :disabled="loading"
          class="flex justify-center float-right text-sm rounded-l rounded-r
          bg-blue-500 w-28 py-2 font-semibold
            text-white shadow-sm hover:bg-blue-400
            focus-visible:outline focus-visible:outline-2
            focus-visible:outline-offset-2 focus-visible:outline-blue-600"
          :class="[{
            'hover:opacity-75': true,
            'hover:cursor-default': loading
          }]"
          @click="onChange">
          <Spinner v-if="loading" class="animate-spin h-5 w-5 text-white" />
          <span v-else>{{ $t('Save') }} </span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue';
// eslint-disable-next-line import/no-unresolved
import { t } from 'i18n';
import { ICopy, IClose } from '../icons';
import TextChips from '../sharedComponents/TextChips.vue';
import {
  getInfoAB, postSettings, getSettings, sendEmail, listAlternatives, alert,
} from '../utils/slideshowApi';
import Spinner from '../sharedComponents/Spinner.vue';

const emailRegex = '^[a-zA-Z0-9.!#$%&\'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:.[a-zA-Z0-9-]+)*$';

export default defineComponent({
  components: {
    ICopy,
    IClose,
    TextChips,
    Spinner,
  },
  emits: ['next', 'close'],
  setup(props, context) {
    const { emit } = context;
    const loading = ref(false);
    const emails = ref([]);
    const showEmails = ref(true);
    const alternative = ref('B');
    const href = ref(`/slideshow/${window.ProcessMaker.modeler.process.id}`);
    const alternatives = ref([]);

    const regexp = [{
      regexp: emailRegex,
      message: t('Email required'),
    }];

    const onClick = () => {
      emit('next');
    };

    const getProcessVersion = () => alternatives.value
      .find((item) => item.value === alternative.value).process_version;

    const onChange = async () => {
      const alternativeID = getProcessVersion();
      let processVersions = {
        processVersionA: '',
        processVersionb: '',
      };

      loading.value = true;

      switch (alternative.value) {
        case 'A':
          processVersions.processVersionA = alternativeID;
          processVersions.processVersionB = '';
          break;
        case 'B':
          processVersions.processVersionB = alternativeID;
          processVersions.processVersionA = '';
          break;
        case 'AB':
          processVersions = JSON.parse(alternativeID);
          break;
        default:
          break;
      }

      const response = await postSettings(
        alternative.value,
        processVersions,
        showEmails.value ? 1 : 0,
      );

      loading.value = false;

      if (response.data) {
        alert(t('Data saved successfully'), 'success');
      }
    };

    const onClose = () => emit('close');

    const copyLink = async () => {
      const link = `${window.location.origin}${href.value}`;

      await navigator.clipboard.writeText(link);

      window.ProcessMaker.alert(t('Link copied to clipboard'), 'success');
    };

    const sendLink = async () => {
      try {
        if (emails.value.length) {
          const response = await sendEmail(emails.value);

          if (response.status !== 'error') {
            window.ProcessMaker.alert(t(`Invitation sent successfully to ${emails.value.length} persons`), 'success');
          }

          return response;
        }
      } catch (e) {
        window.ProcessMaker.alert(e.message, 'danger');

        return null;
      }
    };

    const updateLocalSettings = (settings) => {
      showEmails.value = settings.is_enable == 1;
      alternative.value = settings.published_version || 'A';
    };

    const setAlternatives = (items) => {
      items.forEach((item) => {
        alternatives.value.push({
          value: item.published_version,
          label: `Alternative ${item.published_version}`,
          process_version: item.process_version,
        });
      });
      if (alternatives.value.length > 1) {
        alternatives.value.push({
          value: 'AB',
          label: 'Alternative A + Alternative B',
          process_version: `{"processVersionA":${items[0].process_version}, "processVersionB":${items[1].process_version}}`,
        });
      }
    };

    const updateSettings = async () => {
      const response = await getSettings();
      const alternativesData = await listAlternatives();

      setAlternatives(alternativesData.data);

      response.data && updateLocalSettings(response.data);
    };

    onMounted(() => {
      updateSettings();
    });

    return {
      emails,
      showEmails,
      href,
      onClick,
      copyLink,
      sendLink,
      onClose,
      regexp,
      getInfoAB,
      alternative,
      onChange,
      alternatives,
      loading,
    };
  },
});
</script>

<style scoped>
div.bg-light {
  background-color: #f2f8fe !important;
}
</style>
