import {$httpRequester} from './httpRequester';
import queryString from "../helpers/QueryString";

export const upcomingFollowups = (date, page, params = {}) => {
  if (date) {
      return $httpRequester.get(`api/upcoming-followups?followup_date=${date}&page=${page}&perPage=15`, {});
  }
  else {
      return $httpRequester.get(`api/upcoming-followups?page=${page}&perPage=15`, {});

  }
};


export const upcomingFollowupsCount = (date, params = {}) => {
    return $httpRequester.get(`api/upcoming-followups/counts?followup_date=${date}`);
};


export const exportData = (date, page, params = {}) => {
    if (date) {
        return $httpRequester.get(`api/upcoming-followups?followup_date=${date}&page=${page}&perPage=15&${queryString.serialize(params)}`, {
            responseType: 'arraybuffer',
        });
    }
    else {
        return $httpRequester.get(`api/upcoming-followups?page=${page}&perPage=15&${queryString.serialize(params)}`, {
            responseType: 'arraybuffer',
        });

    }

};
