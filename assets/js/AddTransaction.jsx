import React from 'react';

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
            <form onSubmit={this.handleSpend} className="add-transaction">
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

        fetch('/api/v1/transaction/', {
            method: 'post',
            body: JSON.stringify({
                'title': this.state.title,
                'amount': this.state.amount,
                'type': 'spend',
                'account': this.props.account.id
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
        console.log(this);
        this.props.handleSubmit(this.props.account.id);
    }
}
