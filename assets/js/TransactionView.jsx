import React from 'react';
import {Link} from 'react-router-dom'
import DateView from './DateView.jsx';
import MoneyAmount from './MoneyAmount.jsx';

export default class TransactionView extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            title: '',
            id: '',
            amount: '',
            type: '',
            accountId: '',
            createdAt: '',
            isLoaded: false,
            error: null
        }
    }

    componentDidMount() {
        this.handleChange(this.props.match.params.id);
    }

    shouldComponentUpdate(nextProps, nextState) {
        if (this.state.isLoaded !== nextState.isLoaded) {
            return true;
        }

        if (nextProps.match.params.id != nextState.id) {
            this.setState({isLoaded: false});
            this.handleChange(nextProps.match.params.id);
            return true;
        }

        return false;
    }

    handleChange(id) {
        fetch('/api/v1/transaction/' + id)
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState({
                        isLoaded: true,
                        title: result.title,
                        id: result.id,
                        amount: result.amount,
                        type: result.type,
                        account: result.account,
                        createdAt: result.created_at
                    });
                },
                (error) => {
                    this.setState({isLoaded: true, error});
                }
            )
    }

    render() {
        if (this.state.error) {
            return <div>Error loading </div>;
        } else if (!this.state.isLoaded) {
            return <div>Loading ...</div>
        } else {
            return (
                <div className="transaction-view">
                    <span className="title">{this.state.title}</span>
                    <MoneyAmount amount={this.state.amount}/>
                    <span className="type">{this.state.type}</span>
                    <span className="account">{this.state.account.name}</span>
                    <DateView date={this.state.createdAt} />
                </div>
            )
        }
    }
}
