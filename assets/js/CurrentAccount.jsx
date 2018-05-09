import React from 'react';
import MoneyAmount from './MoneyAmount.jsx';

export default class CurrentAccount extends React.Component {
    render() {
        return (
            <div className="current-account">
                <span className="name">{this.props.account.name}</span>
                <MoneyAmount amount={this.props.account.total}/>
            </div>
        )
    }
}
