import React from 'react';
import Breadcrumb from './breadcrumb';
import axios from 'axios';

import { Navigate } from 'react-router-dom';
import { Button, Space, message } from 'antd';

class Home extends React.Component {
    constructor(props, ctx) {
        super(props, ctx);
    }

    componentDidMount() {
        this._requestLocation();
    }

    _requestWeather = (position = {}) => {
        const { coords = {} } = position;
        axios.get('/api/weather', {
            params: {
                lat: coords.latitude,
                lng: coords.longitude
            }
        });
    }

    _requestLocation() {
        let location = {};
        if (navigator && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(this._requestWeather);
        }
    }

    _logout = () => {
        const hide = message.loading('Logging out...');
        axios.get('/api/logout')
            .then(() => {
                hide();
                this.props.updateUserData();
            });
    }

    renderHome() {
        const { user } = this.props;
        return (
            <>
                <div>
                    <Space>
                        <span>Hello, {user.name}</span>
                        <span>({user.email})</span>
                        <Button onClick={this._logout}>Log Out</Button>
                    </Space>
                </div>
                <hr/>
            </>
        );
    }

    render() {
        const { user } = this.props;
        return(
            <>
                <Breadcrumb items={[['/', 'home']]} />
                {user && !user.authenticated && <Navigate to="/" replace />}
                {user && user.authenticated && this.renderHome()}
            </>
        );
    }
}

export default Home;
