const { apiClient, alert, modeler } = window.ProcessMaker;

export default {};

export const getImages = async (processId) => {
  const response = await apiClient.get(`/slideshow/images/${processId}`);

  return response;
};

export const getScreen = async (processId) => {
  const response = await apiClient.get(`screens/${processId}`);

  return response;
};

export const getInfoAB = () => modeler.process.alternative_info;

export const postSettings = async (alternative, isEnable) => {
  const response = await apiClient.post(`/slideshow/process/${modeler.process.id}/settings`, {
    published_version: alternative,
    is_enable: isEnable,
  });

  return response;
};

export const getSettings = async () => {
  const response = await apiClient.get(`/slideshow/process/${modeler.process.id}/settings`);

  return response;
};
