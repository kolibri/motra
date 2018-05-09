import React from 'react';

export default class DateView extends React.Component {

    render() {
        let date = new Date(this.props.date);
        return (
            <span className="date">
                {date.getDay()}.{date.getMonth()}.{date.getFullYear()} &nbsp;
                {date.getHours()}:{date.getMinutes()}:{date.getSeconds()}
            </span>
        )
    }
}

