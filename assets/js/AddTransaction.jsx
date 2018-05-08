import React from 'react';
import { Link } from 'react-router-dom'

export default class AddTransaction extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            amount: 0,
            title: ''
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
