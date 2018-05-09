import React from 'react';
import {Link} from 'react-router-dom'

export default class MoneyAmount extends React.Component {

    render() {
        const amount = new Intl.NumberFormat('de-DE', {
            style: 'currency',
            currency: 'EUR'
        }).format(this.props.amount / 100);

        return (
            <span className="amount">
                <span className="total">{amount}</span>
            </span>
        )
    }
}
