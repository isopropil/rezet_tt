import React from 'react';
import { message } from 'antd';
import map from 'lodash/map';

function errorHandler(error, callback) {
    const { response = {} } = error;
    const { data, status } = response;
    if (status === 422) {
        const { errors } = data;
        message.error(
            <>
                <b>{data.message}</b><br/>
                {map(data.errors, (error) => error.join(', '))}
            </>
        );
    } else if (typeof error === 'string') {
        message.error(error);
    } else {
        message.error('Unknown error');
    }
    callback(status, data);
}

export default errorHandler;
