import React from 'react';
import parseHydraDocumentation from '@api-platform/api-doc-parser/lib/hydra/parseHydraDocumentation';
import { HydraAdmin, hydraClient, fetchHydra as baseFetchHydra } from '@api-platform/admin';
import authProvider from './authProvider';
import { Route, Redirect } from 'react-router-dom';

import { Route, Redirect } from 'react-router-dom';

const entrypoint = process.env.REACT_APP_API_ENTRYPOINT; // Change this by your own entrypoint if you're not using API Platform distribution
const fetchHeaders = {'Authorization': `Bearer ${localStorage.getItem('token')}`};
const fetchHydra = (url, options = {}) => baseFetchHydra(url, {
    ...options,
    headers: new Headers(fetchHeaders),
});
const dataProvider = api => hydraClient(api, fetchHydra);
const apiDocumentationParser = entrypoint =>
  parseHydraDocumentation(entrypoint, {
    headers: new Headers(fetchHeaders),
  }).then(
    ({ api }) => ({ api }),
    result => {
      const { api, status } = result;

      if (status === 401) {
        return Promise.resolve({
          api,
          status,
          customRoutes: [
            <Route path="/" render={() => <Redirect to="/login" />} />,
          ],
        });
      }

      return Promise.reject(result);
    }
  );

export default () => (
    <HydraAdmin
        apiDocumentationParser={apiDocumentationParser}
        authProvider={authProvider}
        entrypoint={entrypoint}
        dataProvider={dataProvider}
    />
);


// export default () => <HydraAdmin entrypoint={process.env.REACT_APP_API_ENTRYPOINT}/>;

