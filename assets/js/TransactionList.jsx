import React from 'react';
import {Link} from 'react-router-dom'
import MoneyAmount from './MoneyAmount.jsx';

export default class TransactionList extends React.Component {
    render() {
        const transactions = this.props.transactions;
        return (
            <ul className="transaction-list">
                {transactions.map((transaction) =>
                    <li className={transaction.type} key={transaction.id}>
                            <span className="title">
                                <Link to={{ pathname: "/transaction/view/" + transaction.id }}>
                                    {transaction.title}
                                </Link>
                            </span>
                        <MoneyAmount amount={transaction.amount}/>

                    </li>
                )}
            </ul>
        )
    }
}
