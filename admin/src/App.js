// admin/src/App.js

import React from 'react';
import { HydraAdmin, ResourceGuesser } from '@api-platform/admin';
import parseHydraDocumentation from '@api-platform/api-doc-parser/lib/hydra/parseHydraDocumentation';
import { dataProvider as baseDataProvider, fetchHydra as baseFetchHydra  } from '@api-platform/admin';
import authProvider from './authProvider';
import { Redirect } from 'react-router-dom';

const entrypoint = process.env.REACT_APP_API_ENTRYPOINT;
const fetchHeaders = {'Authorization': `Bearer ${window.localStorage.getItem('token')}`};
const fetchHydra = (url, options = {}) => baseFetchHydra(url, {
    ...options,
    headers: new Headers(fetchHeaders),
});
const apiDocumentationParser = entrypoint => parseHydraDocumentation(entrypoint, { headers: new Headers(fetchHeaders) })
    .then(
        ({ api }) => ({api}),
        (result) => {
            switch (result.status) {
                case 401:
                    return Promise.resolve({
                        api: result.api,
                        customRoutes: [{
                            props: {
                                path: '/',
                                render: () => <Redirect to={`/login`}/>,
                            },
                        }],
                    });

                default:
                    return Promise.reject(result);
            }
        },
    );
const dataProvider = baseDataProvider(entrypoint, fetchHydra, apiDocumentationParser);

export default props => (
    <HydraAdmin
        apiDocumentationParser={ apiDocumentationParser }
        dataProvider={ dataProvider }
        authProvider={ authProvider }
        entrypoint={ entrypoint }
    >
      <ResourceGuesser name="clients" />
      <ResourceGuesser name="client_users" />
      <ResourceGuesser name="phones" />
      <ResourceGuesser name="phone_has_features" />
      <ResourceGuesser name="phone_images" />
      <ResourceGuesser name="phone_features" />
    </HydraAdmin>
);
