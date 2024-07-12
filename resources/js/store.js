// Module to add Vuex
import { get } from 'lodash';

// Store module vuex - slideshow
export default {
  namespaced: true,
  state: () => ({
    images: [],
  }),
  mutations: {
    addImage: (state, image) => {
      state.images.push(image);
    },
    addImages: (state, images) => {
      state.images = state.images.concat(images);
    },
    setImages: (state, images) => {
      state.images = images;
    },
  },
  getters: {
    getImages(state) {
      return state.images;
    },
    getImage: (state) => (prop, value) => state.images.find((e) => get(e, prop) === value),
    getImageByProps: (state) => (data) => state.images.find((image) => Object.entries(data).every(([key, value]) => get(image, key) === value)),
  },
};
