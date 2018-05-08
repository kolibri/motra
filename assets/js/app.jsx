require('../sass/app.scss');
import React from 'react';
import ReactDOM from 'react-dom';
import {
    BrowserRouter,
    Route,
    Link
} from 'react-router-dom'

class App extends React.Component {
    render() {
        return (
            <div>
                <Route path="/" component={AccountInfoList}/>
                <Route path="/account/:id" component={CurrentAccount}/>
                <Route path="/account/:id" component={TransactionBox}/>
                <Route path="/account/:id" component={TransactionList}/>
            </div>
        );
    }
}

class AccountInfoList extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            accounts: {},
            isLoaded: false,
            error: null
        }
    }

    componentDidMount() {
        fetch('/api/v1/account/list')
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
                        <Link to={{ pathname: "/account/" + account.id }} key={account.name}>
                            <span className="name">{account.name}</span>
                            <span className="total">{account.total} €</span>
                        </Link>)}
                </div>
            )
        }
    }
}

class CurrentAccount extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            id: null,
            name: '',
            total: 0,
            isLoaded: false,
            error: null
        }
    }

    componentDidMount() {
        const {match: {params}} = this.props;
        this.handleChange(params.id);
    }

    handleChange(id) {
        fetch('/api/v1/account/view/' + id)
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState({isLoaded: true, id: result.id, name: result.name, total: result.total});
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
                <div className="current-account">
                    <span className="name">{this.state.name}</span>
                    <span className="total">{this.state.total} €</span>
                </div>
            )
        }
    }
}

class TransactionBox extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            amount: 42,
            title: 'foo'
        }

        this.handleInputChange = this.handleInputChange.bind(this);
        this.handleSpend = this.handleSpend.bind(this);
    }

    render() {
        return (
            <form onSubmit={this.handleSpend} className="transaction-box">
                <input type="number" placeholder="Amount" name="amount" value={this.state.amount}
                       onChange={this.handleInputChange}/>
                <input type="text" placeholder="Title" name="title" value={this.state.title}
                       onChange={this.handleInputChange}/>
                <button type="submit" value="spend">Spend</button>
            </form>
        );
    }

    handleInputChange(event) {
        const target = event.target;
        const value = target.type === 'checkbox' ? target.checked : target.value;
        const name = target.name;

        this.setState({
            [name]: value
        });
    }

    handleSpend(event) {
        event.preventDefault();
        console.log(this.props);
        fetch('/api/v1/transaction/add/' + this.props.match.params.id, {
            method: 'post',
            body: JSON.stringify({
                'title': this.state.title,
                'amount': this.state.amount,
                'type': 'spend'
            })
        })
            .then(res => res.json())
            .then(
                (result) => {

                },
                (error) => {
                    this.setState({isLoaded: true, error});
                }
            )

    }
}

class TransactionList extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            transactions: {},
            isLoaded: false,
            error: null
        }
    }

    componentDidMount() {
        fetch('/api/v1/transaction/list/' + this.props.match.params.id)
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
            console.log(this.state);
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

ReactDOM.render(<BrowserRouter><App/></BrowserRouter>, document.getElementById('app'));
