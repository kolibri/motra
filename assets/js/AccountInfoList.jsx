import React from 'react';
import { Link } from 'react-router-dom'

import MoneyAmount from './MoneyAmount.jsx';

export default class AccountInfoList extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            accounts: {},
            isLoaded: false,
            error: null
        }
    }

    componentDidMount() {
        fetch('/api/v1/account')
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState({isLoaded: true, accounts: result.accounts});
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
            const accounts = this.state.accounts;
            return (
                <div className="account-list">
                    {accounts.map((account) =>
                        <Link to={{ pathname: "/account/view/" + account.id }} key={account.name}>
                            <span className="name">{account.name}</span>
                            <MoneyAmount amount={account.total} />
                        </Link>)}

                    <Link to={{ pathname: "/account/add"}}>Add Account</Link>
                </div>
            )
        }
    }
}
