require('../sass/app.scss');
import React from 'react';
import ReactDOM from 'react-dom';
import {
    BrowserRouter,
    Route,
    Link
} from 'react-router-dom'

import AccountInfoList from './AccountInfoList.jsx';
import CurrentAccount from './CurrentAccount.jsx';
import AddTransaction from './AddTransaction.jsx';
import TransactionList from './TransactionList.jsx';
import AddAccount from './AddAccount.jsx';

class App extends React.Component {
    render() {
        return (
            <div>
                <Route path="/" component={AccountInfoList}/>
                <Route path="/account/view/:id" component={CurrentAccount}/>
                <Route path="/account/view/:id" component={AddTransaction}/>
                <Route path="/account/view/:id" component={TransactionList}/>
                <Route path="/account/add" component={AddAccount}/>
            </div>
        );
    }
}

ReactDOM.render(<BrowserRouter><App/></BrowserRouter>, document.getElementById('app'));
