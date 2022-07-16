import React from 'react';
import Breadcrumb from './breadcrumb';
import axios from 'axios';

import { Navigate } from 'react-router-dom';
import { Button, Space, message, Spin } from 'antd';

class Home extends React.Component {
    constructor(props, ctx) {
        super(props, ctx);
        this.state = {
            status: null,
            weatherData: null
        }
    }

    componentDidMount() {
        this._requestLocation();
    }

    _requestWeather = (position = {}) => {
        const { coords = {} } = position;
        this.setState({ status: 'requesting' });
        axios.get('/api/weather', {
            params: {
                lat: coords.latitude,
                lng: coords.longitude
            }
        }).then(response => {
            const { data } = response;
            if (data.status === 'ok') {
                this.setState({ status: 'ok', weatherData: data.payload });
            } else {
                this.setState({ status: 'error', message: data.payload });
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
        const { status, weatherData } = this.state;
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
                {status === null && 'Coordinates needed for request weather data'}
                {status === 'requesting' && <Spin size="large" /> }
                {status === 'error' && <span>Error: {this.state.message}</span> }
                {status === 'ok' &&
                    <>
                        <b>Weather in your region:</b><br/>
                        Temperature: {weatherData.temp}<br/>
                        Pressure: {weatherData.pressure}<br/>
                        Humidity: {weatherData.humidity}<br/>
                        Min Temp: {weatherData.temp_min}<br/>
                        Max Temp: {weatherData.temp_max}<br/>
                    </>
                }
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
