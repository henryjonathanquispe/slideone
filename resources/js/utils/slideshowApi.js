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

export const sendEmail = async (emails) => {
  const response = await apiClient.post(`/slideshow/send/${modeler.process.id}`, {
    list_emails: emails,
  });

  return response;
};

export const run = async ({
  initialNode,
  stopNode,
  alternative,
}) => {
  const response = await window.ProcessMaker.apiClient.post('/package-testing/run', {
    demo_mode: 1,
    scenario: null,
    starting_step: initialNode,
    process_id: modeler.process.id,
    data: '[]',
    initial_data: '{}',
    stop_step: stopNode,
    save_test: 1,
    auto_submit: 1,
    bypass_script_connector: 1,
    alternative,
  });

  return response;
};

export const completeTask = async (runId, tokenId) => {
  const response = await window.ProcessMaker.apiClient.post(`/package-testing/runs/${runId}/tokens/${tokenId}/complete`, {
    data: {},
  });

  return response;
};

export const selectRoute = async (runId, gatewayId, flowId) => {
  const response = window.ProcessMaker.apiClient.post(`/package-testing/runs/${runId}/select-route`, {
    gateway_id: gatewayId,
    flow_id: flowId,
  });

  return response;
};
