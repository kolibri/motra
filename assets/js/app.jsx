require('../sass/app.scss');
import React from 'react';
import ReactDOM from 'react-dom';
import {
    BrowserRouter,
    Route,
    Link
} from 'react-router-dom'

import AccountInfoList from './AccountInfoList.jsx';
import AddAccount from './AddAccount.jsx';
import TransactionView from './TransactionView.jsx';
import AccountView from './AccountView.jsx';

class App extends React.Component {
    render() {
        return (
            <div>
                <Route path="/" component={AccountInfoList}/>
                <Route path="/account/add" component={AddAccount}/>
                <Route path="/account/view/:id" component={AccountView}/>
                <Route path="/transaction/view/:id" component={TransactionView}/>
            </div>
        );
    }
}

ReactDOM.render(<BrowserRouter><App/></BrowserRouter>, document.getElementById('app'));
