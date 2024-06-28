const { apiClient, alert } = window.ProcessMaker;

export default {};

export const getImages = async (processId) => {
  const response = await apiClient.get(`/slideshow/images/${processId}`);

  return response;
};

export const getScreen = async (processId) => {
  const response = await apiClient.get(`screens/${processId}`);

  return response;
};
