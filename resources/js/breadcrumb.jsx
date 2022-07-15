import React from 'react';
import { Breadcrumb } from 'antd';
import { Link } from 'react-router-dom';
import map from 'lodash/map';

class Bread extends React.Component {
    constructor(props, ctx) {
        super(props, ctx);
    }

    render() {
        return(
            <Breadcrumb
                style={{
                    margin: '16px 0',
                }}
            >
                {map(this.props.items, (item, key) =>
                    <Breadcrumb.Item key={key}><Link to={item[0]}>{item[1]}</Link></Breadcrumb.Item>
                )}
            </Breadcrumb>
        );
    }
}

export default Bread;
