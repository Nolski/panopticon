import {$httpRequester} from './httpRequester';
import queryString from "../helpers/QueryString";

export const get = (caseType, caseId, params = {}) => {
  return $httpRequester.get(`api/case-notes/${caseType}/${caseId}?${queryString.serialize(params)}`)
}


export const post = (caseType, caseId, params = {}) => {
  return $httpRequester.post(`api/case-notes/${caseType}/${caseId}`, params)
}



export const setNoteStarred = (caseType, caseId,noteId, params = {}) => {
  return $httpRequester.post(`api/case-notes/${caseType}/${caseId}/${noteId}/star`, params)
}







