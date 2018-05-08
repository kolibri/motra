import React from 'react';
import { Link } from 'react-router-dom'

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
                            <span className="total">{account.total} €</span>
                        </Link>)}

                    <Link to={{ pathname: "/account/add"}}>Add Account</Link>
                </div>
            )
        }
    }
}
