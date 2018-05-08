import React from 'react';
import { Link } from 'react-router-dom'

export default class TransactionList extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            transactions: {},
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
        fetch('/api/v1/transaction/list/' + id)
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState({isLoaded: true, transactions: result.transactions});
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
            const transactions = this.state.transactions;
            return (
                <ul className="transaction-list">
                    {transactions.map((transaction) =>
                        <li>
                            <span className="title">{transaction.title}</span>
                            <span className="amount">{transaction.amount}</span>
                            <span className="type">{transaction.type}</span>
                        </li>
                    )}
                </ul>
            )
        }
    }
}
