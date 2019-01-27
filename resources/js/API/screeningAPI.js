import {$httpRequester} from './httpRequester';

export const get = (caseId, params = {}) => {
  return $httpRequester.get(`api/job-seekers/${caseId}/screening`)
}
