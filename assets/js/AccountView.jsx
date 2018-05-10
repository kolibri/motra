import React from 'react';
import CurrentAccount from './CurrentAccount.jsx';
import AddTransaction from './AddTransaction.jsx';
import TransactionList from './TransactionList.jsx';

export default class AccountView extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            account: {},
            transactions: [],
            isLoaded: false,
            error: null
        }

        this.handleChange = this.handleChange.bind(this)
    }

    componentDidMount() {
        const {match: {params}} = this.props;
        this.handleChange(params.id);
    }

    handleChange(id) {
        fetch('/api/v1/account/' + id, {
            headers: {
                Authorization: 'Basic ' + btoa("torben@tester.foo:tester")
            }
        })
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState({isLoaded: true, account: result.account, transactions: result.transactions});
                },
                (error) => {
                    this.setState({isLoaded: true, error});
                }
            )
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

    render() {
        if (this.state.error) {
            return <div>Error loading </div>;
        } else if (!this.state.isLoaded) {
            return <div>Loading ...</div>
        } else {
            return (
                <div>
                    <CurrentAccount account={this.state.account}/>
                    <AddTransaction account={this.state.account}
                                    handleSubmit={this.handleChange}/>
                    <TransactionList transactions={this.state.transactions}/>
                </div>
            )
        }
    }
}
