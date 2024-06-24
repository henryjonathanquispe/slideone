const { apiClient, alert } = window.ProcessMaker;

export default {};

export const getImages = async (processId) => {
  const response = await apiClient.get(`/api/package/slideshow/images/${processId}`);

  if (response.errors) {
    // Alert Code
  }

  return response;
};

export const getScreen = async (processId) => {
  const response = await apiClient.get(`screens/${processId}`);

  if (response.errors) {
    // Alert Code
  }

  return response;
};
